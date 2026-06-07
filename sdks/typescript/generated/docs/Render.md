
# Render


## Properties

Name | Type
------------ | -------------
`id` | string
`status` | string
`templateId` | string
`templateVersionLabel` | string
`formatsRequested` | Array&lt;string&gt;
`outputs` | [Array&lt;RenderOutput&gt;](RenderOutput.md)
`durationMs` | number
`inputDataHash` | string
`inputDataSizeBytes` | number
`error` | [RenderError](RenderError.md)
`pollUrl` | string
`createdAt` | Date
`completedAt` | Date

## Example

```typescript
import type { Render } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "id": null,
  "status": null,
  "templateId": null,
  "templateVersionLabel": null,
  "formatsRequested": null,
  "outputs": null,
  "durationMs": null,
  "inputDataHash": null,
  "inputDataSizeBytes": null,
  "error": null,
  "pollUrl": null,
  "createdAt": null,
  "completedAt": null,
} satisfies Render

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as Render
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


