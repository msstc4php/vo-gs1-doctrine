<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Unit\Contract;

use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\Type;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\TypeRegistrar;
use MSSTC4PHP\ValueObject\Gs1\Internal\DigitalLinkSyntax;
use MSSTC4PHP\ValueObject\Gs1\Internal\ElementStringSyntax;
use MSSTC4PHP\ValueObject\Gs1\Internal\EpcSyntax;
use MSSTC4PHP\ValueObject\Gs1\Internal\HibcSyntax;
use MSSTC4PHP\ValueObject\Gs1\Internal\IsbtSyntax;
use MSSTC4PHP\ValueObject\Gs1\Internal\RuMarkingSyntax;
use MSSTC4PHP\ValueObject\Gs1\Key\Cpid;
use MSSTC4PHP\ValueObject\Gs1\Key\Gcn;
use MSSTC4PHP\ValueObject\Gs1\Key\Gdti;
use MSSTC4PHP\ValueObject\Gs1\Key\Giai;
use MSSTC4PHP\ValueObject\Gs1\Key\Ginc;
use MSSTC4PHP\ValueObject\Gs1\Key\Gmn;
use MSSTC4PHP\ValueObject\Gs1\Key\Grai;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\FmdPackCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\TobaccoUnitIdentifier;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\TobaccoUnitIdentifierWithoutTimeStamp;
use MSSTC4PHP\ValueObject\Gs1\Udi\Ndc;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClassConstant;

#[CoversNothing]
final class ColumnLengthsFollowTheLibraryBoundsTest extends TestCase
{
    private const int VARCHAR_LIMIT = 4000;

    /**
     * Types whose bound is not a constant of the library (fixed-width keys, check-digit formats) are proven by the
     * at-max samples of the string type tests instead.
     *
     * @return iterable<string, array{non-empty-string, int}>
     */
    public static function bounds(): iterable
    {
        yield 'cpid' => ['gs1_cpid', self::constant(Cpid::class, 'MAX')];
        yield 'gcn' => ['gs1_gcn', 13 + self::constant(Gcn::class, 'SERIAL_MAX')];
        yield 'gdti' => ['gs1_gdti', 13 + self::constant(Gdti::class, 'SERIAL_MAX')];
        yield 'giai' => ['gs1_giai', self::constant(Giai::class, 'MAX')];
        yield 'ginc' => ['gs1_ginc', self::constant(Ginc::class, 'MAX')];
        yield 'gmn' => ['gs1_gmn', self::constant(Gmn::class, 'MAX')];
        yield 'grai' => ['gs1_grai', 14 + self::constant(Grai::class, 'SERIAL_MAX')];
        yield 'epc pure identity uri' => ['gs1_epc_pure_identity_uri', self::constant(EpcSyntax::class, 'MAX_LENGTH')];
        yield 'epc tag uri' => ['gs1_epc_tag_uri', self::constant(EpcSyntax::class, 'MAX_LENGTH')];
        // An IFA message read without its trailer is stored with RS and EOT appended.
        yield 'eu fmd pack code' => ['gs1_eu_fmd_pack_code', FmdPackCode::MAX_LENGTH + 2];
        yield 'eu tobacco unit identifier' => [
            'gs1_eu_tobacco_unit_identifier',
            self::constant(TobaccoUnitIdentifier::class, 'MAX_LENGTH'),
        ];
        yield 'eu tobacco unit identifier without time stamp' => [
            'gs1_eu_tobacco_unit_identifier_without_time_stamp',
            self::constant(TobaccoUnitIdentifierWithoutTimeStamp::class, 'MAX_LENGTH'),
        ];
        yield 'ru marking code' => ['gs1_ru_marking_code', RuMarkingSyntax::MAX_LENGTH];
        yield 'ru marking identifier' => ['gs1_ru_marking_identifier', RuMarkingSyntax::MAX_LENGTH];
        yield 'gs1 basic udi-di' => ['gs1_basic_udi_di', self::constant(Gmn::class, 'MAX')];
        yield 'hibc udi' => ['gs1_hibc_udi', self::constant(HibcSyntax::class, 'MAX_LENGTH')];
        yield 'isbt udi' => ['gs1_isbt_udi', self::constant(IsbtSyntax::class, 'MAX_LENGTH')];
        yield 'ndc' => ['gs1_ndc', self::constant(Ndc::class, 'MAX_LENGTH')];
    }

    #[DataProvider('bounds')]
    public function testDeclaredLengthEqualsTheLibraryBound(string $name, int $bound): void
    {
        TypeRegistrar::registerAll();

        self::assertSame(
            \sprintf('VARCHAR(%d)', $bound),
            Type::getType($name)->getSQLDeclaration([], new SQLitePlatform()),
        );
        // SQL Server NVARCHAR and Oracle VARCHAR2 stop at 4000.
        self::assertLessThanOrEqual(self::VARCHAR_LIMIT, $bound);
    }

    /**
     * @return iterable<string, array{non-empty-string, int}>
     */
    public static function textBounds(): iterable
    {
        yield 'digital link' => ['gs1_digital_link', self::constant(DigitalLinkSyntax::class, 'MAX_LENGTH')];
        yield 'element string' => ['gs1_element_string', self::constant(ElementStringSyntax::class, 'MAX_LENGTH')];
        yield 'gs1 udi' => ['gs1_udi', self::constant(ElementStringSyntax::class, 'MAX_LENGTH')];
    }

    #[DataProvider('textBounds')]
    public function testABoundAboveTheVarcharLimitMapsToAClob(string $name, int $bound): void
    {
        TypeRegistrar::registerAll();
        $platform = new SQLitePlatform();

        self::assertGreaterThan(self::VARCHAR_LIMIT, $bound);
        self::assertSame($platform->getClobTypeDeclarationSQL([]), Type::getType($name)->getSQLDeclaration([], $platform));
    }

    public function testEveryOrmTypeIsBoundedOrAClob(): void
    {
        $listed = [...\array_column(\iterator_to_array(self::bounds()), 0), ...\array_column(\iterator_to_array(self::textBounds()), 0)];
        $unlisted = \array_diff(\array_keys(TypeRegistrar::types()), $listed);
        TypeRegistrar::registerAll();

        foreach ($unlisted as $name) {
            $declaration = Type::getType($name)->getSQLDeclaration([], new SQLitePlatform());
            self::assertMatchesRegularExpression('/^VARCHAR\((\d{1,3}|[1-3]\d{3}|4000)\)$/', $declaration, $name);
        }
    }

    /**
     * @param class-string $class
     */
    private static function constant(string $class, string $name): int
    {
        $value = new ReflectionClassConstant($class, $name)->getValue();
        self::assertIsInt($value);

        return $value;
    }
}
