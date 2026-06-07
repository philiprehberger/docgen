
# Template


## Properties

Name | Type
------------ | -------------
`id` | string
`name` | string
`slug` | string
`description` | string
`engine` | string
`body` | string
`archivedAt` | Date
`latestVersionLabel` | string
`createdAt` | Date
`updatedAt` | Date

## Example

```typescript
import type { Template } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "id": null,
  "name": null,
  "slug": null,
  "description": null,
  "engine": null,
  "body": null,
  "archivedAt": null,
  "latestVersionLabel": null,
  "createdAt": null,
  "updatedAt": null,
} satisfies Template

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as Template
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


