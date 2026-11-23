# Conventions

- Type names `gs1_<snake_name>` (`gs1_gtin`, `gs1_element_string`, `gs1_ru_marking_code`); `TypeRegistrarTest` checks
  the pattern.
- Name rule: `gs1_` + snake_case class name; `Marking\EU` / `Marking\RU` classes add `eu_` / `ru_`. The agency of a
  UDI class stays in the name, `Gs1` too (`gs1_gs1_udi`, `gs1_gs1_basic_udi_di`; ruling S5, 1.0): the generic
  `gs1_udi` / `gs1_basic_udi_di` are reserved for a future polymorphic type. Type class `<Prefix><Class>Type` in `Orm` and `Odm` with the same `NAME`.
- Column length = the value object's public `MAX_LENGTH` (longest stored value, frozen by vo-gs1 1.x): `FmdPackCode`
  512 (vo-gs1 1.0 bounds the stored form, trailer included), `MarkingIdentifier` 74. Literals in the types, so a
  schema never changes silently; `ColumnLengthsFollowTheLibraryBoundsTest` compares every type with `MAX_LENGTH`
  (no reflection on private or `Internal\` constants).
- Adding a class of vo-gs1: type in both branches, both registrars, a `Samples::strings()` row, `CoversClass` in both
  `StringTypesTest`s, README row; the completeness test also pins the class count (31).
- ODM types cannot be built directly (see `vo-base-doctrine`): tests go through `Type::getType($name)` after
  `registerAll()`.
