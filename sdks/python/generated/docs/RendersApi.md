# philiprehberger_docgen.RendersApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**cancel_render**](RendersApi.md#cancel_render) | **DELETE** /v1/renders/{renderId} | Cancel a queued or in-flight render
[**create_render**](RendersApi.md#create_render) | **POST** /v1/renders | Submit a render job
[**download_render_output**](RendersApi.md#download_render_output) | **GET** /v1/renders/{renderId}/outputs/{format} | Redirect to a signed download URL for one output format
[**get_render**](RendersApi.md#get_render) | **GET** /v1/renders/{renderId} | Poll a render&#39;s status
[**list_renders**](RendersApi.md#list_renders) | **GET** /v1/renders | List renders in the current workspace


# **cancel_render**
> cancel_render(render_id)

Cancel a queued or in-flight render

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.rest import ApiException
from pprint import pprint

# Defining the host is optional and defaults to https://api.docgen.philiprehberger.com
# See configuration.py for a list of all supported configuration parameters.
configuration = philiprehberger_docgen.Configuration(
    host = "https://api.docgen.philiprehberger.com"
)

# The client must configure the authentication and authorization parameters
# in accordance with the API server security policy.
# Examples for each auth method are provided below, use the example that
# satisfies your auth use case.

# Configure Bearer authorization (docgen_live_…): bearerAuth
configuration = philiprehberger_docgen.Configuration(
    access_token = os.environ["BEARER_TOKEN"]
)

# Enter a context with an instance of the API client
with philiprehberger_docgen.ApiClient(configuration) as api_client:
    # Create an instance of the API class
    api_instance = philiprehberger_docgen.RendersApi(api_client)
    render_id = 'render_id_example' # str | 

    try:
        # Cancel a queued or in-flight render
        api_instance.cancel_render(render_id)
    except Exception as e:
        print("Exception when calling RendersApi->cancel_render: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **render_id** | **str**|  | 

### Return type

void (empty response body)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**204** | Cancelled |  -  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |
**409** | Render already in a terminal state |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **create_render**
> Render create_render(render_create, sync=sync, idempotency_key=idempotency_key)

Submit a render job

Submits a render against a frozen template version.

Default is async — returns `202 Accepted` with a poll URL.

Add `?sync=true` to block up to `DOCGEN_SYNC_RENDER_TIMEOUT` seconds
(default 15s) for a synchronous response. If the render isn't
finished in time, the response falls back to the same `202` shape.


### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.render import Render
from philiprehberger_docgen.models.render_create import RenderCreate
from philiprehberger_docgen.rest import ApiException
from pprint import pprint

# Defining the host is optional and defaults to https://api.docgen.philiprehberger.com
# See configuration.py for a list of all supported configuration parameters.
configuration = philiprehberger_docgen.Configuration(
    host = "https://api.docgen.philiprehberger.com"
)

# The client must configure the authentication and authorization parameters
# in accordance with the API server security policy.
# Examples for each auth method are provided below, use the example that
# satisfies your auth use case.

# Configure Bearer authorization (docgen_live_…): bearerAuth
configuration = philiprehberger_docgen.Configuration(
    access_token = os.environ["BEARER_TOKEN"]
)

# Enter a context with an instance of the API client
with philiprehberger_docgen.ApiClient(configuration) as api_client:
    # Create an instance of the API class
    api_instance = philiprehberger_docgen.RendersApi(api_client)
    render_create = philiprehberger_docgen.RenderCreate() # RenderCreate | 
    sync = False # bool |  (optional) (default to False)
    idempotency_key = 'idempotency_key_example' # str | Optional idempotency key. Same key + same template version + same input data hash returns the cached render record. Same key + different inputs returns 409.  (optional)

    try:
        # Submit a render job
        api_response = api_instance.create_render(render_create, sync=sync, idempotency_key=idempotency_key)
        print("The response of RendersApi->create_render:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling RendersApi->create_render: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **render_create** | [**RenderCreate**](RenderCreate.md)|  | 
 **sync** | **bool**|  | [optional] [default to False]
 **idempotency_key** | **str**| Optional idempotency key. Same key + same template version + same input data hash returns the cached render record. Same key + different inputs returns 409.  | [optional] 

### Return type

[**Render**](Render.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Sync render completed in time |  -  |
**202** | Async render queued (or sync timed out) |  -  |
**401** | Missing or invalid bearer token |  -  |
**409** | Idempotency key collision with different inputs |  -  |
**422** | Validation error |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **download_render_output**
> download_render_output(render_id, format)

Redirect to a signed download URL for one output format

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.rest import ApiException
from pprint import pprint

# Defining the host is optional and defaults to https://api.docgen.philiprehberger.com
# See configuration.py for a list of all supported configuration parameters.
configuration = philiprehberger_docgen.Configuration(
    host = "https://api.docgen.philiprehberger.com"
)

# The client must configure the authentication and authorization parameters
# in accordance with the API server security policy.
# Examples for each auth method are provided below, use the example that
# satisfies your auth use case.

# Configure Bearer authorization (docgen_live_…): bearerAuth
configuration = philiprehberger_docgen.Configuration(
    access_token = os.environ["BEARER_TOKEN"]
)

# Enter a context with an instance of the API client
with philiprehberger_docgen.ApiClient(configuration) as api_client:
    # Create an instance of the API class
    api_instance = philiprehberger_docgen.RendersApi(api_client)
    render_id = 'render_id_example' # str | 
    format = 'format_example' # str | 

    try:
        # Redirect to a signed download URL for one output format
        api_instance.download_render_output(render_id, format)
    except Exception as e:
        print("Exception when calling RendersApi->download_render_output: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **render_id** | **str**|  | 
 **format** | **str**|  | 

### Return type

void (empty response body)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**302** | Redirect to the signed URL |  * Location -  <br>  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **get_render**
> Render get_render(render_id)

Poll a render's status

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.render import Render
from philiprehberger_docgen.rest import ApiException
from pprint import pprint

# Defining the host is optional and defaults to https://api.docgen.philiprehberger.com
# See configuration.py for a list of all supported configuration parameters.
configuration = philiprehberger_docgen.Configuration(
    host = "https://api.docgen.philiprehberger.com"
)

# The client must configure the authentication and authorization parameters
# in accordance with the API server security policy.
# Examples for each auth method are provided below, use the example that
# satisfies your auth use case.

# Configure Bearer authorization (docgen_live_…): bearerAuth
configuration = philiprehberger_docgen.Configuration(
    access_token = os.environ["BEARER_TOKEN"]
)

# Enter a context with an instance of the API client
with philiprehberger_docgen.ApiClient(configuration) as api_client:
    # Create an instance of the API class
    api_instance = philiprehberger_docgen.RendersApi(api_client)
    render_id = 'render_id_example' # str | 

    try:
        # Poll a render's status
        api_response = api_instance.get_render(render_id)
        print("The response of RendersApi->get_render:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling RendersApi->get_render: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **render_id** | **str**|  | 

### Return type

[**Render**](Render.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | OK |  -  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **list_renders**
> RenderList list_renders(per_page=per_page, cursor=cursor, status=status)

List renders in the current workspace

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.render_list import RenderList
from philiprehberger_docgen.rest import ApiException
from pprint import pprint

# Defining the host is optional and defaults to https://api.docgen.philiprehberger.com
# See configuration.py for a list of all supported configuration parameters.
configuration = philiprehberger_docgen.Configuration(
    host = "https://api.docgen.philiprehberger.com"
)

# The client must configure the authentication and authorization parameters
# in accordance with the API server security policy.
# Examples for each auth method are provided below, use the example that
# satisfies your auth use case.

# Configure Bearer authorization (docgen_live_…): bearerAuth
configuration = philiprehberger_docgen.Configuration(
    access_token = os.environ["BEARER_TOKEN"]
)

# Enter a context with an instance of the API client
with philiprehberger_docgen.ApiClient(configuration) as api_client:
    # Create an instance of the API class
    api_instance = philiprehberger_docgen.RendersApi(api_client)
    per_page = 25 # int |  (optional) (default to 25)
    cursor = 'cursor_example' # str |  (optional)
    status = 'status_example' # str |  (optional)

    try:
        # List renders in the current workspace
        api_response = api_instance.list_renders(per_page=per_page, cursor=cursor, status=status)
        print("The response of RendersApi->list_renders:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling RendersApi->list_renders: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **per_page** | **int**|  | [optional] [default to 25]
 **cursor** | **str**|  | [optional] 
 **status** | **str**|  | [optional] 

### Return type

[**RenderList**](RenderList.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | A page of renders |  -  |
**401** | Missing or invalid bearer token |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

