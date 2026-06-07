<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Render;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RenderTest extends TestCase
{
    use RefreshDatabase;

    private Workspace $workspace;

    private string $bearer;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->workspace = Workspace::create(['name' => 'Acme', 'is_sandbox' => false]);
        [, $plaintext] = ApiKey::mint($this->workspace, 'CI key');
        $this->bearer = $plaintext;
    }

    #[Test]
    public function it_renders_html_sync_and_persists_an_output(): void
    {
        $template = $this->template('<p>Hello {{ name }}</p>');
        $this->freezeVersion($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['html'],
            'data' => ['name' => 'World'],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'succeeded')
            ->assertJsonPath('template_version_label', 'v1');

        $outputs = $response->json('outputs');
        $this->assertCount(1, $outputs);
        $this->assertSame('html', $outputs[0]['format']);
        $this->assertNotEmpty($outputs[0]['url']);
        $this->assertGreaterThan(0, $outputs[0]['bytes']);
    }

    #[Test]
    public function it_queues_async_renders_and_returns_202(): void
    {
        $template = $this->template();
        $this->freezeVersion($template);

        // Default sync=false; sync queue runs the job inline so it will be terminal,
        // but the controller still returns 202 because we didn't pass sync=true.
        $response = $this->auth()->postJson('/v1/renders', [
            'template_id' => $template->id,
            'formats' => ['html'],
            'data' => ['name' => 'World'],
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['id', 'status', 'poll_url']);
    }

    #[Test]
    public function it_rejects_renders_without_a_frozen_version(): void
    {
        $template = $this->template();

        $this->auth()->postJson('/v1/renders', [
            'template_id' => $template->id,
            'formats' => ['html'],
            'data' => ['name' => 'World'],
        ])->assertStatus(422);
    }

    #[Test]
    public function it_rejects_missing_required_merge_fields(): void
    {
        $template = $this->template('Hello {{ first_name }} {{ last_name }}');
        $this->freezeVersion($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['html'],
            'data' => ['first_name' => 'A'],
        ]);

        $response->assertStatus(422)
            ->assertHeader('Content-Type', 'application/problem+json');
    }

    #[Test]
    public function it_rejects_unsupported_formats(): void
    {
        $template = $this->template();
        $this->freezeVersion($template);

        // `tiff` is never going to be a registered format.
        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['tiff'],
            'data' => ['name' => 'x'],
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_returns_the_cached_record_on_an_idempotent_replay(): void
    {
        $template = $this->template('Hi {{ name }}');
        $this->freezeVersion($template);

        $first = $this->auth()
            ->withHeader('Idempotency-Key', 'job-42')
            ->postJson('/v1/renders?sync=true', [
                'template_id' => $template->id,
                'formats' => ['html'],
                'data' => ['name' => 'Alice'],
            ]);

        $second = $this->auth()
            ->withHeader('Idempotency-Key', 'job-42')
            ->postJson('/v1/renders?sync=true', [
                'template_id' => $template->id,
                'formats' => ['html'],
                'data' => ['name' => 'Alice'],
            ]);

        $first->assertOk();
        $second->assertOk();

        $this->assertSame($first->json('id'), $second->json('id'));
        $this->assertSame(1, Render::count());
    }

    #[Test]
    public function it_409s_on_idempotency_key_collision_with_different_data(): void
    {
        $template = $this->template('Hi {{ name }}');
        $this->freezeVersion($template);

        $this->auth()
            ->withHeader('Idempotency-Key', 'job-42')
            ->postJson('/v1/renders?sync=true', [
                'template_id' => $template->id,
                'formats' => ['html'],
                'data' => ['name' => 'Alice'],
            ])->assertOk();

        $this->auth()
            ->withHeader('Idempotency-Key', 'job-42')
            ->postJson('/v1/renders?sync=true', [
                'template_id' => $template->id,
                'formats' => ['html'],
                'data' => ['name' => 'Bob'],
            ])->assertStatus(409);
    }

    #[Test]
    public function it_cancels_a_queued_render(): void
    {
        $template = $this->template();
        $version = $this->freezeVersion($template);

        $render = Render::create([
            'workspace_id' => $this->workspace->id,
            'template_id' => $template->id,
            'template_version_id' => $version->id,
            'template_version_label' => $version->label,
            'status' => Render::STATUS_QUEUED,
            'formats_requested' => ['html'],
            'signed_url_ttl_seconds' => 3600,
            'queued_at' => now(),
        ]);

        $this->auth()->deleteJson("/v1/renders/{$render->id}")->assertStatus(204);

        $this->assertSame(Render::STATUS_CANCELLED, $render->fresh()->status);
    }

    #[Test]
    public function it_409s_on_cancel_after_terminal_state(): void
    {
        $template = $this->template();
        $version = $this->freezeVersion($template);

        $render = Render::create([
            'workspace_id' => $this->workspace->id,
            'template_id' => $template->id,
            'template_version_id' => $version->id,
            'template_version_label' => $version->label,
            'status' => Render::STATUS_SUCCEEDED,
            'formats_requested' => ['html'],
            'signed_url_ttl_seconds' => 3600,
        ]);

        $this->auth()->deleteJson("/v1/renders/{$render->id}")->assertStatus(409);
    }

    #[Test]
    public function the_signed_download_url_serves_the_rendered_output(): void
    {
        $template = $this->template('<p>Hi {{ name }}</p>');
        $this->freezeVersion($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['html'],
            'data' => ['name' => 'Bob'],
        ]);

        $response->assertOk();
        $signedUrl = $response->json('outputs.0.url');
        $this->assertNotNull($signedUrl);

        $download = $this->get($signedUrl);

        $download->assertOk();
        $this->assertStringContainsString('Hi Bob', $download->streamedContent());
    }

    #[Test]
    public function an_unsigned_download_url_is_rejected(): void
    {
        $template = $this->template();
        $version = $this->freezeVersion($template);
        $render = Render::create([
            'workspace_id' => $this->workspace->id,
            'template_id' => $template->id,
            'template_version_id' => $version->id,
            'template_version_label' => $version->label,
            'status' => Render::STATUS_SUCCEEDED,
            'formats_requested' => ['html'],
            'outputs' => [['format' => 'html', 'path' => 'rendered/whatever', 'bytes' => 1, 'sha256' => 'x']],
            'signed_url_ttl_seconds' => 3600,
        ]);

        // No signature: 401.
        $this->get("/v1/renders/{$render->id}/outputs/html")->assertStatus(401);
    }

    #[Test]
    public function it_returns_404_for_a_render_in_another_workspace(): void
    {
        $other = Workspace::create(['name' => 'Other', 'is_sandbox' => false]);
        $theirTemplate = Template::create([
            'workspace_id' => $other->id, 'name' => 'T', 'slug' => 'tt', 'body' => '<p>x</p>',
        ]);
        $theirVersion = $theirTemplate->versions()->create([
            'label' => 'v1', 'body_snapshot' => $theirTemplate->body,
            'fields_schema' => ['fields' => []], 'created_at' => now(),
        ]);

        $theirRender = Render::create([
            'workspace_id' => $other->id,
            'template_id' => $theirTemplate->id,
            'template_version_id' => $theirVersion->id,
            'template_version_label' => 'v1',
            'status' => Render::STATUS_QUEUED,
            'formats_requested' => ['html'],
            'signed_url_ttl_seconds' => 3600,
        ]);

        $this->auth()->getJson("/v1/renders/{$theirRender->id}")->assertStatus(404);
    }

    private function template(string $body = '<p>Hello {{ name }}</p>'): Template
    {
        return Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'T',
            'slug' => 't-' . substr(bin2hex(random_bytes(3)), 0, 6),
            'body' => $body,
        ]);
    }

    private function freezeVersion(Template $template)
    {
        $schema = (new \App\Services\Twig\FieldDiscovery)->discover($template->body);

        return $template->versions()->create([
            'label' => 'v1',
            'body_snapshot' => $template->body,
            'fields_schema' => $schema,
            'created_at' => now(),
        ]);
    }

    private function auth(): self
    {
        return $this->withHeader('Authorization', "Bearer {$this->bearer}");
    }
}
