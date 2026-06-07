<?php

return [
    'rendered_path' => env('DOCGEN_RENDERED_PATH', 'rendered'),
    'assets_path' => env('DOCGEN_ASSETS_PATH', 'assets'),

    'default_signed_url_ttl' => (int) env('DOCGEN_DEFAULT_SIGNED_URL_TTL', 3600),
    'max_signed_url_ttl' => (int) env('DOCGEN_MAX_SIGNED_URL_TTL', 86400),

    'template_body_max_bytes' => (int) env('DOCGEN_TEMPLATE_BODY_MAX_BYTES', 262144),
    'input_data_max_bytes' => (int) env('DOCGEN_INPUT_DATA_MAX_BYTES', 1048576),

    'sync_render_timeout' => (int) env('DOCGEN_SYNC_RENDER_TIMEOUT', 15),

    // Chromium binary for PDF rendering. When empty, PdfRenderer auto-detects
    // puppeteer's bundled Chrome under ~/.cache/puppeteer/chrome.
    'chrome_path' => env('DOCGEN_CHROME_PATH'),

    // Node binary Browsershot should invoke. When empty, uses `node` from PATH.
    'node_bin' => env('DOCGEN_NODE_BIN'),

    'version' => '0.5.0',
];
