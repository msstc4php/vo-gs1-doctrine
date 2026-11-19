# msstc4php/vo-gs1-doctrine

Doctrine ORM (DBAL) and MongoDB ODM types for [`msstc4php/vo-gs1`](https://github.com/msstc4php/vo-gs1), built on
[`msstc4php/vo-base-doctrine`](https://github.com/msstc4php/vo-base-doctrine). PHP >= 8.4, no Symfony dependency.

31 types per branch, one per value object of `vo-gs1` (keys, element strings, Digital Link, EPC URIs, UDI, Basic UDI-DI,
NDC, EU and Russian marking codes). Backed enums (`GtinFormat`, `Gs1Prefix`, `IssuingAgency`, `EpcScheme`, ...) need no
type: map them with `enumType`.

## Install

Not on Packagist yet; register the GitHub repositories:

```json
{
    "repositories": [
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-base" },
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-base-doctrine" },
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-gs1" },
        { "type": "vcs", "url": "https://github.com/msstc4php/vo-gs1-doctrine" }
    ],
    "require": {
        "msstc4php/vo-gs1-doctrine": "^0.1"
    }
}
```

Runtime requirements: `msstc4php/vo-gs1`, `msstc4php/vo-base-doctrine ^1.2`; `doctrine/dbal` (ORM) or
`doctrine/mongodb-odm` (ODM) is yours.

## Type names and lengths

Every name is `gs1_` plus the snake_case of the class path below the package root, dropping a redundant leading
`Gs1`; regional namespaces are kept as a prefix (`Marking\RU\MarkingCode` is `gs1_ru_marking_code`,
`Marking\EU\Ppn` is `gs1_eu_ppn`, `Udi\Gs1Udi` is `gs1_udi`, `Udi\BasicUdiDi\Gs1BasicUdiDi` is `gs1_basic_udi_di`). The ORM column is `VARCHAR(n)`, `n` being the
characters of the longest canonical value the class accepts, except where that bound exceeds 4000 characters (the
VARCHAR limit of SQL Server and Oracle; MySQL's row size limit too): those types (`gs1_digital_link`,
`gs1_element_string`, `gs1_udi`) map to vo-base-doctrine's `AbstractTextVoType`, a CLOB/TEXT column. A TEXT column
cannot carry a plain index on every platform (MySQL needs a prefix length); index a derived key instead (the GTIN, a
hash of the value). The ODM field is a BSON string without a length.

On write a type accepts only its own value-object class: a `Gtin` given to `gs1_ru_marking_code` throws
`InvalidArgumentException` (vo-base-doctrine 1.2).

**Collation.** Some values are case-sensitive: the crypto tail and serials of Russian marking codes, the FMD serial and
batch, the PPN, the EU tobacco unit identifier, GS1 serials (CSET 82) and Digital Link URIs. Under a case-insensitive
collation (MySQL `*_ci`, SQL Server's default `CI`) two values that differ only in case collide in a unique index and
match each other in `WHERE`. Give these columns a binary or case-sensitive collation (MySQL `utf8mb4_bin` or
`ascii_bin`, SQL Server `Latin1_General_BIN2`), for example
`#[ORM\Column(type: 'gs1_ru_marking_code', options: ['collation' => 'utf8mb4_bin'])]`.

Values keep control characters: GS (U+001D), RS (U+001E) and EOT (U+0004) in marking and FMD codes are stored as they
are. An element string is stored in its canonical bracketed form, which has no GS.

| Type name | Value object | Column length (characters) | Length derived from |
|---|---|---|---|
| `gs1_cpid` | `Key\Cpid` | 30 | `Cpid::MAX` = 30 |
| `gs1_gcn` | `Key\Gcn` | 25 | 13 digits + serial cap 12 (`SERIAL_MAX`) |
| `gs1_gdti` | `Key\Gdti` | 30 | 13 digits + serial cap 17 (`SERIAL_MAX`) |
| `gs1_giai` | `Key\Giai` | 30 | `Giai::MAX` = 30 |
| `gs1_ginc` | `Key\Ginc` | 30 | `Ginc::MAX` = 30 |
| `gs1_gln` | `Key\Gln` | 13 | fixed 13 digits |
| `gs1_gmn` | `Key\Gmn` | 25 | `Gmn::MAX` = 25 |
| `gs1_grai` | `Key\Grai` | 30 | 14 characters + serial cap 16 (`SERIAL_MAX`) |
| `gs1_gsin` | `Key\Gsin` | 17 | fixed 17 digits |
| `gs1_gsrn` | `Key\Gsrn` | 18 | fixed 18 digits |
| `gs1_gtin` | `Key\Gtin` | 14 | longest accepted form, 14 digits (8, 12, 13, 14 accepted) |
| `gs1_sscc` | `Key\Sscc` | 18 | fixed 18 digits |
| `gs1_digital_link` | `DigitalLink\DigitalLink` | CLOB (bound 14178) | `DigitalLinkSyntax::MAX_LENGTH` (the value is the URI as given) |
| `gs1_element_string` | `ElementString\ElementString` | CLOB (bound 14178) | `ElementStringSyntax::MAX_LENGTH`: input and canonical bracketed form, a package bound |
| `gs1_epc_pure_identity_uri` | `Epc\EpcPureIdentityUri` | 1024 | `EpcSyntax::MAX_LENGTH` |
| `gs1_epc_tag_uri` | `Epc\EpcTagUri` | 1024 | `EpcSyntax::MAX_LENGTH` |
| `gs1_eu_fmd_pack_code` | `Marking\EU\FmdPackCode` | 514 | `FmdPackCode::MAX_LENGTH` 512 (input cap) + 2: an IFA message read without its trailer is stored with RS EOT appended |
| `gs1_eu_ppn` | `Marking\EU\Ppn` | 28 | 2 + 24 + 2 (PRA Code, product number up to 24, check digits; `Ppn::PATTERN`) |
| `gs1_eu_tobacco_unit_identifier` | `Marking\EU\TobaccoUnitIdentifier` | 50 | `MAX_LENGTH` = 50 (Article 8(1)) |
| `gs1_eu_tobacco_unit_identifier_without_time_stamp` | `Marking\EU\TobaccoUnitIdentifierWithoutTimeStamp` | 42 | `MAX_LENGTH` = 42 (50 less the 8-digit time stamp) |
| `gs1_ru_marking_code` | `Marking\RU\MarkingCode` | 255 | `RuMarkingSyntax::MAX_LENGTH` |
| `gs1_ru_marking_identifier` | `Marking\RU\MarkingIdentifier` | 255 | `RuMarkingSyntax::MAX_LENGTH` (same input cap) |
| `gs1_basic_udi_di` | `Udi\BasicUdiDi\Gs1BasicUdiDi` | 25 | a GMN, `Gmn::MAX` = 25 |
| `gs1_hibcc_basic_udi_di` | `Udi\BasicUdiDi\HibccBasicUdiDi` | 25 | `++` + LIC 4 + model identifier 17 + 2 check characters |
| `gs1_iccbba_basic_udi_di` | `Udi\BasicUdiDi\IccbbaBasicUdiDi` | 18 | fixed 16 + 2 checksum digits |
| `gs1_ifa_basic_udi_di` | `Udi\BasicUdiDi\IfaBasicUdiDi` | 25 | `PP` + manufacturer 5 + device group 16 + 2 check digits |
| `gs1_udi` | `Udi\Gs1Udi` | CLOB (bound 14178) | same canonical form as ElementString |
| `gs1_hibc_udi` | `Udi\HibcUdi` | 113 | `HibcSyntax::MAX_LENGTH` |
| `gs1_isbt_udi` | `Udi\IsbtUdi` | 3116 | `IsbtSyntax::MAX_LENGTH`, the largest Data Matrix (the stored concatenation is never longer than the input) |
| `gs1_ndc` | `Udi\Ndc` | 13 | `Ndc::MAX_LENGTH` = 13 (6-4-1 and 6-3-2 with hyphens) |
| `gs1_ndc12` | `Udi\Ndc12` | 14 | 12 digits + 2 hyphens |

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

## License

MIT, see [`LICENSE`](LICENSE).
