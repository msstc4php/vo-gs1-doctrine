# Changelog

## [1.0.0] - unreleased

First stable release: from here on a breaking change needs 2.0.

**Backward compatibility (README, "Backward compatibility"):** 1.x covers the type names, type classes and `NAME`
constants, the ORM column lengths (each the vo-gs1 class's public `MAX_LENGTH`) and the VARCHAR/CLOB choice, and the
`TypeRegistrar` API. A minor may add types, never rename one or change a column. Exception message wording is not
covered.

### Changed

- `gs1_udi` is now `gs1_gs1_udi` and `gs1_basic_udi_di` is now `gs1_gs1_basic_udi_di`: the agency stays in the name,
  and the generic names are kept free for a later polymorphic type.
- `gs1_eu_fmd_pack_code` is `VARCHAR(512)` (was 514): vo-gs1 1.0 bounds the stored form at 512, trailer included.
- `gs1_ru_marking_identifier` is `VARCHAR(74)` (was 255), the real maximum of the stored form.
- Column lengths are checked against each value object's public `MAX_LENGTH` instead of private or internal
  constants.
- Requires `msstc4php/vo-gs1 ^1.0` and `msstc4php/vo-base-doctrine ^1.3`.
- README: `Gs1Prefix` is a pure enum (no `enumType`); `Gtin` rows compare on `toGtin14()`; polymorphic UDI storage
  will be a separate, additive type.

### Upgrading from 0.x

- Mappings: replace `type: 'gs1_udi'` with `'gs1_gs1_udi'` and `type: 'gs1_basic_udi_di'` with
  `'gs1_gs1_basic_udi_di'` (ORM and ODM); Symfony configuration that lists the names by hand likewise.
- Schema: a migration shrinks `gs1_eu_fmd_pack_code` columns to 512 and `gs1_ru_marking_identifier` columns to 74.
  No value vo-gs1 0.x could read back exceeds them (a 513/514-character FMD value never reloaded); check with
  `SELECT … WHERE LENGTH(col) > 512` before migrating.
- Stored `MarkingIdentifier` values that vo-gs1 1.0 refuses as ambiguous (a 0.x pack КИ kept as its bare 25
  characters) load as the pack, as 0.x read them; a later write stores them as `]d1` + 25.

## [0.1.0] - 2026-11-20

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
