<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TemplateCrudTest extends TestCase
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
    public function it_rejects_requests_without_a_bearer_token(): void
    {
        $this->getJson('/v1/templates')
            ->assertStatus(401)
            ->assertHeader('Content-Type', 'application/problem+json');
    }

    #[Test]
    public function it_rejects_requests_with_an_invalid_bearer_token(): void
    {
        $this->withHeader('Authorization', 'Bearer docgen_live_nope')
            ->getJson('/v1/templates')
            ->assertStatus(401);
    }

    #[Test]
    public function it_creates_a_template(): void
    {
        $response = $this->auth()->postJson('/v1/templates', [
            'name' => 'Welcome Letter',
            'body' => '<h1>Hello {{ name }}</h1>',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'slug', 'body', 'engine'])
            ->assertJsonPath('name', 'Welcome Letter')
            ->assertJsonPath('slug', 'welcome-letter')
            ->assertJsonPath('engine', 'twig');

        $this->assertSame(1, Template::count());
    }

    #[Test]
    public function it_rejects_a_duplicate_slug(): void
    {
        $this->auth()->postJson('/v1/templates', [
            'name' => 'Letter', 'slug' => 'letter', 'body' => '{{ a }}',
        ])->assertStatus(201);

        $this->auth()->postJson('/v1/templates', [
            'name' => 'Another Letter', 'slug' => 'letter', 'body' => '{{ b }}',
        ])->assertStatus(422);
    }

    #[Test]
    public function it_rejects_an_unparseable_body(): void
    {
        $this->auth()->postJson('/v1/templates', [
            'name' => 'Broken', 'body' => '{% for %}',
        ])->assertStatus(422);
    }

    #[Test]
    public function it_lists_templates_for_the_current_workspace_only(): void
    {
        Template::create([
            'workspace_id' => $this->workspace->id, 'name' => 'Mine',
            'slug' => 'mine', 'body' => '{{ a }}',
        ]);

        $other = Workspace::create(['name' => 'Other', 'is_sandbox' => false]);
        Template::create([
            'workspace_id' => $other->id, 'name' => 'Theirs',
            'slug' => 'theirs', 'body' => '{{ b }}',
        ]);

        $response = $this->auth()->getJson('/v1/templates');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Mine', $response->json('data.0.name'));
    }

    #[Test]
    public function it_updates_a_template_draft(): void
    {
        $template = Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'Original', 'slug' => 'original',
            'body' => '<p>v0</p>',
        ]);

        $response = $this->auth()->patchJson("/v1/templates/{$template->id}", [
            'body' => '<p>v1 with {{ name }}</p>',
        ]);

        $response->assertOk()->assertJsonPath('body', '<p>v1 with {{ name }}</p>');
    }

    #[Test]
    public function it_archives_a_template_on_delete(): void
    {
        $template = Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'Goner', 'slug' => 'goner', 'body' => '{{ a }}',
        ]);

        $this->auth()->deleteJson("/v1/templates/{$template->id}")->assertStatus(204);

        $this->assertNotNull($template->fresh()->archived_at);

        $this->auth()->getJson("/v1/templates/{$template->id}")->assertStatus(404);
    }

    #[Test]
    public function it_returns_field_schema_for_a_template(): void
    {
        $template = Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'Invoice', 'slug' => 'invoice',
            'body' => '<p>Hello {{ client.name }}</p>{% for line in lines %}{{ line.amount }}{% endfor %}',
        ]);

        $response = $this->auth()->getJson("/v1/templates/{$template->id}/fields");

        $response->assertOk()
            ->assertJsonStructure(['fields']);

        $names = array_column($response->json('fields'), 'name');
        sort($names);

        $this->assertSame(['client', 'lines'], $names);
    }

    #[Test]
    public function it_returns_404_for_unknown_template_id(): void
    {
        $this->auth()->getJson('/v1/templates/01HXXXXXXXXXXXXXXXXXXXXXXX')->assertStatus(404);
    }

    private function auth(): self
    {
        return $this->withHeader('Authorization', "Bearer {$this->bearer}");
    }
}
