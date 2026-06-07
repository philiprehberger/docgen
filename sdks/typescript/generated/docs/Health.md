
# Health


## Properties

Name | Type
------------ | -------------
`healthy` | boolean
`version` | string
`queueDepth` | number
`twigVersion` | string
`phpVersion` | string

## Example

```typescript
import type { Health } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "healthy": true,
  "version": 0.5.0,
  "queueDepth": 0,
  "twigVersion": null,
  "phpVersion": null,
} satisfies Health

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as Health
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


