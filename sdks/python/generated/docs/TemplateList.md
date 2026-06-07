# TemplateList


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**data** | [**List[Template]**](Template.md) |  | 
**next_cursor** | **str** |  | 

## Example

```python
from philiprehberger_docgen.models.template_list import TemplateList

# TODO update the JSON string below
json = "{}"
# create an instance of TemplateList from a JSON string
template_list_instance = TemplateList.from_json(json)
# print the JSON string representation of the object
print(TemplateList.to_json())

# convert the object into a dict
template_list_dict = template_list_instance.to_dict()
# create an instance of TemplateList from a dict
template_list_from_dict = TemplateList.from_dict(template_list_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


