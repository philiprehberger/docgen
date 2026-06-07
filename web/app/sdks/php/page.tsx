import { SdkPage } from "../../../components/SdkPage";

export const metadata = { title: "PHP SDK" };

export default function Page() {
  return (
    <SdkPage
      snippets={{
        lang: "PHP",
        pkg: "philiprehberger/docgen",
        install: "composer require philiprehberger/docgen",
        sourceUrl: "https://github.com/philiprehberger/docgen/tree/main/sdks/php",
        quickstartLang: "php",
        quickstart: `<?php

use Docgen\\Client\\Configuration;
use Docgen\\Client\\Api\\TemplatesApi;
use Docgen\\Client\\Api\\RendersApi;
use Docgen\\Client\\Model\\TemplateCreate;
use Docgen\\Client\\Model\\RenderCreate;
use Docgen\\Sdk\\PollRender;
use GuzzleHttp\\Client as HttpClient;

$config = (new Configuration())
    ->setHost('https://api.docgen.philiprehberger.com')
    ->setAccessToken(getenv('DOCGEN_API_KEY'));   // docgen_live_…

$http = new HttpClient();
$templates = new TemplatesApi($http, $config);
$renders = new RendersApi($http, $config);

// 1. Create a template
$template = $templates->createTemplate(new TemplateCreate([
    'name' => 'Invoice',
    'body' => '<h1>Invoice {{ number }}</h1><p>Total: {{ total }}</p>',
]));

// 2. Freeze v1
$templates->createTemplateVersion($template->getId());

// 3. Submit a render
$render = $renders->createRender(new RenderCreate([
    'template_id' => $template->getId(),
    'formats' => ['pdf'],
    'data' => ['number' => 'INV-001', 'total' => '$2,625.00'],
]));

// 4. Poll until terminal
$done = PollRender::until($renders, $render->getId(), ['max_wait_ms' => 30000]);

foreach ($done->getOutputs() as $output) {
    if ($output->getFormat() === 'pdf') {
        echo "Download: " . $output->getUrl() . PHP_EOL;
    }
}`,
        pollLang: "php",
        poll: `use Docgen\\Sdk\\PollRender;
use Docgen\\Sdk\\PollRenderTimeout;

try {
    $done = PollRender::until($renders, $render->getId(), [
        'max_wait_ms' => 30000,
        'initial_interval_ms' => 500,
        'max_interval_ms' => 5000,
        'backoff_factor' => 1.6,
    ]);
} catch (PollRenderTimeout $e) {
    // $e->renderId, $e->maxWaitMs
}`,
        notes: (
          <>
            <p>
              <strong>Laravel integration.</strong> The client is plain PSR
              code — wrap it in a service binding if you want Laravel to
              inject your <code className="text-amber-300/90 text-xs">DOCGEN_API_KEY</code>{" "}
              from <code className="text-amber-300/90 text-xs">config/services.php</code>.
              The model layer plays nicely with Eloquent factories for
              integration tests.
            </p>
          </>
        ),
      }}
    />
  );
}
