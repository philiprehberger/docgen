# Render

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **string** |  |
**status** | **string** |  |
**template_id** | **string** |  |
**template_version_label** | **string** |  | [optional]
**formats_requested** | **string[]** |  |
**outputs** | [**\DocgenClient\Model\RenderOutput[]**](RenderOutput.md) |  | [optional]
**duration_ms** | **int** |  | [optional]
**input_data_hash** | **string** |  | [optional]
**input_data_size_bytes** | **int** |  | [optional]
**error** | [**\DocgenClient\Model\RenderError**](RenderError.md) |  | [optional]
**poll_url** | **string** | Convenience field. Equivalent to &#x60;/v1/renders/{id}&#x60;. Returned on 202 to make polling unambiguous. | [optional]
**created_at** | **\DateTime** |  |
**completed_at** | **\DateTime** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
