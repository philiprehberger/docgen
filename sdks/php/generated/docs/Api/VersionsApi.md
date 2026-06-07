# DocgenClient\VersionsApi

Frozen snapshots of templates. Renders pin a version.

All URIs are relative to https://api.docgen.philiprehberger.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**createTemplateVersion()**](VersionsApi.md#createTemplateVersion) | **POST** /v1/templates/{templateId}/versions | Freeze the current draft as a new version |
| [**getTemplateVersion()**](VersionsApi.md#getTemplateVersion) | **GET** /v1/templates/{templateId}/versions/{versionLabel} | Fetch a frozen version |
| [**listTemplateVersions()**](VersionsApi.md#listTemplateVersions) | **GET** /v1/templates/{templateId}/versions | List frozen versions of a template |


## `createTemplateVersion()`

```php
createTemplateVersion($template_id): \DocgenClient\Model\TemplateVersion
```

Freeze the current draft as a new version

Snapshots the current `body` of the template into a new version record. Version label is auto-assigned (`v1`, `v2`, …). Once a version exists, its body is immutable forever — even if the template's current draft changes.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\VersionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string

try {
    $result = $apiInstance->createTemplateVersion($template_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VersionsApi->createTemplateVersion: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |

### Return type

[**\DocgenClient\Model\TemplateVersion**](../Model/TemplateVersion.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getTemplateVersion()`

```php
getTemplateVersion($template_id, $version_label): \DocgenClient\Model\TemplateVersion
```

Fetch a frozen version

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\VersionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string
$version_label = 'version_label_example'; // string

try {
    $result = $apiInstance->getTemplateVersion($template_id, $version_label);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VersionsApi->getTemplateVersion: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |
| **version_label** | **string**|  | |

### Return type

[**\DocgenClient\Model\TemplateVersion**](../Model/TemplateVersion.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listTemplateVersions()`

```php
listTemplateVersions($template_id): \DocgenClient\Model\TemplateVersionList
```

List frozen versions of a template

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\VersionsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$template_id = 'template_id_example'; // string

try {
    $result = $apiInstance->listTemplateVersions($template_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling VersionsApi->listTemplateVersions: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **template_id** | **string**|  | |

### Return type

[**\DocgenClient\Model\TemplateVersionList**](../Model/TemplateVersionList.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
