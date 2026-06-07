# RenderOutput


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**format** | **str** |  | 
**url** | **str** | Signed download URL. Expires per &#x60;expires_at&#x60;. | 
**expires_at** | **datetime** |  | 
**bytes** | **int** |  | 
**sha256** | **str** |  | 

## Example

```python
from philiprehberger_docgen.models.render_output import RenderOutput

# TODO update the JSON string below
json = "{}"
# create an instance of RenderOutput from a JSON string
render_output_instance = RenderOutput.from_json(json)
# print the JSON string representation of the object
print(RenderOutput.to_json())

# convert the object into a dict
render_output_dict = render_output_instance.to_dict()
# create an instance of RenderOutput from a dict
render_output_from_dict = RenderOutput.from_dict(render_output_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


