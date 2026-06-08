<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Template;
use App\Models\TemplateVersion;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TemplateVersionTest extends TestCase
{
    use RefreshDatabase;

    private Workspace $workspace;

    private string $bearer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workspace = Workspace::create(['name' => 'Acme', 'is_sandbox' => false]);
        [, $plaintext] = ApiKey::mint($this->workspace, 'CI key');
        $this->bearer = $plaintext;
    }

    #[Test]
    public function it_freezes_a_version_with_an_auto_assigned_label(): void
    {
        $template = $this->template();

        $response = $this->auth()->postJson("/v1/templates/{$template->id}/versions");

        $response->assertStatus(201)
            ->assertJsonPath('label', 'v1')
            ->assertJsonPath('template_id', $template->id);
    }

    #[Test]
    public function it_auto_increments_version_labels(): void
    {
        $template = $this->template();

        $this->auth()->postJson("/v1/templates/{$template->id}/versions")->assertJsonPath('label', 'v1');
        $this->auth()->postJson("/v1/templates/{$template->id}/versions")->assertJsonPath('label', 'v2');
        $this->auth()->postJson("/v1/templates/{$template->id}/versions")->assertJsonPath('label', 'v3');
    }

    #[Test]
    public function it_snapshots_the_current_body_into_the_version(): void
    {
        $template = $this->template('<p>v1 body</p>');

        $this->auth()->postJson("/v1/templates/{$template->id}/versions");

        // Edit the draft.
        $template->forceFill(['body' => '<p>v2 body changed</p>'])->save();

        $version = TemplateVersion::query()->where('template_id', $template->id)->first();

        $this->assertSame('<p>v1 body</p>', $version->body_snapshot);
    }

    #[Test]
    public function frozen_versions_are_immutable_even_if_the_draft_changes(): void
    {
        $template = $this->template('Hello {{ name }}');

        $this->auth()->postJson("/v1/templates/{$template->id}/versions");

        $template->forceFill(['body' => 'Goodbye {{ name }}'])->save();

        $response = $this->auth()->getJson("/v1/templates/{$template->id}/versions/v1");

        $response->assertOk()->assertJsonPath('body', 'Hello {{ name }}');
    }

    #[Test]
    public function it_persists_the_field_schema_at_freeze_time(): void
    {
        $template = $this->template('{{ client.name }} owes {{ total }}');

        $this->auth()->postJson("/v1/templates/{$template->id}/versions");

        $response = $this->auth()->getJson("/v1/templates/{$template->id}/versions/v1");

        $response->assertOk();
        $schema = $response->json('fields_schema');
        $names = array_column($schema['fields'], 'name');
        sort($names);

        $this->assertSame(['client', 'total'], $names);
    }

    #[Test]
    public function it_lists_versions_for_a_template(): void
    {
        $template = $this->template();

        $this->auth()->postJson("/v1/templates/{$template->id}/versions");
        $this->auth()->postJson("/v1/templates/{$template->id}/versions");

        $response = $this->auth()->getJson("/v1/templates/{$template->id}/versions");

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    #[Test]
    public function it_does_not_leak_versions_across_workspaces(): void
    {
        $other = Workspace::create(['name' => 'Other', 'is_sandbox' => false]);
        $theirTemplate = Template::create([
            'workspace_id' => $other->id, 'name' => 'Theirs', 'slug' => 'theirs',
            'body' => '<p>x</p>',
        ]);

        $this->auth()->postJson("/v1/templates/{$theirTemplate->id}/versions")->assertStatus(404);
    }

    private function template(string $body = '<p>Hello</p>'): Template
    {
        return Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'Invoice',
            'slug' => 'invoice-'.substr(bin2hex(random_bytes(3)), 0, 6),
            'body' => $body,
        ]);
    }

    private function auth(): self
    {
        return $this->withHeader('Authorization', "Bearer {$this->bearer}");
    }
}
