# Integrations

- `msstc4php/vo-base-doctrine ^1.3` (abstract types) and `msstc4php/vo-gs1 ^1.0` (`dev-main as 1.0.0` in
  `composer-ci.json` until vo-gs1 1.0.0 is tagged: tag vo-gs1 first). Both reached through their GitHub repositories
  (no Packagist yet). vo-base is not required directly: the bridge uses none of its `Payload` classes.
- Types per value object: 31 per branch, one per non-abstract, non-`Internal` class implementing `ValueObject`
  (all are `StringConvertible`). Enums, `UnmetRequirement`, builders, factories and `Internal\*` get none.
