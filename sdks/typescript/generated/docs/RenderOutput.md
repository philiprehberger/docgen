
# RenderOutput


## Properties

Name | Type
------------ | -------------
`format` | string
`url` | string
`expiresAt` | Date
`bytes` | number
`sha256` | string

## Example

```typescript
import type { RenderOutput } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "format": null,
  "url": null,
  "expiresAt": null,
  "bytes": null,
  "sha256": null,
} satisfies RenderOutput

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as RenderOutput
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


