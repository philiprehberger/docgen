# DocgenClient

Docgen is a document generation API. POST a template + data, get HTML,
PDF, or DOCX back.

This spec covers the surface delivered through Phase 5 of the build:
templates, versioning, field discovery, render submit (async + sync),
polling, signed-URL download, and HTML/PDF/DOCX output. SDKs are
generated from this spec; controllers conform to it.


For more information, please visit [https://philiprehberger.com](https://philiprehberger.com).

## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/DocgenClient/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new DocgenClient\Api\HealthApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->getHealth();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling HealthApi->getHealth: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.docgen.philiprehberger.com*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*HealthApi* | [**getHealth**](docs/Api/HealthApi.md#gethealth) | **GET** /v1/healthz | Liveness and queue depth
*RendersApi* | [**cancelRender**](docs/Api/RendersApi.md#cancelrender) | **DELETE** /v1/renders/{renderId} | Cancel a queued or in-flight render
*RendersApi* | [**createRender**](docs/Api/RendersApi.md#createrender) | **POST** /v1/renders | Submit a render job
*RendersApi* | [**downloadRenderOutput**](docs/Api/RendersApi.md#downloadrenderoutput) | **GET** /v1/renders/{renderId}/outputs/{format} | Redirect to a signed download URL for one output format
*RendersApi* | [**getRender**](docs/Api/RendersApi.md#getrender) | **GET** /v1/renders/{renderId} | Poll a render&#39;s status
*RendersApi* | [**listRenders**](docs/Api/RendersApi.md#listrenders) | **GET** /v1/renders | List renders in the current workspace
*TemplatesApi* | [**archiveTemplate**](docs/Api/TemplatesApi.md#archivetemplate) | **DELETE** /v1/templates/{templateId} | Archive a template (soft delete)
*TemplatesApi* | [**createTemplate**](docs/Api/TemplatesApi.md#createtemplate) | **POST** /v1/templates | Create a template (draft)
*TemplatesApi* | [**getTemplate**](docs/Api/TemplatesApi.md#gettemplate) | **GET** /v1/templates/{templateId} | Fetch a template
*TemplatesApi* | [**getTemplateFields**](docs/Api/TemplatesApi.md#gettemplatefields) | **GET** /v1/templates/{templateId}/fields | Discover the merge-field schema of the current draft
*TemplatesApi* | [**listTemplates**](docs/Api/TemplatesApi.md#listtemplates) | **GET** /v1/templates | List templates in the current workspace
*TemplatesApi* | [**updateTemplate**](docs/Api/TemplatesApi.md#updatetemplate) | **PATCH** /v1/templates/{templateId} | Update the current draft of a template
*VersionsApi* | [**createTemplateVersion**](docs/Api/VersionsApi.md#createtemplateversion) | **POST** /v1/templates/{templateId}/versions | Freeze the current draft as a new version
*VersionsApi* | [**getTemplateVersion**](docs/Api/VersionsApi.md#gettemplateversion) | **GET** /v1/templates/{templateId}/versions/{versionLabel} | Fetch a frozen version
*VersionsApi* | [**listTemplateVersions**](docs/Api/VersionsApi.md#listtemplateversions) | **GET** /v1/templates/{templateId}/versions | List frozen versions of a template

## Models

- [Field](docs/Model/Field.md)
- [FieldSchema](docs/Model/FieldSchema.md)
- [Health](docs/Model/Health.md)
- [Problem](docs/Model/Problem.md)
- [Render](docs/Model/Render.md)
- [RenderCreate](docs/Model/RenderCreate.md)
- [RenderError](docs/Model/RenderError.md)
- [RenderList](docs/Model/RenderList.md)
- [RenderOutput](docs/Model/RenderOutput.md)
- [Template](docs/Model/Template.md)
- [TemplateCreate](docs/Model/TemplateCreate.md)
- [TemplateList](docs/Model/TemplateList.md)
- [TemplateUpdate](docs/Model/TemplateUpdate.md)
- [TemplateVersion](docs/Model/TemplateVersion.md)
- [TemplateVersionList](docs/Model/TemplateVersionList.md)

## Authorization

Authentication schemes defined for the API:
### bearerAuth

- **Type**: Bearer authentication (docgen_live_…)

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

hello@philiprehberger.com

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `0.5.0`
    - Package version: `0.5.0`
    - Generator version: `7.22.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
