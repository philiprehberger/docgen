# Render


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **str** |  | 
**status** | **str** |  | 
**template_id** | **str** |  | 
**template_version_label** | **str** |  | [optional] 
**formats_requested** | **List[str]** |  | 
**outputs** | [**List[RenderOutput]**](RenderOutput.md) |  | [optional] 
**duration_ms** | **int** |  | [optional] 
**input_data_hash** | **str** |  | [optional] 
**input_data_size_bytes** | **int** |  | [optional] 
**error** | [**RenderError**](RenderError.md) |  | [optional] 
**poll_url** | **str** | Convenience field. Equivalent to &#x60;/v1/renders/{id}&#x60;. Returned on 202 to make polling unambiguous.  | [optional] 
**created_at** | **datetime** |  | 
**completed_at** | **datetime** |  | [optional] 

## Example

```python
from philiprehberger_docgen.models.render import Render

# TODO update the JSON string below
json = "{}"
# create an instance of Render from a JSON string
render_instance = Render.from_json(json)
# print the JSON string representation of the object
print(Render.to_json())

# convert the object into a dict
render_dict = render_instance.to_dict()
# create an instance of Render from a dict
render_from_dict = Render.from_dict(render_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


