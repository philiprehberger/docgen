# philiprehberger_docgen.VersionsApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**create_template_version**](VersionsApi.md#create_template_version) | **POST** /v1/templates/{templateId}/versions | Freeze the current draft as a new version
[**get_template_version**](VersionsApi.md#get_template_version) | **GET** /v1/templates/{templateId}/versions/{versionLabel} | Fetch a frozen version
[**list_template_versions**](VersionsApi.md#list_template_versions) | **GET** /v1/templates/{templateId}/versions | List frozen versions of a template


# **create_template_version**
> TemplateVersion create_template_version(template_id)

Freeze the current draft as a new version

Snapshots the current `body` of the template into a new version
record. Version label is auto-assigned (`v1`, `v2`, …). Once a
version exists, its body is immutable forever — even if the
template's current draft changes.


### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template_version import TemplateVersion
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
    api_instance = philiprehberger_docgen.VersionsApi(api_client)
    template_id = 'template_id_example' # str | 

    try:
        # Freeze the current draft as a new version
        api_response = api_instance.create_template_version(template_id)
        print("The response of VersionsApi->create_template_version:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling VersionsApi->create_template_version: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 

### Return type

[**TemplateVersion**](TemplateVersion.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Frozen |  -  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **get_template_version**
> TemplateVersion get_template_version(template_id, version_label)

Fetch a frozen version

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template_version import TemplateVersion
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
    api_instance = philiprehberger_docgen.VersionsApi(api_client)
    template_id = 'template_id_example' # str | 
    version_label = 'version_label_example' # str | 

    try:
        # Fetch a frozen version
        api_response = api_instance.get_template_version(template_id, version_label)
        print("The response of VersionsApi->get_template_version:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling VersionsApi->get_template_version: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 
 **version_label** | **str**|  | 

### Return type

[**TemplateVersion**](TemplateVersion.md)

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

# **list_template_versions**
> TemplateVersionList list_template_versions(template_id)

List frozen versions of a template

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template_version_list import TemplateVersionList
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
    api_instance = philiprehberger_docgen.VersionsApi(api_client)
    template_id = 'template_id_example' # str | 

    try:
        # List frozen versions of a template
        api_response = api_instance.list_template_versions(template_id)
        print("The response of VersionsApi->list_template_versions:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling VersionsApi->list_template_versions: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 

### Return type

[**TemplateVersionList**](TemplateVersionList.md)

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

