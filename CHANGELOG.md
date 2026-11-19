# Changelog

## [Unreleased]

First release content (0.1.0).

### Added

- ORM and ODM types for every value object of `vo-gs1` (31 per branch): keys, element strings, Digital Link, EPC URIs,
  UDI, Basic UDI-DI, NDC, Russian and EU marking codes. Names are `gs1_<snake_name>` (regional prefixes `eu_`, `ru_`),
  the ORM column length is the longest canonical value of the class.
- `Orm\TypeRegistrar` and `Odm\TypeRegistrar` (`registerAll()`, `types()`).
- Package scaffold, CI and tool configuration from `vo-documents-doctrine`.

### Changed before the first release (final review)

- `gs1_digital_link`, `gs1_element_string` and `gs1_udi` (ORM) are CLOB/TEXT columns: their 14178-character bound
  exceeds the VARCHAR limit of SQL Server and Oracle (4000) and MySQL's row size. `gs1_isbt_udi` is `VARCHAR(3116)`.
- README: binary collation for the case-sensitive types; writing another class's value object is refused
  (vo-base-doctrine 1.2).
