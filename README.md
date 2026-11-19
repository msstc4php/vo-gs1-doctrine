# msstc4php/vo-gs1-doctrine

Doctrine ORM (DBAL) and MongoDB ODM types for [`msstc4php/vo-gs1`](https://github.com/msstc4php/vo-gs1), built on
[`msstc4php/vo-base-doctrine`](https://github.com/msstc4php/vo-base-doctrine). PHP >= 8.4, no Symfony dependency.

Work in progress: the types arrive with the 0.1.0 release, one per value object, named `gs1_<snake_name>`.

## Usage

Bare Doctrine: call `Orm\TypeRegistrar::registerAll()` (or `Odm\TypeRegistrar::registerAll()`) once at bootstrap.
Symfony: map `TypeRegistrar::types()` under `doctrine.dbal.types` (ORM) or `doctrine_mongodb.odm.types` (ODM).

## License

MIT, see [`LICENSE`](LICENSE).
