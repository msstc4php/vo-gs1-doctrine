# Known issues

- CI fails until `vo-base` and `vo-base-doctrine` are tagged `v1.2.0` and the GitHub repositories exist.
- With zero types, Infection's `Foreach_` mutant of each `registerAll()` is equivalent and escapes (covered MSI 66%,
  under the CI threshold of 75%); it goes away with the first types.
