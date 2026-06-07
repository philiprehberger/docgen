# TemplatesApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

| Method | HTTP request | Description |
|------------- | ------------- | -------------|
| [**archiveTemplate**](TemplatesApi.md#archivetemplate) | **DELETE** /v1/templates/{templateId} | Archive a template (soft delete) |
| [**createTemplate**](TemplatesApi.md#createtemplate) | **POST** /v1/templates | Create a template (draft) |
| [**getTemplate**](TemplatesApi.md#gettemplate) | **GET** /v1/templates/{templateId} | Fetch a template |
| [**getTemplateFields**](TemplatesApi.md#gettemplatefields) | **GET** /v1/templates/{templateId}/fields | Discover the merge-field schema of the current draft |
| [**listTemplates**](TemplatesApi.md#listtemplates) | **GET** /v1/templates | List templates in the current workspace |
| [**updateTemplate**](TemplatesApi.md#updatetemplate) | **PATCH** /v1/templates/{templateId} | Update the current draft of a template |



## archiveTemplate

> archiveTemplate(templateId)

Archive a template (soft delete)

### Example

```ts
import {
  Configuration,
  TemplatesApi,
} from '@philiprehberger/docgen';
import type { ArchiveTemplateRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new TemplatesApi(config);

  const body = {
    // string
    templateId: templateId_example,
  } satisfies ArchiveTemplateRequest;

  try {
    const data = await api.archiveTemplate(body);
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

`void` (Empty response body)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **204** | Archived |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## createTemplate

> Template createTemplate(templateCreate)

Create a template (draft)

### Example

```ts
import {
  Configuration,
  TemplatesApi,
} from '@philiprehberger/docgen';
import type { CreateTemplateRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new TemplatesApi(config);

  const body = {
    // TemplateCreate
    templateCreate: ...,
  } satisfies CreateTemplateRequest;

  try {
    const data = await api.createTemplate(body);
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
| **templateCreate** | [TemplateCreate](TemplateCreate.md) |  | |

### Return type

[**Template**](Template.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **201** | Created |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **422** | Validation error |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## getTemplate

> Template getTemplate(templateId)

Fetch a template

### Example

```ts
import {
  Configuration,
  TemplatesApi,
} from '@philiprehberger/docgen';
import type { GetTemplateRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new TemplatesApi(config);

  const body = {
    // string
    templateId: templateId_example,
  } satisfies GetTemplateRequest;

  try {
    const data = await api.getTemplate(body);
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

[**Template**](Template.md)

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


## getTemplateFields

> FieldSchema getTemplateFields(templateId)

Discover the merge-field schema of the current draft

Walks the Twig AST of the current &#x60;body&#x60;, extracts every variable referenced, infers type from usage context (loops → array, etc.), and returns a JSON-schema-ish shape.  Clients should call this to validate user input *before* submitting a render. 

### Example

```ts
import {
  Configuration,
  TemplatesApi,
} from '@philiprehberger/docgen';
import type { GetTemplateFieldsRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new TemplatesApi(config);

  const body = {
    // string
    templateId: templateId_example,
  } satisfies GetTemplateFieldsRequest;

  try {
    const data = await api.getTemplateFields(body);
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

[**FieldSchema**](FieldSchema.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | Field schema |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |
| **422** | Template body could not be parsed |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## listTemplates

> TemplateList listTemplates(perPage, cursor)

List templates in the current workspace

### Example

```ts
import {
  Configuration,
  TemplatesApi,
} from '@philiprehberger/docgen';
import type { ListTemplatesRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new TemplatesApi(config);

  const body = {
    // number (optional)
    perPage: 56,
    // string (optional)
    cursor: cursor_example,
  } satisfies ListTemplatesRequest;

  try {
    const data = await api.listTemplates(body);
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

### Return type

[**TemplateList**](TemplateList.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | A page of templates |  -  |
| **401** | Missing or invalid bearer token |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


## updateTemplate

> Template updateTemplate(templateId, templateUpdate)

Update the current draft of a template

### Example

```ts
import {
  Configuration,
  TemplatesApi,
} from '@philiprehberger/docgen';
import type { UpdateTemplateRequest } from '@philiprehberger/docgen';

async function example() {
  console.log("🚀 Testing @philiprehberger/docgen SDK...");
  const config = new Configuration({ 
    // Configure HTTP bearer authorization: bearerAuth
    accessToken: "YOUR BEARER TOKEN",
  });
  const api = new TemplatesApi(config);

  const body = {
    // string
    templateId: templateId_example,
    // TemplateUpdate
    templateUpdate: ...,
  } satisfies UpdateTemplateRequest;

  try {
    const data = await api.updateTemplate(body);
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
| **templateUpdate** | [TemplateUpdate](TemplateUpdate.md) |  | |

### Return type

[**Template**](Template.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: `application/json`
- **Accept**: `application/json`, `application/problem+json`


### HTTP response details
| Status code | Description | Response headers |
|-------------|-------------|------------------|
| **200** | OK |  -  |
| **401** | Missing or invalid bearer token |  -  |
| **404** | Resource not found (or not visible to the current workspace) |  -  |
| **422** | Validation error |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)

