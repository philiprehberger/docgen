<?php

namespace Tests\Unit;

use App\Services\Rendering\AssetUrlGuard;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AssetUrlGuardTest extends TestCase
{
    #[Test]
    public function it_flags_loopback_addresses(): void
    {
        $guard = new AssetUrlGuard;

        $this->assertTrue($guard->isForbidden('http://127.0.0.1/secret'));
        $this->assertTrue($guard->isForbidden('http://localhost/secret'));
    }

    #[Test]
    public function it_flags_private_ranges(): void
    {
        $guard = new AssetUrlGuard;

        $this->assertTrue($guard->isForbidden('http://10.0.0.5/x'));
        $this->assertTrue($guard->isForbidden('http://192.168.1.10/x'));
        $this->assertTrue($guard->isForbidden('http://172.20.0.1/x'));
    }

    #[Test]
    public function it_flags_link_local(): void
    {
        $this->assertTrue((new AssetUrlGuard)->isForbidden('http://169.254.169.254/latest/meta-data/'));
    }

    #[Test]
    public function it_extracts_forbidden_urls_from_html_attributes_and_css(): void
    {
        $html = <<<HTML
            <img src="http://127.0.0.1/leak.png">
            <a href="https://192.168.1.1/admin">x</a>
            <link href="https://example.com/styles.css">
            <style>body{background: url('http://10.0.0.1/bg.png')}</style>
        HTML;

        $forbidden = (new AssetUrlGuard)->findForbiddenUrls($html);

        $this->assertContains('http://127.0.0.1/leak.png', $forbidden);
        $this->assertContains('https://192.168.1.1/admin', $forbidden);
        $this->assertContains('http://10.0.0.1/bg.png', $forbidden);
        $this->assertNotContains('https://example.com/styles.css', $forbidden);
    }

    #[Test]
    public function it_returns_empty_array_for_template_with_no_absolute_urls(): void
    {
        $html = '<h1>Hi</h1><img src="/relative.png">';

        $this->assertSame([], (new AssetUrlGuard)->findForbiddenUrls($html));
    }
}
