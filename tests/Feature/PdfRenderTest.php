<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Template;
use App\Models\Workspace;
use App\Services\Twig\FieldDiscovery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Integration tests that spawn a real Chromium via Browsershot. Tagged
 * `pdf` so CI can skip them on environments without a working Chrome
 * binary (CI installs puppeteer; the actual host browser does the work).
 */
#[Group('pdf')]
class PdfRenderTest extends TestCase
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

        $this->skipIfNoChromium();
    }

    #[Test]
    public function it_renders_a_simple_template_to_pdf(): void
    {
        $template = $this->templateWithBody('<h1>Hello {{ name }}</h1>');
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['pdf'],
            'data' => ['name' => 'World'],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'succeeded');

        $outputs = $response->json('outputs');
        $this->assertCount(1, $outputs);
        $this->assertSame('pdf', $outputs[0]['format']);
        $this->assertGreaterThan(1000, $outputs[0]['bytes']);  // Real PDFs are 1KB+
    }

    #[Test]
    public function the_downloaded_pdf_has_the_pdf_magic_header(): void
    {
        $template = $this->templateWithBody('<h1>Hello {{ name }}</h1>');
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['pdf'],
            'data' => ['name' => 'World'],
        ]);

        $download = $this->get($response->json('outputs.0.url'));

        $download->assertOk();
        $head = substr($download->streamedContent(), 0, 5);
        $this->assertSame('%PDF-', $head);
    }

    #[Test]
    public function it_renders_the_invoice_sample_template(): void
    {
        $body = file_get_contents(__DIR__ . '/../../sample-templates/invoice.twig');

        $template = $this->templateWithBody($body);
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['pdf'],
            'data' => $this->invoiceFixture(),
        ]);

        $response->assertStatus(200)->assertJsonPath('status', 'succeeded');
        $this->assertGreaterThan(2000, $response->json('outputs.0.bytes'));
    }

    #[Test]
    public function it_refuses_to_render_a_template_referencing_a_private_ip(): void
    {
        $template = $this->templateWithBody('<img src="http://169.254.169.254/leak"><p>hi {{ x }}</p>');
        $this->freeze($template);

        $response = $this->auth()->postJson('/v1/renders?sync=true', [
            'template_id' => $template->id,
            'formats' => ['pdf'],
            'data' => ['x' => 'y'],
        ]);

        // Sync mode returns 200 once the render reaches a terminal state in time —
        // even if that terminal state is `failed`.
        $response->assertStatus(200)->assertJsonPath('status', 'failed');

        $this->assertStringContainsString(
            'forbidden',
            $response->json('error.message') ?? '',
        );
    }

    private function skipIfNoChromium(): void
    {
        $candidates = glob('/home/ubuntu/.cache/puppeteer/chrome/*/chrome-linux64/chrome');

        if (! $candidates || ! is_executable($candidates[0])) {
            $this->markTestSkipped('No puppeteer Chromium found on this host.');
        }
    }

    private function templateWithBody(string $body): Template
    {
        return Template::create([
            'workspace_id' => $this->workspace->id,
            'name' => 'T',
            'slug' => 't-' . substr(bin2hex(random_bytes(3)), 0, 6),
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

    private function invoiceFixture(): array
    {
        return [
            'sender' => [
                'name' => 'Northcliffe Legal LLP',
                'tagline' => 'Commercial counsel for growing businesses',
                'address_line_1' => '212 Tower Place, Suite 600',
                'address_line_2' => 'Portland, OR 97201',
                'email' => 'billing@northcliffe.example',
            ],
            'client' => [
                'name' => 'Ridgeline Supply Co.',
                'contact' => 'Accounts Payable',
                'email' => 'ap@ridgelinesupply.example',
            ],
            'invoice' => [
                'number' => 'INV-2026-0114',
                'issued_on' => 'June 7, 2026',
                'due_on' => 'July 7, 2026',
                'late_rate' => '1.5%',
            ],
            'lines' => [
                ['description' => 'Contract review — vendor MSA', 'quantity' => '4.0 hr', 'rate' => '$350', 'amount' => '$1,400'],
                ['description' => 'Drafting — NDA + addendum', 'quantity' => '2.5 hr', 'rate' => '$350', 'amount' => '$875'],
                ['description' => 'Strategy call (June 3)', 'quantity' => '1.0 hr', 'rate' => '$350', 'amount' => '$350'],
            ],
            'totals' => [
                'subtotal' => '$2,625.00',
                'tax_rate' => '0%',
                'tax' => '$0.00',
                'total' => '$2,625.00',
            ],
        ];
    }
}
