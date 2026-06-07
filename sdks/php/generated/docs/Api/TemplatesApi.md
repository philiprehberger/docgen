# DocgenClient\TemplatesApi

HTML+Twig templates owned by a workspace.

All URIs are relative to https://api.docgen.philiprehberger.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**archiveTemplate()**](TemplatesApi.md#archiveTemplate) | **DELETE** /v1/templates/{templateId} | Archive a template (soft delete) |
| [**createTemplate()**](TemplatesApi.md#createTemplate) | **POST** /v1/templates | Create a template (draft) |
| [**getTemplate()**](TemplatesApi.md#getTemplate) | **GET** /v1/templates/{templateId} | Fetch a template |
| [**getTemplateFields()**](TemplatesApi.md#getTemplateFields) | **GET** /v1/templates/{templateId}/fields | Discover the merge-field schema of the current draft |
| [**listTemplates()**](TemplatesApi.md#listTemplates) | **GET** /v1/templates | List templates in the current workspace |
| [**updateTemplate()**](TemplatesApi.md#updateTemplate) | **PATCH** /v1/templates/{templateId} | Update the current draft of a template |


## `archiveTemplate()`

```php
archiveTemplate($template_id)
```

Archive a template (soft delete)

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\TemplatesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string

try {
    $apiInstance->archiveTemplate($template_id);
} catch (Exception $e) {
    echo 'Exception when calling TemplatesApi->archiveTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |

### Return type

void (empty response body)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `createTemplate()`

```php
createTemplate($template_create): \DocgenClient\Model\Template
```

Create a template (draft)

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\TemplatesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_create = new \DocgenClient\Model\TemplateCreate(); // \DocgenClient\Model\TemplateCreate

try {
    $result = $apiInstance->createTemplate($template_create);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TemplatesApi->createTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_create** | [**\DocgenClient\Model\TemplateCreate**](../Model/TemplateCreate.md)|  | |

### Return type

[**\DocgenClient\Model\Template**](../Model/Template.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTemplate()`

```php
getTemplate($template_id): \DocgenClient\Model\Template
```

Fetch a template

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\TemplatesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string

try {
    $result = $apiInstance->getTemplate($template_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TemplatesApi->getTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |

### Return type

[**\DocgenClient\Model\Template**](../Model/Template.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTemplateFields()`

```php
getTemplateFields($template_id): \DocgenClient\Model\FieldSchema
```

Discover the merge-field schema of the current draft

Walks the Twig AST of the current `body`, extracts every variable referenced, infers type from usage context (loops → array, etc.), and returns a JSON-schema-ish shape.  Clients should call this to validate user input *before* submitting a render.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\TemplatesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string

try {
    $result = $apiInstance->getTemplateFields($template_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TemplatesApi->getTemplateFields: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |

### Return type

[**\DocgenClient\Model\FieldSchema**](../Model/FieldSchema.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listTemplates()`

```php
listTemplates($per_page, $cursor): \DocgenClient\Model\TemplateList
```

List templates in the current workspace

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\TemplatesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$per_page = 25; // int
$cursor = 'cursor_example'; // string

try {
    $result = $apiInstance->listTemplates($per_page, $cursor);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TemplatesApi->listTemplates: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **per_page** | **int**|  | [optional] [default to 25] |
| **cursor** | **string**|  | [optional] |

### Return type

[**\DocgenClient\Model\TemplateList**](../Model/TemplateList.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updateTemplate()`

```php
updateTemplate($template_id, $template_update): \DocgenClient\Model\Template
```

Update the current draft of a template

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\TemplatesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string
$template_update = new \DocgenClient\Model\TemplateUpdate(); // \DocgenClient\Model\TemplateUpdate

try {
    $result = $apiInstance->updateTemplate($template_id, $template_update);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling TemplatesApi->updateTemplate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |
| **template_update** | [**\DocgenClient\Model\TemplateUpdate**](../Model/TemplateUpdate.md)|  | |

### Return type

[**\DocgenClient\Model\Template**](../Model/Template.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
