# RenderCreate


## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**template_id** | **str** |  | 
**version** | **str** | Optional. Defaults to the latest frozen version. If the template has no frozen versions, render returns 422 — drafts cannot be rendered.  | [optional] 
**formats** | **List[str]** |  | 
**data** | **Dict[str, object]** | Merge-field values. Validated against the version&#39;s &#x60;fields_schema&#x60; on submit; missing fields return 422.  | 
**signed_url_ttl** | **int** | Per-request override for the signed-URL TTL in seconds. Capped at the workspace &#x60;default_signed_url_ttl_seconds&#x60;&#39;s &#x60;DOCGEN_MAX_SIGNED_URL_TTL&#x60; (default 86400).  | [optional] 

## Example

```python
from philiprehberger_docgen.models.render_create import RenderCreate

# TODO update the JSON string below
json = "{}"
# create an instance of RenderCreate from a JSON string
render_create_instance = RenderCreate.from_json(json)
# print the JSON string representation of the object
print(RenderCreate.to_json())

# convert the object into a dict
render_create_dict = render_create_instance.to_dict()
# create an instance of RenderCreate from a dict
render_create_from_dict = RenderCreate.from_dict(render_create_dict)
```
[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


