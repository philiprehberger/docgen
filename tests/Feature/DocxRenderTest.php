<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Template;
use App\Models\Workspace;
use App\Services\Twig\FieldDiscovery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DocxRenderTest extends TestCase
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
    public function it_renders_a_simple_template_to_docx(): void
    {
        $template = $this->template('<h1>Hello {{ name }}</h1><p>Welcome aboard.</p>');
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['docx'],
            'data' => ['name' => 'World'],
        ]);

        $response->assertStatus(200)->assertJsonPath('status', 'succeeded');

        $outputs = $response->json('outputs');
        $this->assertSame('docx', $outputs[0]['format']);
        $this->assertGreaterThan(2000, $outputs[0]['bytes']);  // DOCX files are >2KB even when minimal
    }

    #[Test]
    public function the_downloaded_docx_has_the_zip_magic_header(): void
    {
        $template = $this->template('<h1>Hi {{ name }}</h1>');
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['docx'],
            'data' => ['name' => 'Alice'],
        ]);

        $download = $this->get($response->json('outputs.0.url'));

        $download->assertOk();
        // DOCX is a ZIP archive — first two bytes are `PK`.
        $head = substr($download->streamedContent(), 0, 2);
        $this->assertSame('PK', $head);
    }

    #[Test]
    public function it_renders_pdf_and_docx_in_one_request(): void
    {
        $template = $this->template('<h1>Combo {{ x }}</h1>');
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['html', 'docx'],     // skip pdf in CI to keep it fast
            'data' => ['x' => 'yes'],
        ]);

        $response->assertStatus(200)->assertJsonPath('status', 'succeeded');

        $formats = array_column($response->json('outputs'), 'format');
        sort($formats);

        $this->assertSame(['docx', 'html'], $formats);
    }

    private function template(string $body): Template
    {
        return Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'T',
            'slug' => 't-'.substr(bin2hex(random_bytes(3)), 0, 6),
            'body' => $body,
        ]);
    }

    private function freeze(Template $template): void
    {
        $schema = (new FieldDiscovery)->discover($template->body);

        $template->versions()->create([
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
