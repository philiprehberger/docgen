# RenderCreate

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**template_id** | **string** |  |
**version** | **string** | Optional. Defaults to the latest frozen version. If the template has no frozen versions, render returns 422 — drafts cannot be rendered. | [optional]
**formats** | **string[]** |  |
**data** | **array<string,mixed>** | Merge-field values. Validated against the version&#39;s &#x60;fields_schema&#x60; on submit; missing fields return 422. |
**signed_url_ttl** | **int** | Per-request override for the signed-URL TTL in seconds. Capped at the workspace &#x60;default_signed_url_ttl_seconds&#x60;&#39;s &#x60;DOCGEN_MAX_SIGNED_URL_TTL&#x60; (default 86400). | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
