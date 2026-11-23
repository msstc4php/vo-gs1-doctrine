# CLAUDE.md

Guidance for Claude Code in this repository. Deep references live under `.claude/docs/`.

## What this is

`msstc4php/vo-gs1-doctrine`: Doctrine ORM and MongoDB ODM types for `msstc4php/vo-gs1` (GS1 keys, element strings,
UDI, marking codes). Built on the abstract types of `msstc4php/vo-base-doctrine`. PHP >= 8.4. No Symfony dependency;
`Orm` and `Odm` never see each other (`deptrac.yaml`).

## Common commands

Local install against the sibling checkouts (`../vo-base`, `../vo-base-doctrine`, `../vo-gs1`):
`COMPOSER=composer-local.json composer update --ignore-platform-req=php`. In CI the install is `composer-ci.json`.

`composer-local.json` is gitignored. Create it from `composer-ci.json`: replace the three VCS repositories with path
repositories (`{"type": "path", "url": "../vo-base", "options": {"symlink": true}}`, same for `../vo-base-doctrine` and
`../vo-gs1`) and require `msstc4php/vo-base-doctrine` and `msstc4php/vo-base` as `dev-main as 1.3.0` and
`msstc4php/vo-gs1` as `dev-main as 0.1.0`.

- `make check`, `make test`, `make infection`, `make fix`.
- The ODM integration tests need MongoDB (`MONGODB_URI`, default `mongodb://127.0.0.1:27017`) and skip themselves
  otherwise.

## Standard

Tool configuration is byte-identical to the `php84` templates of `msstc4symfony/bundle-standard`; CI's `templates` job
enforces it. Do not edit `.php-cs-fixer.dist.php`, `rector.php`, `phpstan*.neon`, `phpunit.xml.dist`,
`infection.json5`, `Makefile` or `.gitignore` here. The hex layout of that standard does not apply.

## Adding a class of `vo-gs1`

Every non-abstract value object needs one ORM and one ODM type named `gs1_<snake_name>` (column length = the class's
maximum length), registered in both `TypeRegistrar`s.
