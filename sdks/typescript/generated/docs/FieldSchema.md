
# FieldSchema

JSON-schema-ish description of every variable referenced in the template body. Types are inferred from usage — `{% for %}` makes a field an array, dotted access makes it an object, default → scalar. 

## Properties

Name | Type
------------ | -------------
`fields` | [Array&lt;Field&gt;](Field.md)

## Example

```typescript
import type { FieldSchema } from '@philiprehberger/docgen'

// TODO: Update the object below with actual values
const example = {
  "fields": null,
} satisfies FieldSchema

console.log(example)

// Convert the instance to a JSON string
const exampleJSON: string = JSON.stringify(example)
console.log(exampleJSON)

// Parse the JSON string back to an object
const exampleParsed = JSON.parse(exampleJSON) as FieldSchema
console.log(exampleParsed)
```

[[Back to top]](#) [[Back to API list]](../README.md#api-endpoints) [[Back to Model list]](../README.md#models) [[Back to README]](../README.md)


