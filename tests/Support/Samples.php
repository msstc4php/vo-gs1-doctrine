<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support;

use MSSTC4PHP\ValueObject\Contract\StringConvertible;
use MSSTC4PHP\ValueObject\Gs1\DigitalLink\DigitalLink;
use MSSTC4PHP\ValueObject\Gs1\ElementString\ElementString;
use MSSTC4PHP\ValueObject\Gs1\Epc\EpcPureIdentityUri;
use MSSTC4PHP\ValueObject\Gs1\Epc\EpcTagUri;
use MSSTC4PHP\ValueObject\Gs1\Key\Cpid;
use MSSTC4PHP\ValueObject\Gs1\Key\Gcn;
use MSSTC4PHP\ValueObject\Gs1\Key\Gdti;
use MSSTC4PHP\ValueObject\Gs1\Key\Giai;
use MSSTC4PHP\ValueObject\Gs1\Key\Ginc;
use MSSTC4PHP\ValueObject\Gs1\Key\Gln;
use MSSTC4PHP\ValueObject\Gs1\Key\Gmn;
use MSSTC4PHP\ValueObject\Gs1\Key\Grai;
use MSSTC4PHP\ValueObject\Gs1\Key\Gsin;
use MSSTC4PHP\ValueObject\Gs1\Key\Gsrn;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use MSSTC4PHP\ValueObject\Gs1\Key\Sscc;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\FmdPackCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\Ppn;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\TobaccoUnitIdentifier;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\TobaccoUnitIdentifierWithoutTimeStamp;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingIdentifier;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\Gs1BasicUdiDi;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\HibccBasicUdiDi;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\IccbbaBasicUdiDi;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\IfaBasicUdiDi;
use MSSTC4PHP\ValueObject\Gs1\Udi\Gs1Udi;
use MSSTC4PHP\ValueObject\Gs1\Udi\HibcUdi;
use MSSTC4PHP\ValueObject\Gs1\Udi\IsbtUdi;
use MSSTC4PHP\ValueObject\Gs1\Udi\Ndc;
use MSSTC4PHP\ValueObject\Gs1\Udi\Ndc12;

/**
 * A canonical sample, the column length and a stored value that must be refused, per type name.
 */
final class Samples
{
    public const string GS = "\x1D";

    public const string RS = "\x1E";

    public const string EOT = "\x04";

    public const string BAD_STORED = 'zz9qq!';

    public const string RU_CODE_44 = 'MEUCIQDvA1b2C3d4E5f6G7h8I9j0K+L/M=N-O.P_Q:R;';

    public const string IFA_PACK = "[)>\x1E06\x1D9N111234567842\x1D1T1A234B5\x1DD151231\x1DS1234567890123456\x1E\x04";

    /**
     * Per type: the type name, a canonical sample, the column length, an invalid stored value and whether the sample
     * has the longest accepted length (then one more character must be refused).
     *
     * @return iterable<string, array{non-empty-string, StringConvertible, positive-int, non-empty-string, bool}>
     */
    public static function strings(): iterable
    {
        yield 'cpid' => ['gs1_cpid', Cpid::from('0614' . \str_repeat('A', 26)), 30, self::BAD_STORED, true];
        yield 'gcn' => ['gs1_gcn', Gcn::from('0614141123452123456789012'), 25, self::BAD_STORED, true];
        yield 'gdti' => ['gs1_gdti', Gdti::from('061414112345212345678901234567'), 30, self::BAD_STORED, true];
        yield 'giai' => ['gs1_giai', Giai::from('0614141' . \str_repeat('A', 23)), 30, self::BAD_STORED, true];
        yield 'ginc' => ['gs1_ginc', Ginc::from('0614141' . \str_repeat('A', 23)), 30, self::BAD_STORED, true];
        yield 'gln' => ['gs1_gln', Gln::from('0614141123452'), 13, self::BAD_STORED, true];
        yield 'gmn' => ['gs1_gmn', Gmn::from('1987654Ad4X4bL5ttr2310c2K'), 25, self::BAD_STORED, true];
        yield 'grai' => ['gs1_grai', Grai::from('00614141123452' . \str_repeat('A', 16)), 30, self::BAD_STORED, true];
        yield 'gsin' => ['gs1_gsin', Gsin::from('06141411234567890'), 17, self::BAD_STORED, true];
        yield 'gsrn' => ['gs1_gsrn', Gsrn::from('061414112345678902'), 18, self::BAD_STORED, true];
        yield 'gtin' => ['gs1_gtin', Gtin::from('09506000134352'), 14, self::BAD_STORED, true];
        yield 'sscc' => ['gs1_sscc', Sscc::from('006141411234567890'), 18, self::BAD_STORED, true];
        yield 'digital link' => [
            'gs1_digital_link',
            DigitalLink::from('https://id.gs1.org/01/09506000134352/10/ABC'),
            14178,
            self::BAD_STORED,
            false,
        ];
        yield 'element string' => [
            'gs1_element_string',
            ElementString::from('(01)09506000134352(10)ABC(21)XYZ'),
            14178,
            self::BAD_STORED,
            false,
        ];
        yield 'epc pure identity uri' => [
            'gs1_epc_pure_identity_uri',
            EpcPureIdentityUri::from('urn:epc:id:sgtin:0614141.112345.400'),
            1024,
            self::BAD_STORED,
            false,
        ];
        yield 'epc tag uri' => [
            'gs1_epc_tag_uri',
            EpcTagUri::from('urn:epc:tag:sgtin-96:3.0614141.812345.6789'),
            1024,
            self::BAD_STORED,
            false,
        ];
        yield 'eu fmd pack code, ifa' => [
            'gs1_eu_fmd_pack_code',
            FmdPackCode::from(self::IFA_PACK),
            514,
            self::BAD_STORED,
            false,
        ];
        yield 'eu fmd pack code, gs1' => [
            'gs1_eu_fmd_pack_code',
            FmdPackCode::from('(01)04150123456782(10)1A234B5(17)151231(21)1234567890123456'),
            514,
            self::BAD_STORED,
            false,
        ];
        yield 'eu ppn' => ['gs1_eu_ppn', Ppn::from('99ABCDEFGHIJKLMNOPQRSTUVWX17'), 28, self::BAD_STORED, true];
        yield 'eu tobacco unit identifier' => [
            'gs1_eu_tobacco_unit_identifier',
            TobaccoUnitIdentifier::from('ABC' . \str_repeat('x', 39) . '19052014'),
            50,
            self::BAD_STORED,
            true,
        ];
        yield 'eu tobacco unit identifier without time stamp' => [
            'gs1_eu_tobacco_unit_identifier_without_time_stamp',
            TobaccoUnitIdentifierWithoutTimeStamp::from(\str_repeat('A', 42)),
            42,
            self::BAD_STORED,
            true,
        ];
        yield 'ru marking code' => [
            'gs1_ru_marking_code',
            MarkingCode::from('0104600439931256215Ab+cDe-fGh.J' . self::GS . '91EE10' . self::GS . '92' . self::RU_CODE_44),
            255,
            self::BAD_STORED,
            false,
        ];
        yield 'ru marking identifier' => [
            'gs1_ru_marking_identifier',
            MarkingIdentifier::from('(01)04600439931256(21)5aB3x_YZ'),
            255,
            self::BAD_STORED,
            false,
        ];
        yield 'gs1 basic udi-di' => [
            'gs1_basic_udi_di',
            Gs1BasicUdiDi::from('1987654Ad4X4bL5ttr2310c2K'),
            25,
            self::BAD_STORED,
            true,
        ];
        yield 'hibcc basic udi-di' => [
            'gs1_hibcc_basic_udi_di',
            HibccBasicUdiDi::from('++A999ABCDEFGHIJKLMNOPQ6F'),
            25,
            self::BAD_STORED,
            true,
        ];
        yield 'iccbba basic udi-di' => [
            'gs1_iccbba_basic_udi_di',
            IccbbaBasicUdiDi::from('A9990ABC123T048008'),
            18,
            self::BAD_STORED,
            true,
        ];
        yield 'ifa basic udi-di' => [
            'gs1_ifa_basic_udi_di',
            IfaBasicUdiDi::from('PP12345ABCDEFGHIJKLMNOP80'),
            25,
            self::BAD_STORED,
            true,
        ];
        yield 'gs1 udi' => [
            'gs1_udi',
            Gs1Udi::from('(01)09506000134352(17)201225(10)ABC123(11)200101(21)XYZ'),
            14178,
            self::BAD_STORED,
            false,
        ];
        yield 'hibc udi' => ['gs1_hibc_udi', HibcUdi::from('+A123BJC5D6E71/$$52001510X3C'), 113, self::BAD_STORED, false];
        yield 'isbt udi' => [
            'gs1_isbt_udi',
            IsbtUdi::from('=/A9997XYZ100T0479=A99991712345600=,000012=>019031'),
            2048,
            self::BAD_STORED,
            false,
        ];
        yield 'ndc' => ['gs1_ndc', Ndc::from('123456-7890-1'), 13, self::BAD_STORED, true];
        yield 'ndc12' => ['gs1_ndc12', Ndc12::from('123456-7890-12'), 14, self::BAD_STORED, true];
    }

    /**
     * @return iterable<string, array{non-empty-string}>
     */
    public static function stringNames(): iterable
    {
        foreach (self::strings() as $label => [$name]) {
            yield $label => [$name];
        }
    }

    /**
     * @return iterable<string, array{non-empty-string, StringConvertible}>
     */
    public static function stringPairs(): iterable
    {
        foreach (self::strings() as $label => [$name, $vo]) {
            yield $label => [$name, $vo];
        }
    }

    /**
     * @return iterable<string, array{non-empty-string, StringConvertible, positive-int, bool}>
     */
    public static function stringLengths(): iterable
    {
        foreach (self::strings() as $label => [$name, $vo, $length, , $atMax]) {
            yield $label => [$name, $vo, $length, $atMax];
        }
    }

    /**
     * @return iterable<string, array{non-empty-string, non-empty-string}>
     */
    public static function stringInvalids(): iterable
    {
        foreach (self::strings() as $label => [$name, , , $invalid]) {
            yield $label => [$name, $invalid];
        }
    }
}
