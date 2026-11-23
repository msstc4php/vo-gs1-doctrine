<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Unit\Contract;

use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\Type;
use MSSTC4PHP\ValueObject\Contract\StringConvertible;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\TypeRegistrar;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Samples;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClassConstant;

/**
 * Every column follows the public `MAX_LENGTH` of its value object (frozen for vo-gs1 1.x); the types keep literals, so
 * a schema never changes silently and a drift fails here.
 */
#[CoversNothing]
final class ColumnLengthsFollowTheLibraryBoundsTest extends TestCase
{
    private const int VARCHAR_LIMIT = 4000;

    /**
     * @return iterable<string, array{non-empty-string, StringConvertible}>
     */
    public static function types(): iterable
    {
        foreach (Samples::strings() as $label => [$name, $sample]) {
            yield $label => [$name, $sample];
        }
    }

    #[DataProvider('types')]
    public function testTheColumnIsTheMaxLengthOfTheValueObject(string $name, StringConvertible $sample): void
    {
        TypeRegistrar::registerAll();
        $platform = new SQLitePlatform();
        $bound = new ReflectionClassConstant($sample::class, 'MAX_LENGTH')->getValue();
        self::assertIsInt($bound);

        // SQL Server NVARCHAR and Oracle VARCHAR2 stop at 4000; above it the type is a CLOB.
        $expected = $bound > self::VARCHAR_LIMIT ? $platform->getClobTypeDeclarationSQL([]) : \sprintf('VARCHAR(%d)', $bound);
        self::assertSame($expected, Type::getType($name)->getSQLDeclaration([], $platform));
    }

    public function testEveryOrmTypeIsChecked(): void
    {
        self::assertEqualsCanonicalizing(
            \array_keys(TypeRegistrar::types()),
            \array_values(\array_unique(\array_column(\iterator_to_array(self::types()), 0))),
        );
    }
}
