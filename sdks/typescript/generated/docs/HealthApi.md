# HealthApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

| Method | HTTP request | Description |
|------------- | ------------- | -------------|
| [**getHealth**](HealthApi.md#gethealth) | **GET** /v1/healthz | Liveness and queue depth |



## getHealth

> Health getHealth()

Liveness and queue depth

### Example

```ts
import {
  Configuration,
  HealthApi,
} from '@philiprehberger/docgen';
import type { GetHealthRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const api = new HealthApi();

  try {
    const data = await api.getHealth();
    console.log(data);
  } catch (error) {
    console.error(error);
  }
}

// Run the test
example().catch(console.error);
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**Health**](Health.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | Healthy |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)

