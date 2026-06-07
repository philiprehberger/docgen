# DocgenClient\RendersApi

Async or sync render jobs producing HTML, PDF, or DOCX output.

All URIs are relative to https://api.docgen.philiprehberger.com, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**cancelRender()**](RendersApi.md#cancelRender) | **DELETE** /v1/renders/{renderId} | Cancel a queued or in-flight render |
| [**createRender()**](RendersApi.md#createRender) | **POST** /v1/renders | Submit a render job |
| [**downloadRenderOutput()**](RendersApi.md#downloadRenderOutput) | **GET** /v1/renders/{renderId}/outputs/{format} | Redirect to a signed download URL for one output format |
| [**getRender()**](RendersApi.md#getRender) | **GET** /v1/renders/{renderId} | Poll a render&#39;s status |
| [**listRenders()**](RendersApi.md#listRenders) | **GET** /v1/renders | List renders in the current workspace |


## `cancelRender()`

```php
cancelRender($render_id)
```

Cancel a queued or in-flight render

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\RendersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$render_id = 'render_id_example'; // string

try {
    $apiInstance->cancelRender($render_id);
} catch (Exception $e) {
    echo 'Exception when calling RendersApi->cancelRender: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **render_id** | **string**|  | |

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

## `createRender()`

```php
createRender($render_create, $sync, $idempotency_key): \DocgenClient\Model\Render
```

Submit a render job

Submits a render against a frozen template version.  Default is async — returns `202 Accepted` with a poll URL.  Add `?sync=true` to block up to `DOCGEN_SYNC_RENDER_TIMEOUT` seconds (default 15s) for a synchronous response. If the render isn't finished in time, the response falls back to the same `202` shape.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\RendersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$render_create = new \DocgenClient\Model\RenderCreate(); // \DocgenClient\Model\RenderCreate
$sync = false; // bool
$idempotency_key = 'idempotency_key_example'; // string | Optional idempotency key. Same key + same template version + same input data hash returns the cached render record. Same key + different inputs returns 409.

try {
    $result = $apiInstance->createRender($render_create, $sync, $idempotency_key);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RendersApi->createRender: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **render_create** | [**\DocgenClient\Model\RenderCreate**](../Model/RenderCreate.md)|  | |
| **sync** | **bool**|  | [optional] [default to false] |
| **idempotency_key** | **string**| Optional idempotency key. Same key + same template version + same input data hash returns the cached render record. Same key + different inputs returns 409. | [optional] |

### Return type

[**\DocgenClient\Model\Render**](../Model/Render.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `downloadRenderOutput()`

```php
downloadRenderOutput($render_id, $format)
```

Redirect to a signed download URL for one output format

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\RendersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$render_id = 'render_id_example'; // string
$format = 'format_example'; // string

try {
    $apiInstance->downloadRenderOutput($render_id, $format);
} catch (Exception $e) {
    echo 'Exception when calling RendersApi->downloadRenderOutput: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **render_id** | **string**|  | |
| **format** | **string**|  | |

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

## `getRender()`

```php
getRender($render_id): \DocgenClient\Model\Render
```

Poll a render's status

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\RendersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$render_id = 'render_id_example'; // string

try {
    $result = $apiInstance->getRender($render_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RendersApi->getRender: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **render_id** | **string**|  | |

### Return type

[**\DocgenClient\Model\Render**](../Model/Render.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listRenders()`

```php
listRenders($per_page, $cursor, $status): \DocgenClient\Model\RenderList
```

List renders in the current workspace

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (docgen_live_…) authorization: bearerAuth
$config = DocgenClient\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new DocgenClient\Api\RendersApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$per_page = 25; // int
$cursor = 'cursor_example'; // string
$status = 'status_example'; // string

try {
    $result = $apiInstance->listRenders($per_page, $cursor, $status);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling RendersApi->listRenders: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **per_page** | **int**|  | [optional] [default to 25] |
| **cursor** | **string**|  | [optional] |
| **status** | **string**|  | [optional] |

### Return type

[**\DocgenClient\Model\RenderList**](../Model/RenderList.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
