# RenderError


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **str** |  | 
**message** | **str** |  | 
**details** | **Dict[str, object]** |  | [optional] 

## Example

```python
from philiprehberger_docgen.models.render_error import RenderError

# TODO update the JSON string below
json = "{}"
# create an instance of RenderError from a JSON string
render_error_instance = RenderError.from_json(json)
# print the JSON string representation of the object
print(RenderError.to_json())

# convert the object into a dict
render_error_dict = render_error_instance.to_dict()
# create an instance of RenderError from a dict
render_error_from_dict = RenderError.from_dict(render_error_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


