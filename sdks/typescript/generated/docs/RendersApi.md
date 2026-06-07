# RendersApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

| Method | HTTP request | Description |
|------------- | ------------- | -------------|
| [**cancelRender**](RendersApi.md#cancelrender) | **DELETE** /v1/renders/{renderId} | Cancel a queued or in-flight render |
| [**createRender**](RendersApi.md#createrender) | **POST** /v1/renders | Submit a render job |
| [**downloadRenderOutput**](RendersApi.md#downloadrenderoutput) | **GET** /v1/renders/{renderId}/outputs/{format} | Redirect to a signed download URL for one output format |
| [**getRender**](RendersApi.md#getrender) | **GET** /v1/renders/{renderId} | Poll a render\&#39;s status |
| [**listRenders**](RendersApi.md#listrenders) | **GET** /v1/renders | List renders in the current workspace |



## cancelRender

> cancelRender(renderId)

Cancel a queued or in-flight render

### Example

```ts
import {
  Configuration,
  RendersApi,
} from '@philiprehberger/docgen';
import type { CancelRenderRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new RendersApi(config);

  const body = {
    // string
    renderId: renderId_example,
  } satisfies CancelRenderRequest;

  try {
    const data = await api.cancelRender(body);
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Run the test
example().catch(console.error);
```

### Parameters


| Name | Type | Description  | Notes |
|------------- | ------------- | ------------- | -------------|
| **renderId** | `string` |  | [Defaults to `undefined`] |

### Return type

`void` (Empty response body)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **204** | Cancelled |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |
| **409** | Render already in a terminal state |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## createRender

> Render createRender(renderCreate, sync, idempotencyKey)

Submit a render job

Submits a render against a frozen template version.  Default is async — returns &#x60;202 Accepted&#x60; with a poll URL.  Add &#x60;?sync&#x3D;true&#x60; to block up to &#x60;DOCGEN_SYNC_RENDER_TIMEOUT&#x60; seconds (default 15s) for a synchronous response. If the render isn\&#39;t finished in time, the response falls back to the same &#x60;202&#x60; shape. 

### Example

```ts
import {
  Configuration,
  RendersApi,
} from '@philiprehberger/docgen';
import type { CreateRenderRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new RendersApi(config);

  const body = {
    // RenderCreate
    renderCreate: ...,
    // boolean (optional)
    sync: true,
    // string | Optional idempotency key. Same key + same template version + same input data hash returns the cached render record. Same key + different inputs returns 409.  (optional)
    idempotencyKey: idempotencyKey_example,
  } satisfies CreateRenderRequest;

  try {
    const data = await api.createRender(body);
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Run the test
example().catch(console.error);
```

### Parameters


| Name | Type | Description  | Notes |
|------------- | ------------- | ------------- | -------------|
| **renderCreate** | [RenderCreate](RenderCreate.md) |  | |
| **sync** | `boolean` |  | [Optional] [Defaults to `false`] |
| **idempotencyKey** | `string` | Optional idempotency key. Same key + same template version + same input data hash returns the cached render record. Same key + different inputs returns 409.  | [Optional] [Defaults to `undefined`] |

### Return type

[**Render**](Render.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | Sync render completed in time |  -  |
| **202** | Async render queued (or sync timed out) |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **409** | Idempotency key collision with different inputs |  -  |
| **422** | Validation error |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## downloadRenderOutput

> downloadRenderOutput(renderId, format)

Redirect to a signed download URL for one output format

### Example

```ts
import {
  Configuration,
  RendersApi,
} from '@philiprehberger/docgen';
import type { DownloadRenderOutputRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new RendersApi(config);

  const body = {
    // string
    renderId: renderId_example,
    // 'html' | 'pdf' | 'docx'
    format: format_example,
  } satisfies DownloadRenderOutputRequest;

  try {
    const data = await api.downloadRenderOutput(body);
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Run the test
example().catch(console.error);
```

### Parameters


| Name | Type | Description  | Notes |
|------------- | ------------- | ------------- | -------------|
| **renderId** | `string` |  | [Defaults to `undefined`] |
| **format** | `html`, `pdf`, `docx` |  | [Defaults to `undefined`] [Enum: html, pdf, docx] |

### Return type

`void` (Empty response body)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **302** | Redirect to the signed URL |  * Location -  <br>  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## getRender

> Render getRender(renderId)

Poll a render\&#39;s status

### Example

```ts
import {
  Configuration,
  RendersApi,
} from '@philiprehberger/docgen';
import type { GetRenderRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new RendersApi(config);

  const body = {
    // string
    renderId: renderId_example,
  } satisfies GetRenderRequest;

  try {
    const data = await api.getRender(body);
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Run the test
example().catch(console.error);
```

### Parameters


| Name | Type | Description  | Notes |
|------------- | ------------- | ------------- | -------------|
| **renderId** | `string` |  | [Defaults to `undefined`] |

### Return type

[**Render**](Render.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | OK |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## listRenders

> RenderList listRenders(perPage, cursor, status)

List renders in the current workspace

### Example

```ts
import {
  Configuration,
  RendersApi,
} from '@philiprehberger/docgen';
import type { ListRendersRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new RendersApi(config);

  const body = {
    // number (optional)
    perPage: 56,
    // string (optional)
    cursor: cursor_example,
    // 'queued' | 'rendering' | 'succeeded' | 'failed' | 'cancelled' (optional)
    status: status_example,
  } satisfies ListRendersRequest;

  try {
    const data = await api.listRenders(body);
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Run the test
example().catch(console.error);
```

### Parameters


| Name | Type | Description  | Notes |
|------------- | ------------- | ------------- | -------------|
| **perPage** | `number` |  | [Optional] [Defaults to `25`] |
| **cursor** | `string` |  | [Optional] [Defaults to `undefined`] |
| **status** | `queued`, `rendering`, `succeeded`, `failed`, `cancelled` |  | [Optional] [Defaults to `undefined`] [Enum: queued, rendering, succeeded, failed, cancelled] |

### Return type

[**RenderList**](RenderList.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | A page of renders |  -  |
| **401** | Missing or invalid bearer token |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)

