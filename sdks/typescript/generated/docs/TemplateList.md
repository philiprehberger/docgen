
# TemplateList


## Properties

Name | Type
------------ | -------------
`data` | [Array&lt;Template&gt;](Template.md)
`nextCursor` | string

## Example

```typescript
import type { TemplateList } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "data": null,
  "nextCursor": null,
} satisfies TemplateList

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as TemplateList
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


