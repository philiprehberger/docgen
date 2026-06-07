# philiprehberger/docgen

Official PHP SDK for the [Docgen](https://docgen.philiprehberger.com) document-generation API.

## Install

```bash
composer require philiprehberger/docgen
```

PHP ^8.2.

## Quickstart

```php
use Docgen\Client\Configuration;
use Docgen\Client\Api\TemplatesApi;
use Docgen\Client\Api\RendersApi;
use Docgen\Client\Model\TemplateCreate;
use Docgen\Client\Model\RenderCreate;
use Docgen\Sdk\PollRender;
use GuzzleHttp\Client as HttpClient;

$config = (new Configuration())
    ->setHost('https://api.docgen.philiprehberger.com')
    ->setAccessToken(getenv('DOCGEN_API_KEY'));   // docgen_live_…

$http = new HttpClient();
$templates = new TemplatesApi($http, $config);
$renders = new RendersApi($http, $config);

// 1. Author a template + freeze a version
$template = $templates->createTemplate(new TemplateCreate([
    'name' => 'Invoice',
    'body' => '<h1>Invoice {{ number }}</h1><p>Total: {{ total }}</p>',
]));

$version = $templates->createTemplateVersion($template->getId());

// 2. Submit a render — async by default
$render = $renders->createRender(new RenderCreate([
    'template_id' => $template->getId(),
    'formats' => ['pdf'],
    'data' => ['number' => 'INV-001', 'total' => '$2,625.00'],
]));

// 3. Poll until terminal (hand-written ergonomics helper, not generated)
$done = PollRender::until($renders, $render->getId(), ['max_wait_ms' => 30000]);

foreach ($done->getOutputs() as $output) {
    if ($output->getFormat() === 'pdf') {
        echo "Download: " . $output->getUrl() . PHP_EOL;
    }
}
```

## `PollRender::until`

The generated `getRender` is the raw building block; `PollRender::until` is the hand-written ergonomics layer. Exponential backoff, configurable wall-clock budget, throws `PollRenderTimeout` if the deadline elapses.

## License

MIT.
