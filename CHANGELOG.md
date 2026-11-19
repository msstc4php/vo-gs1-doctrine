# Changelog

## [Unreleased]

First release content (0.1.0).

### Added

- ORM and ODM types for every value object of `vo-gs1` (31 per branch): keys, element strings, Digital Link, EPC URIs,
  UDI, Basic UDI-DI, NDC, Russian and EU marking codes. Names are `gs1_<snake_name>` (regional prefixes `eu_`, `ru_`),
  the ORM column length is the longest canonical value of the class.
- `Orm\TypeRegistrar` and `Odm\TypeRegistrar` (`registerAll()`, `types()`).
- Package scaffold, CI and tool configuration from `vo-documents-doctrine`.
