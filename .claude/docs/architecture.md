# Architecture

Two independent branches, no shared code (`deptrac.yaml`: `Orm` and `Odm` have no rules, so neither may see the other).

- `src/Orm/*Type.php`: `final` subclasses of `MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType` (value object
  class and column length). Every vo-gs1 value object is a string value object, so no JSON types are expected.
- `src/Odm/*Type.php`: the same over `Odm\AbstractStringVoType`; no column length.
- `Orm\TypeRegistrar` / `Odm\TypeRegistrar`: `registerAll()` (idempotent) and `types()` (name => class). Empty until
  the vo-gs1 value objects exist (plan Task 9).
