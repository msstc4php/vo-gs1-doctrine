# Changelog

## [Unreleased]

### Added

- Package scaffold, CI and tool configuration from `vo-documents-doctrine`; empty ORM and ODM `TypeRegistrar`s.
- ORM and ODM types for every value object of `vo-gs1` (31 per branch, `gs1_<snake_name>`), registered in both
  `TypeRegistrar`s; the column length is the longest canonical value of the class.
