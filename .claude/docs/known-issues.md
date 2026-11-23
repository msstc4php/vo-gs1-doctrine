# Known issues

- `composer.json` requires `vo-gs1 ^1.0`, which resolves only once vo-gs1 v1.0.0 is tagged; CI uses
  `composer-ci.json` (`dev-main as 1.0.0`) until then.
- `Gtin` (and `IsbtUdi`, `DigitalLink`) compare by value (`equals()`), not by the stored string: equal objects can
  be two rows (README, "Equality is not string equality"; ruling S7).
- Infection is 100% covered MSI with the types in place (it was 66% with zero types).
- The ODM round trip needs MongoDB and skips itself without one; the ORM one runs on SQLite.
- `ElementString` canonical form is bracketed, so its stored value never contains GS; the GS/RS/EOT round trips are
  `MarkingCode` (GS) and `FmdPackCode` (IFA message: GS, RS, EOT).
- `ElementString`/`Gs1Udi` 14178 is the package's own bound (input and canonical form, ruling CR-017). With
  `DigitalLink` they are the types above 4000 characters and map to `AbstractTextVoType` (CLOB; ruling CR-004).
  `ColumnLengthsFollowTheLibraryBoundsTest` checks every type, CLOB included: a VARCHAR length equals `MAX_LENGTH`
  and is at most 4000, a CLOB type is one whose `MAX_LENGTH` exceeds 4000.
- Writing a VO of another class is refused by vo-base-doctrine's `AbstractStringVoType` (S-1); the gs1 string-type
  tests pin the message `Expected <own class>, got <other class>.`
- `RuMarkingIdentifierType` (ORM and ODM) overrides `convertToPHPValue()`: a bare 25-character value that vo-gs1 1.0
  refuses as ambiguous can only be a 0.x row (1.0 writes that pack as `]d1` + 25), so it loads through
  `MarkingIdentifier::fromTobaccoConsumerPack()`, as 0.x read it (G15). The check is not a `catch` around
  `parent::convertToPHPValue()`, because PHPStan reports that catch as never thrown. `OrmRoundTripTest` /
  `OdmRoundTripTest` pin it on the hydration path (raw insert of the bare 25, then `findOneBy()`). Such rows keep
  their 0.x form until rewritten (Doctrine writes only changed fields): value lookups and unique indexes miss them;
  CHANGELOG "Upgrading from 0.x" gives the one-off migration (detect in PHP, `CONCAT(']d1', col)`).
- Column bounds of vo-gs1 1.0 review fix round: `gs1_eu_fmd_pack_code` 514 (ruling CR-004 overrode the 512 of B3,
  so no FMD schema change from 0.x), `gs1_isbt_udi` 3123 (CR-011: data structures 3116 + `=+aabbb` header).
