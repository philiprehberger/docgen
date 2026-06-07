<?php

namespace App\Services\Rendering;

/**
 * Pre-flight scan of rendered HTML for absolute asset URLs that point at
 * private / loopback / link-local addresses.
 *
 * Chromium will dutifully fetch anything it sees in `src` / `href` / inline
 * CSS `url(...)`, so without a guard a malicious template could probe the
 * EC2 host's internal network (169.254.169.254 metadata, 127.0.0.1:6379,
 * 10.0.0.0/8 RDS, etc).
 *
 * This is a best-effort static guard — proper isolation would run Chromium
 * in a network namespace with only the public internet reachable. For a
 * portfolio demo, the static guard plus a couple of well-known Chromium
 * flags is the honest middle ground.
 */
class AssetUrlGuard
{
    /** @return array<int, string> URLs that violated the guard. Empty array = safe. */
    public function findForbiddenUrls(string $html): array
    {
        $found = [];

        // src="...", href="...", url("...")
        preg_match_all('/(?:src|href)\s*=\s*["\'](https?:\/\/[^"\']+)["\']/i', $html, $matches);
        $urls = $matches[1] ?? [];

        preg_match_all('/url\(\s*["\']?(https?:\/\/[^)"\']+)["\']?\s*\)/i', $html, $cssMatches);
        $urls = array_merge($urls, $cssMatches[1] ?? []);

        foreach ($urls as $url) {
            if ($this->isForbidden($url)) {
                $found[] = $url;
            }
        }

        return array_values(array_unique($found));
    }

    public function isForbidden(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        if ($host === null || $host === '') {
            return true;
        }

        // Literal IP? Check it.
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return $this->isPrivateIp($host);
        }

        // DNS lookup. Refuse if any A or AAAA record lands in a private range.
        $records = @dns_get_record($host, DNS_A | DNS_AAAA);

        if (! is_array($records)) {
            // If we couldn't resolve, refuse — better safe than sorry.
            return true;
        }

        foreach ($records as $record) {
            $ip = $record['ip'] ?? $record['ipv6'] ?? null;

            if ($ip !== null && $this->isPrivateIp($ip)) {
                return true;
            }
        }

        return false;
    }

    private function isPrivateIp(string $ip): bool
    {
        // FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE strips:
        //   - 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16  (private)
        //   - 127.0.0.0/8, 169.254.0.0/16, 0.0.0.0/8, etc. (reserved)
        //   - and the IPv6 equivalents
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
