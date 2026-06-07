# TemplateCreate


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **str** |  | 
**slug** | **str** | Optional. Auto-derived from &#x60;name&#x60; if omitted. | [optional] 
**description** | **str** |  | [optional] 
**engine** | **str** |  | [optional] [default to 'twig']
**body** | **str** | HTML + Twig source. Hard cap at &#x60;DOCGEN_TEMPLATE_BODY_MAX_BYTES&#x60; (default 256 KB).  | 

## Example

```python
from philiprehberger_docgen.models.template_create import TemplateCreate

# TODO update the JSON string below
json = "{}"
# create an instance of TemplateCreate from a JSON string
template_create_instance = TemplateCreate.from_json(json)
# print the JSON string representation of the object
print(TemplateCreate.to_json())

# convert the object into a dict
template_create_dict = template_create_instance.to_dict()
# create an instance of TemplateCreate from a dict
template_create_from_dict = TemplateCreate.from_dict(template_create_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


