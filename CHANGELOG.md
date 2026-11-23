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
- `gs1_eu_fmd_pack_code` stays `VARCHAR(514)`: vo-gs1 1.0 sets `FmdPackCode::MAX_LENGTH` to 514, trailer included.
- `gs1_ru_marking_identifier` is `VARCHAR(74)` (was 255), the real maximum of the stored form.
- `gs1_isbt_udi` is `VARCHAR(3123)` (was 3116): vo-gs1 1.0 keeps the compound message header, so the stored form of
  3116 characters of data structures can carry 7 more.
- Column lengths are checked against each value object's public `MAX_LENGTH` instead of private or internal
  constants.
- Requires `msstc4php/vo-gs1 ^1.0` and `msstc4php/vo-base-doctrine ^1.3`.
- README: `Gs1Prefix` is a pure enum (no `enumType`); `Gtin` rows compare on `toGtin14()`; polymorphic UDI storage
  will be a separate, additive type.

### Upgrading from 0.x

- Mappings: replace `type: 'gs1_udi'` with `'gs1_gs1_udi'` and `type: 'gs1_basic_udi_di'` with
  `'gs1_gs1_basic_udi_di'` (ORM and ODM); Symfony configuration that lists the names by hand likewise.
- The type renames need no schema or data migration: DBAL 4 stores no type comment and ODM stores no type name, so
  only the mappings and a hand-written Symfony `doctrine.dbal.types` list change.
- Schema: a migration shrinks `gs1_ru_marking_identifier` columns to 74 and widens `gs1_isbt_udi` columns to 3123.
  No value vo-gs1 0.x could store exceeds 74 characters; check with `SELECT … WHERE CHAR_LENGTH(col) > 74` (the
  character length; `LENGTH` counts bytes on MySQL) before migrating. `gs1_eu_fmd_pack_code` does not change.
- Changed stored forms (see the vo-gs1 1.0 "Upgrading from 0.x" block): vo-gs1 1.0 writes an ambiguous tobacco pack
  КИ (25 characters that also read as `01` + GTIN + `21` + 7) as `]d1` + 25, where 0.x wrote the bare 25, and an
  `IsbtUdi` read from a compound message with its `=+aabbb` header, where 0.x wrote the data structures alone. Both
  kinds of 0.x row still load (the КИ as the pack), but Doctrine writes a row only when its field changes, so they
  stay in the 0.x form: `WHERE col = :value` with a 1.0 value misses them and a unique index lets a second row of the
  same value in. Before relying on value lookups or unique indexes, run a one-off data migration, or query both forms.
  For `gs1_ru_marking_identifier`, detect the rows in PHP, not SQL (only vo-gs1 decides the ambiguity): select the
  rows with `CHAR_LENGTH(col) = 25`, and for each value `MarkingIdentifier::isValid($value)` refuses while
  `MarkingIdentifier::fromTobaccoConsumerPack($value)` accepts, run
  `UPDATE … SET col = CONCAT(']d1', col) WHERE id = :id`. An `IsbtUdi` row needs nothing unless the application
  stores compound-message input and looks it up by value; then rewrite those rows from the input or compare with
  `equals()`.
- Downgrading to 0.x after 1.0 has written values is not possible for these columns without reverting the data:
  vo-gs1 0.x refuses a `]d1` identifier and an `IsbtUdi` value above 3116 characters.

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
