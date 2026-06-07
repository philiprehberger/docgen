# FieldSchema

JSON-schema-ish description of every variable referenced in the template body. Types are inferred from usage — `{% for %}` makes a field an array, dotted access makes it an object, default → scalar. 

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**fields** | [**List[ModelField]**](ModelField.md) |  | 

## Example

```python
from philiprehberger_docgen.models.field_schema import FieldSchema

# TODO update the JSON string below
json = "{}"
# create an instance of FieldSchema from a JSON string
field_schema_instance = FieldSchema.from_json(json)
# print the JSON string representation of the object
print(FieldSchema.to_json())

# convert the object into a dict
field_schema_dict = field_schema_instance.to_dict()
# create an instance of FieldSchema from a dict
field_schema_from_dict = FieldSchema.from_dict(field_schema_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


