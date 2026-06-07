# VersionsApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

| Method | HTTP request | Description |
|------------- | ------------- | -------------|
| [**createTemplateVersion**](VersionsApi.md#createtemplateversion) | **POST** /v1/templates/{templateId}/versions | Freeze the current draft as a new version |
| [**getTemplateVersion**](VersionsApi.md#gettemplateversion) | **GET** /v1/templates/{templateId}/versions/{versionLabel} | Fetch a frozen version |
| [**listTemplateVersions**](VersionsApi.md#listtemplateversions) | **GET** /v1/templates/{templateId}/versions | List frozen versions of a template |



## createTemplateVersion

> TemplateVersion createTemplateVersion(templateId)

Freeze the current draft as a new version

Snapshots the current &#x60;body&#x60; of the template into a new version record. Version label is auto-assigned (&#x60;v1&#x60;, &#x60;v2&#x60;, …). Once a version exists, its body is immutable forever — even if the template\&#39;s current draft changes. 

### Example

```ts
import {
  Configuration,
  VersionsApi,
} from '@philiprehberger/docgen';
import type { CreateTemplateVersionRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new VersionsApi(config);

  const body = {
    // string
    templateId: templateId_example,
  } satisfies CreateTemplateVersionRequest;

  try {
    const data = await api.createTemplateVersion(body);
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
| **templateId** | `string` |  | [Defaults to `undefined`] |

### Return type

[**TemplateVersion**](TemplateVersion.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **201** | Frozen |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## getTemplateVersion

> TemplateVersion getTemplateVersion(templateId, versionLabel)

Fetch a frozen version

### Example

```ts
import {
  Configuration,
  VersionsApi,
} from '@philiprehberger/docgen';
import type { GetTemplateVersionRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new VersionsApi(config);

  const body = {
    // string
    templateId: templateId_example,
    // string
    versionLabel: versionLabel_example,
  } satisfies GetTemplateVersionRequest;

  try {
    const data = await api.getTemplateVersion(body);
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
| **templateId** | `string` |  | [Defaults to `undefined`] |
| **versionLabel** | `string` |  | [Defaults to `undefined`] |

### Return type

[**TemplateVersion**](TemplateVersion.md)

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


## listTemplateVersions

> TemplateVersionList listTemplateVersions(templateId)

List frozen versions of a template

### Example

```ts
import {
  Configuration,
  VersionsApi,
} from '@philiprehberger/docgen';
import type { ListTemplateVersionsRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new VersionsApi(config);

  const body = {
    // string
    templateId: templateId_example,
  } satisfies ListTemplateVersionsRequest;

  try {
    const data = await api.listTemplateVersions(body);
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
| **templateId** | `string` |  | [Defaults to `undefined`] |

### Return type

[**TemplateVersionList**](TemplateVersionList.md)

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

