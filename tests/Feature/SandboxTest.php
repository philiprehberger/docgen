<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SandboxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    #[Test]
    public function it_mints_a_sandbox_key_without_authentication(): void
    {
        $response = $this->postJson('/v1/sandbox/keys');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'api_key',
                'prefix',
                'last_four',
                'workspace_id',
                'expires_at',
                'sample_template_ids',
                'limits' => ['renders_per_minute', 'renders_per_day', 'key_lifetime_minutes'],
                'notice',
            ])
            ->assertJsonPath('prefix', ApiKey::PREFIX_TEST);

        $this->assertStringStartsWith('docgen_test_', $response->json('api_key'));
    }

    #[Test]
    public function the_minted_workspace_is_flagged_sandbox(): void
    {
        $response = $this->postJson('/v1/sandbox/keys');

        $workspace = Workspace::find($response->json('workspace_id'));

        $this->assertTrue($workspace->is_sandbox);
    }

    #[Test]
    public function it_seeds_three_sample_templates(): void
    {
        $response = $this->postJson('/v1/sandbox/keys');

        $ids = $response->json('sample_template_ids');

        $this->assertCount(3, $ids);
        $this->assertArrayHasKey('invoice', $ids);
        $this->assertArrayHasKey('offer-letter', $ids);
        $this->assertArrayHasKey('certificate', $ids);

        $invoice = Template::find($ids['invoice']);
        $this->assertNotNull($invoice);
        $this->assertSame(1, $invoice->versions()->count());
    }

    #[Test]
    public function it_rate_limits_per_ip(): void
    {
        $this->postJson('/v1/sandbox/keys')->assertStatus(201);

        $this->postJson('/v1/sandbox/keys')
            ->assertStatus(429)
            ->assertHeader('Content-Type', 'application/problem+json');
    }

    #[Test]
    public function the_minted_key_works_against_the_api(): void
    {
        $mint = $this->postJson('/v1/sandbox/keys');
        $key = $mint->json('api_key');

        // Use the minted key to create a new template in the sandbox workspace.
        $this->withHeader('Authorization', "Bearer {$key}")
            ->postJson('/v1/templates', [
                'name' => 'My test',
                'body' => '<p>Hi {{ x }}</p>',
            ])->assertStatus(201);
    }
}
