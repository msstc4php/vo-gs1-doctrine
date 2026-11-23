# msstc4php/vo-gs1-doctrine

Doctrine ORM (DBAL) and MongoDB ODM types for [`msstc4php/vo-gs1`](https://github.com/msstc4php/vo-gs1), built on
[`msstc4php/vo-base-doctrine`](https://github.com/msstc4php/vo-base-doctrine). PHP >= 8.4, no Symfony dependency.

31 types per branch, one per value object of `vo-gs1` (keys, element strings, Digital Link, EPC URIs, UDI, Basic UDI-DI,
NDC, EU and Russian marking codes). The backed enums (`GtinFormat`, `IssuingAgency`, `EpcScheme`, `EpcTagEncoding`,
`HibcDateFormat`, `MarkingCodeStructure`, `NdcConfiguration`) need no type: map them with `enumType`. `Gs1Prefix` is a
pure enum (no backing value), so it cannot be mapped with `enumType`; it is derived from the key (`Gtin::gs1Prefix()`),
so store the key instead.

Status: stable, 1.0.0. Type names and column lengths are covered by the 1.x promise (see
[Backward compatibility](#backward-compatibility)).

## Install

Register the GitHub repositories (until the packages are on Packagist):

```json
{
    "repositories": [
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-base" },
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-base-doctrine" },
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-gs1" },
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-gs1-doctrine" }
    ],
    "require": {
        "msstc4php/vo-gs1-doctrine": "^1.0"
    }
}
```

Runtime requirements: `msstc4php/vo-gs1 ^1.0`, `msstc4php/vo-base-doctrine ^1.3`; `doctrine/dbal` (ORM) or
`doctrine/mongodb-odm` (ODM) is yours.

## Type names and lengths

Every name is `gs1_` plus the snake_case of the class name; regional namespaces are kept as a prefix
(`Marking\RU\MarkingCode` is `gs1_ru_marking_code`, `Marking\EU\Ppn` is `gs1_eu_ppn`). The issuing agency of a UDI class
is part of its name, `Gs1` included: `Udi\Gs1Udi` is `gs1_gs1_udi` and `Udi\BasicUdiDi\Gs1BasicUdiDi` is
`gs1_gs1_basic_udi_di`, like `gs1_hibc_udi` and `gs1_hibcc_basic_udi_di`. The generic names `gs1_udi` and
`gs1_basic_udi_di` stay free for a later type that stores any UDI or any Basic UDI-DI (`Udi` and `BasicUdiDi` are
interfaces, which vo-base does not map; such polymorphic storage will be a separate, additive type). The ORM column is
`VARCHAR(n)`, `n` being the class's public `MAX_LENGTH` (the longest stored value, frozen for vo-gs1 1.x), except where
that bound exceeds 4000 characters (the VARCHAR limit of SQL Server and Oracle; MySQL's row size limit too): those types
(`gs1_digital_link`, `gs1_element_string`, `gs1_gs1_udi`) map to vo-base-doctrine's `AbstractTextVoType`, a CLOB/TEXT
column. A TEXT column cannot carry a plain index on every platform (MySQL needs a prefix length); index a derived key
instead (the GTIN, a hash of the value). The ODM field is a BSON string without a length.

On write a type accepts only its own value-object class: a `Gtin` given to `gs1_ru_marking_code` throws
`InvalidArgumentException` (vo-base-doctrine 1.2).

**Collation.** Some values are case-sensitive: the crypto tail and serials of Russian marking codes, the FMD serial and
batch, the PPN, the EU tobacco unit identifier, GS1 serials (CSET 82) and Digital Link URIs. Under a case-insensitive
collation (MySQL `*_ci`, SQL Server's default `CI`) two values that differ only in case collide in a unique index and
match each other in `WHERE`. Give these columns a binary or case-sensitive collation (MySQL `utf8mb4_bin` or
`ascii_bin`, SQL Server `Latin1_General_BIN2`), for example
`#[ORM\Column(type: 'gs1_ru_marking_code', options: ['collation' => 'utf8mb4_bin'])]`.

**Equality is not string equality for some types.** `Gtin` keeps the form given but `equals()` compares the 14-digit
form, so `4006381333931` and `04006381333931` are equal objects and different rows: a unique index lets both in and
`WHERE gtin = …` misses one. Index or compare on `toGtin14()` (a separate column or a generated one), or normalise
before you persist (`Gtin::from($gtin->toGtin14())`). `IsbtUdi` (compound message or human-readable form) and
`DigitalLink` (escapes, case of the host, parameter order) behave the same way. Rows written under vo-gs1 0.x can
hold an older stored form of the same value (an ambiguous tobacco pack КИ without `]d1`, an `IsbtUdi` without its
compound message header); CHANGELOG, "Upgrading from 0.x", has the data migration.

Values keep control characters: GS (U+001D), RS (U+001E) and EOT (U+0004) in marking and FMD codes are stored as they
are. An element string is stored in its canonical bracketed form, which has no GS.

| Type name | Value object | Column length (characters) | Length derived from |
|---|---|---|---|
| `gs1_cpid` | `Key\Cpid` | 30 | `Cpid::MAX_LENGTH` |
| `gs1_gcn` | `Key\Gcn` | 25 | `Gcn::MAX_LENGTH`: 13 digits + a serial of up to 12 |
| `gs1_gdti` | `Key\Gdti` | 30 | `Gdti::MAX_LENGTH`: 13 digits + a serial of up to 17 |
| `gs1_giai` | `Key\Giai` | 30 | `Giai::MAX_LENGTH` |
| `gs1_ginc` | `Key\Ginc` | 30 | `Ginc::MAX_LENGTH` |
| `gs1_gln` | `Key\Gln` | 13 | `Gln::MAX_LENGTH`, fixed |
| `gs1_gmn` | `Key\Gmn` | 25 | `Gmn::MAX_LENGTH` |
| `gs1_grai` | `Key\Grai` | 30 | `Grai::MAX_LENGTH`: 14 digits + a serial of up to 16 |
| `gs1_gsin` | `Key\Gsin` | 17 | `Gsin::MAX_LENGTH`, fixed |
| `gs1_gsrn` | `Key\Gsrn` | 18 | `Gsrn::MAX_LENGTH`, fixed |
| `gs1_gtin` | `Key\Gtin` | 14 | `Gtin::MAX_LENGTH`, the longest accepted form (8, 12, 13, 14 accepted) |
| `gs1_sscc` | `Key\Sscc` | 18 | `Sscc::MAX_LENGTH`, fixed |
| `gs1_digital_link` | `DigitalLink\DigitalLink` | CLOB (bound 14178) | `DigitalLink::MAX_LENGTH` (the value is the URI as given) |
| `gs1_element_string` | `ElementString\ElementString` | CLOB (bound 14178) | `ElementString::MAX_LENGTH`: input and canonical bracketed form, a package bound |
| `gs1_epc_pure_identity_uri` | `Epc\EpcPureIdentityUri` | 1024 | `EpcPureIdentityUri::MAX_LENGTH` |
| `gs1_epc_tag_uri` | `Epc\EpcTagUri` | 1024 | `EpcTagUri::MAX_LENGTH` |
| `gs1_eu_fmd_pack_code` | `Marking\EU\FmdPackCode` | 514 | `FmdPackCode::MAX_LENGTH`: the stored form, including the RS EOT trailer that is appended to an IFA message given without one |
| `gs1_eu_ppn` | `Marking\EU\Ppn` | 28 | `Ppn::MAX_LENGTH`: PRA Code 2, product number up to 24, check digits 2 |
| `gs1_eu_tobacco_unit_identifier` | `Marking\EU\TobaccoUnitIdentifier` | 50 | `TobaccoUnitIdentifier::MAX_LENGTH` (Article 8(1)) |
| `gs1_eu_tobacco_unit_identifier_without_time_stamp` | `Marking\EU\TobaccoUnitIdentifierWithoutTimeStamp` | 42 | `TobaccoUnitIdentifierWithoutTimeStamp::MAX_LENGTH` (50 less the 8-digit time stamp) |
| `gs1_ru_marking_code` | `Marking\RU\MarkingCode` | 255 | `MarkingCode::MAX_LENGTH`, a package bound |
| `gs1_ru_marking_identifier` | `Marking\RU\MarkingIdentifier` | 74 | `MarkingIdentifier::MAX_LENGTH`: (01) + 14, (21) + a 20-character serial with every `(` escaped, (8005) + 6 |
| `gs1_gs1_basic_udi_di` | `Udi\BasicUdiDi\Gs1BasicUdiDi` | 25 | `Gs1BasicUdiDi::MAX_LENGTH`, a GMN |
| `gs1_hibcc_basic_udi_di` | `Udi\BasicUdiDi\HibccBasicUdiDi` | 25 | `HibccBasicUdiDi::MAX_LENGTH`: `++` + LIC 4 + model identifier 17 + 2 check characters |
| `gs1_iccbba_basic_udi_di` | `Udi\BasicUdiDi\IccbbaBasicUdiDi` | 18 | `IccbbaBasicUdiDi::MAX_LENGTH`: 16 + 2 checksum digits |
| `gs1_ifa_basic_udi_di` | `Udi\BasicUdiDi\IfaBasicUdiDi` | 25 | `IfaBasicUdiDi::MAX_LENGTH`: `PP` + manufacturer 5 + device group 16 + 2 check digits |
| `gs1_gs1_udi` | `Udi\Gs1Udi` | CLOB (bound 14178) | `Gs1Udi::MAX_LENGTH`, the element string bound |
| `gs1_hibc_udi` | `Udi\HibcUdi` | 113 | `HibcUdi::MAX_LENGTH` |
| `gs1_isbt_udi` | `Udi\IsbtUdi` | 3123 | `IsbtUdi::MAX_LENGTH`: 3116 characters of data structures (the largest Data Matrix) plus the compound message header |
| `gs1_ndc` | `Udi\Ndc` | 13 | `Ndc::MAX_LENGTH` (6-4-1 and 6-3-2 with hyphens) |
| `gs1_ndc12` | `Udi\Ndc12` | 14 | `Ndc12::MAX_LENGTH`: 12 digits + 2 hyphens |

## Usage

Registration, ORM or ODM (the two branches never see each other):

```php
\MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\TypeRegistrar::registerAll();   // once at bootstrap; idempotent
\MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\TypeRegistrar::registerAll();
```

Symfony: map `TypeRegistrar::types()` (name => class) under `doctrine.dbal.types` (ORM) or
`doctrine_mongodb.odm.types` (ODM). Then use the type name in the mapping:

```php
#[ORM\Column(type: 'gs1_gtin', length: 14)]
private Gtin $gtin;
```

The value objects are string value objects, so no codec is involved: the canonical string is stored.

## Backward compatibility

From 1.0.0 on, a breaking change needs 2.0. The 1.x promise covers the type names, the type classes and their
`NAME` constants, the ORM column lengths (each the `MAX_LENGTH` of its value object, which vo-gs1 freezes for 1.x)
and the choice of VARCHAR or CLOB, and the `TypeRegistrar` API. A minor may add types (for example a polymorphic
`gs1_udi`), never rename one or change a column. The stored value is vo-gs1's canonical form, whose own promise is in
the [vo-gs1 README](https://github.com/msstc4php/vo-gs1#backward-compatibility). Exception message wording is not
covered.

## License

MIT, see [`LICENSE`](LICENSE).
