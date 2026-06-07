# ModelField


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **str** |  | 
**type** | **str** |  | 
**required** | **bool** | Always true at this stage — every referenced variable is treated as required. Optional/default-value support is a v2 feature.  | [optional] [default to True]
**children** | [**List[ModelField]**](ModelField.md) | For &#x60;object&#x60; type, the inferred sub-fields. | [optional] 
**item_type** | **str** | For &#x60;array&#x60; type, the type of each item. | [optional] 

## Example

```python
from philiprehberger_docgen.models.model_field import ModelField

# TODO update the JSON string below
json = "{}"
# create an instance of ModelField from a JSON string
model_field_instance = ModelField.from_json(json)
# print the JSON string representation of the object
print(ModelField.to_json())

# convert the object into a dict
model_field_dict = model_field_instance.to_dict()
# create an instance of ModelField from a dict
model_field_from_dict = ModelField.from_dict(model_field_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


