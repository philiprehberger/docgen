# RenderList


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**data** | [**List[Render]**](Render.md) |  | 
**next_cursor** | **str** |  | 

## Example

```python
from philiprehberger_docgen.models.render_list import RenderList

# TODO update the JSON string below
json = "{}"
# create an instance of RenderList from a JSON string
render_list_instance = RenderList.from_json(json)
# print the JSON string representation of the object
print(RenderList.to_json())

# convert the object into a dict
render_list_dict = render_list_instance.to_dict()
# create an instance of RenderList from a dict
render_list_from_dict = RenderList.from_dict(render_list_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


