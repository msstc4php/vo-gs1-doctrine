# Conventions

- Type names `gs1_<snake_name>` (`gs1_gtin`, `gs1_element_string`, `gs1_ru_marking_code`); `TypeRegistrarTest` checks
  the pattern.
- `registerAll()` iterates `types()`, not the constant: PHPStan reports a foreach over a literally empty constant
  (`foreach.emptyArray`) while the registrars are empty.
- ODM types cannot be built directly (see `vo-base-doctrine`): tests go through `Type::getType($name)` after
  `registerAll()`.
