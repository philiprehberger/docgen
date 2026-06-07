
# RenderCreate


## Properties

Name | Type
------------ | -------------
`templateId` | string
`version` | string
`formats` | Array&lt;string&gt;
`data` | { [key: string]: any; }
`signedUrlTtl` | number

## Example

```typescript
import type { RenderCreate } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "templateId": null,
  "version": null,
  "formats": null,
  "data": null,
  "signedUrlTtl": null,
} satisfies RenderCreate

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as RenderCreate
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


