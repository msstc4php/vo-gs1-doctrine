# Known issues

- CI fails until `vo-base` and `vo-base-doctrine` are tagged `v1.2.0` and the GitHub repositories exist.
- Infection is 100% covered MSI with the types in place (it was 66% with zero types).
- The ODM round trip needs MongoDB and skips itself without one; the ORM one runs on SQLite.
- `ElementString` canonical form is bracketed, so its stored value never contains GS; the GS/RS/EOT round trips are
  `MarkingCode` (GS) and `FmdPackCode` (IFA message: GS, RS, EOT).
- `ElementString`/`Gs1Udi` 14178 is the package's own input bound (twice the 7089 data cap), not a proven maximum of
  the canonical form, which is much shorter in practice (repeated AIs are refused).
