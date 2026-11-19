# Conventions

- Type names `gs1_<snake_name>` (`gs1_gtin`, `gs1_element_string`, `gs1_ru_marking_code`); `TypeRegistrarTest` checks
  the pattern.
- Name rule: `gs1_` + snake_case class name; `Marking\EU` / `Marking\RU` classes add `eu_` / `ru_`; a leading `Gs1` is
  not repeated. Type class `<Prefix><Class>Type` in `Orm` and `Odm` with the same `NAME`.
- Column length = longest canonical (stored) value, not the longest input: `FmdPackCode` is 514 (512 + the 2-character
  IFA trailer added on read). Literals, not the library's internal constants, so a schema never changes silently.
- Adding a class of vo-gs1: type in both branches, both registrars, a `Samples::strings()` row, `CoversClass` in both
  `StringTypesTest`s, README row; the completeness test also pins the class count (31).
- ODM types cannot be built directly (see `vo-base-doctrine`): tests go through `Type::getType($name)` after
  `registerAll()`.
