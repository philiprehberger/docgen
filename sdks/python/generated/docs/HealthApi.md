# philiprehberger_docgen.HealthApi

All URIs are relative to *https://api.docgen.philiprehberger.com*

Method | HTTP request | Description
------------- | ------------- | -------------
[**get_health**](HealthApi.md#get_health) | **GET** /v1/healthz | Liveness and queue depth


# **get_health**
> Health get_health()

Liveness and queue depth

### Example


```python
import philiprehberger_docgen
from philiprehberger_docgen.models.health import Health
from philiprehberger_docgen.rest import ApiException
from pprint import pprint

# Defining the host is optional and defaults to https://api.docgen.philiprehberger.com
# See configuration.py for a list of all supported configuration parameters.
configuration = philiprehberger_docgen.Configuration(
    host = "https://api.docgen.philiprehberger.com"
)


# Enter a context with an instance of the API client
with philiprehberger_docgen.ApiClient(configuration) as api_client:
    # Create an instance of the API class
    api_instance = philiprehberger_docgen.HealthApi(api_client)

    try:
        # Liveness and queue depth
        api_response = api_instance.get_health()
        print("The response of HealthApi->get_health:\n")
        pprint(api_response)
    except Exception as e:
        print("Exception when calling HealthApi->get_health: %s\n" % e)
```



### Parameters

This endpoint does not need any parameter.

### Return type

[**Health**](Health.md)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

### HTTP response details

| Status code | Description | Response headers |
|-------------|-------------|------------------|
**200** | Healthy |  -  |

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

