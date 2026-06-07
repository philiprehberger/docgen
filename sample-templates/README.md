# Sample templates

Three reference templates that ship with the demo. Each one exercises a
different shape of the merge-field system:

| Template | Renders | Shape |
|---|---|---|
| `invoice.twig` | Northcliffe Legal LLP → Ridgeline Supply Co. invoice | Object root (sender, client, invoice, totals) + array root (lines) with object items |
| `offer-letter.twig` | Beacon Academy → fictional new hire | Nested object roots (company, candidate, position, signer) with no arrays |
| `certificate.twig` | Beacon Academy → fictional graduate | Deep nesting (institution.*, program.*, signers.primary.*, signers.secondary.*) |

These are the "live preview" set on the docs site (Phase 8). They double
as smoke-test fixtures in PHPUnit — each one must render to PDF without
errors.

The fictional brands match the plan's `## Open decisions` entry:

- **Northcliffe Legal LLP** — invoice issuer (legal services)
- **Ridgeline Supply Co.** — invoice recipient (commercial supplier)
- **Beacon Academy** — offer letter issuer + certificate issuer (private school)

All names are fictional. Don't seed real customer data here even by accident.
