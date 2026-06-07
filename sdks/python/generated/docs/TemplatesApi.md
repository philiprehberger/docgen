# philiprehberger_docgen.TemplatesApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**archive_template**](TemplatesApi.md#archive_template) | **DELETE** /v1/templates/{templateId} | Archive a template (soft delete)
[**create_template**](TemplatesApi.md#create_template) | **POST** /v1/templates | Create a template (draft)
[**get_template**](TemplatesApi.md#get_template) | **GET** /v1/templates/{templateId} | Fetch a template
[**get_template_fields**](TemplatesApi.md#get_template_fields) | **GET** /v1/templates/{templateId}/fields | Discover the merge-field schema of the current draft
[**list_templates**](TemplatesApi.md#list_templates) | **GET** /v1/templates | List templates in the current workspace
[**update_template**](TemplatesApi.md#update_template) | **PATCH** /v1/templates/{templateId} | Update the current draft of a template


# **archive_template**
> archive_template(template_id)

Archive a template (soft delete)

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
    api_instance = philiprehberger_docgen.TemplatesApi(api_client)
    template_id = 'template_id_example' # str | 

    try:
        # Archive a template (soft delete)
        api_instance.archive_template(template_id)
    except Exception as e:
        print("Exception when calling TemplatesApi->archive_template: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 

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
**204** | Archived |  -  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **create_template**
> Template create_template(template_create)

Create a template (draft)

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template import Template
from philiprehberger_docgen.models.template_create import TemplateCreate
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
    api_instance = philiprehberger_docgen.TemplatesApi(api_client)
    template_create = philiprehberger_docgen.TemplateCreate() # TemplateCreate | 

    try:
        # Create a template (draft)
        api_response = api_instance.create_template(template_create)
        print("The response of TemplatesApi->create_template:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling TemplatesApi->create_template: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_create** | [**TemplateCreate**](TemplateCreate.md)|  | 

### Return type

[**Template**](Template.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**201** | Created |  -  |
**401** | Missing or invalid bearer token |  -  |
**422** | Validation error |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **get_template**
> Template get_template(template_id)

Fetch a template

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template import Template
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
    api_instance = philiprehberger_docgen.TemplatesApi(api_client)
    template_id = 'template_id_example' # str | 

    try:
        # Fetch a template
        api_response = api_instance.get_template(template_id)
        print("The response of TemplatesApi->get_template:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling TemplatesApi->get_template: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 

### Return type

[**Template**](Template.md)

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

# **get_template_fields**
> FieldSchema get_template_fields(template_id)

Discover the merge-field schema of the current draft

Walks the Twig AST of the current `body`, extracts every variable
referenced, infers type from usage context (loops → array, etc.),
and returns a JSON-schema-ish shape.

Clients should call this to validate user input *before* submitting
a render.


### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.field_schema import FieldSchema
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
    api_instance = philiprehberger_docgen.TemplatesApi(api_client)
    template_id = 'template_id_example' # str | 

    try:
        # Discover the merge-field schema of the current draft
        api_response = api_instance.get_template_fields(template_id)
        print("The response of TemplatesApi->get_template_fields:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling TemplatesApi->get_template_fields: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 

### Return type

[**FieldSchema**](FieldSchema.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Field schema |  -  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |
**422** | Template body could not be parsed |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **list_templates**
> TemplateList list_templates(per_page=per_page, cursor=cursor)

List templates in the current workspace

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template_list import TemplateList
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
    api_instance = philiprehberger_docgen.TemplatesApi(api_client)
    per_page = 25 # int |  (optional) (default to 25)
    cursor = 'cursor_example' # str |  (optional)

    try:
        # List templates in the current workspace
        api_response = api_instance.list_templates(per_page=per_page, cursor=cursor)
        print("The response of TemplatesApi->list_templates:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling TemplatesApi->list_templates: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **per_page** | **int**|  | [optional] [default to 25]
 **cursor** | **str**|  | [optional] 

### Return type

[**TemplateList**](TemplateList.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | A page of templates |  -  |
**401** | Missing or invalid bearer token |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **update_template**
> Template update_template(template_id, template_update)

Update the current draft of a template

### Example

* Bearer (docgen_live_…) Authentication (bearerAuth):

```python
import philiprehberger_docgen
from philiprehberger_docgen.models.template import Template
from philiprehberger_docgen.models.template_update import TemplateUpdate
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
    api_instance = philiprehberger_docgen.TemplatesApi(api_client)
    template_id = 'template_id_example' # str | 
    template_update = philiprehberger_docgen.TemplateUpdate() # TemplateUpdate | 

    try:
        # Update the current draft of a template
        api_response = api_instance.update_template(template_id, template_update)
        print("The response of TemplatesApi->update_template:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling TemplatesApi->update_template: %s\n" % e)
```



### Parameters


Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **template_id** | **str**|  | 
 **template_update** | [**TemplateUpdate**](TemplateUpdate.md)|  | 

### Return type

[**Template**](Template.md)

### Authorization

[bearerAuth](../README.md#bearerAuth)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json, application/problem+json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | OK |  -  |
**401** | Missing or invalid bearer token |  -  |
**404** | Resource not found (or not visible to the current workspace) |  -  |
**422** | Validation error |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

