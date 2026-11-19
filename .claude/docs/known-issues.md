# Known issues

- CI fails until `vo-base` and `vo-base-doctrine` are tagged `v1.2.0` and the GitHub repositories exist.
- Infection is 100% covered MSI with the types in place (it was 66% with zero types).
- The ODM round trip needs MongoDB and skips itself without one; the ORM one runs on SQLite.
- `ElementString` canonical form is bracketed, so its stored value never contains GS; the GS/RS/EOT round trips are
  `MarkingCode` (GS) and `FmdPackCode` (IFA message: GS, RS, EOT).
- `ElementString`/`Gs1Udi` 14178 is the package's own bound (input and canonical form, ruling CR-017). With
  `DigitalLink` they are the types above 4000 characters and map to `AbstractTextVoType` (CLOB; ruling CR-004); the
  length contract test covers only VARCHAR types and asserts every VARCHAR bound is at most 4000.
- Writing a VO of another class is refused by vo-base-doctrine's `AbstractStringVoType` (S-1); the gs1 string-type
  tests pin the message `Expected <own class>, got <other class>.`
