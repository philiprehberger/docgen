<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HealthTest extends TestCase
{
    #[Test]
    public function it_returns_healthy_without_authentication(): void
    {
        $response = $this->getJson('/v1/healthz');

        $response->assertOk()
            ->assertJsonPath('healthy', true)
            ->assertJsonStructure(['version', 'queue_depth', 'twig_version', 'php_version']);
    }
}
