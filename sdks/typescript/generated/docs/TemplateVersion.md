
# TemplateVersion


## Properties

Name | Type
------------ | -------------
`templateId` | string
`label` | string
`body` | string
`fieldsSchema` | [FieldSchema](FieldSchema.md)
`createdAt` | Date

## Example

```typescript
import type { TemplateVersion } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "templateId": null,
  "label": v3,
  "body": null,
  "fieldsSchema": null,
  "createdAt": null,
} satisfies TemplateVersion

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as TemplateVersion
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


