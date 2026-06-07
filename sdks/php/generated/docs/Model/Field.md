# Field

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**name** | **string** |  |
**type** | **string** |  |
**required** | **bool** | Always true at this stage — every referenced variable is treated as required. Optional/default-value support is a v2 feature. | [optional] [default to true]
**children** | [**\DocgenClient\Model\Field[]**](Field.md) | For &#x60;object&#x60; type, the inferred sub-fields. | [optional]
**item_type** | **string** | For &#x60;array&#x60; type, the type of each item. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
