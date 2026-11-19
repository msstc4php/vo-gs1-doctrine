<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Unit\Odm;

use Doctrine\ODM\MongoDB\Types\Type;
use InvalidArgumentException;
use MSSTC4PHP\ValueObject\Contract\StringConvertible;
use MSSTC4PHP\ValueObject\Exception\InvalidValueObjectException;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\CpidType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\DigitalLinkType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\ElementStringType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EpcPureIdentityUriType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EpcTagUriType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EuFmdPackCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EuPpnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EuTobaccoUnitIdentifierType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EuTobaccoUnitIdentifierWithoutTimeStampType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GcnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GdtiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GiaiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GincType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GlnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GmnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GraiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\Gs1BasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\Gs1UdiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GsinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GsrnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GtinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\HibccBasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\HibcUdiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\IccbbaBasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\IfaBasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\IsbtUdiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\Ndc12Type;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\NdcType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\RuMarkingCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\RuMarkingIdentifierType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\SsccType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\TypeRegistrar;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Samples;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use MSSTC4PHP\ValueObject\Gs1\Key\Sscc;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CpidType::class)]
#[CoversClass(GcnType::class)]
#[CoversClass(GdtiType::class)]
#[CoversClass(GiaiType::class)]
#[CoversClass(GincType::class)]
#[CoversClass(GlnType::class)]
#[CoversClass(GmnType::class)]
#[CoversClass(GraiType::class)]
#[CoversClass(GsinType::class)]
#[CoversClass(GsrnType::class)]
#[CoversClass(GtinType::class)]
#[CoversClass(SsccType::class)]
#[CoversClass(DigitalLinkType::class)]
#[CoversClass(ElementStringType::class)]
#[CoversClass(EpcPureIdentityUriType::class)]
#[CoversClass(EpcTagUriType::class)]
#[CoversClass(EuFmdPackCodeType::class)]
#[CoversClass(EuPpnType::class)]
#[CoversClass(EuTobaccoUnitIdentifierType::class)]
#[CoversClass(EuTobaccoUnitIdentifierWithoutTimeStampType::class)]
#[CoversClass(RuMarkingCodeType::class)]
#[CoversClass(RuMarkingIdentifierType::class)]
#[CoversClass(Gs1BasicUdiDiType::class)]
#[CoversClass(HibccBasicUdiDiType::class)]
#[CoversClass(IccbbaBasicUdiDiType::class)]
#[CoversClass(IfaBasicUdiDiType::class)]
#[CoversClass(Gs1UdiType::class)]
#[CoversClass(HibcUdiType::class)]
#[CoversClass(IsbtUdiType::class)]
#[CoversClass(NdcType::class)]
#[CoversClass(Ndc12Type::class)]
#[UsesClass(TypeRegistrar::class)]
final class StringTypesTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        TypeRegistrar::registerAll();
    }

    #[DataProviderExternal(Samples::class, 'stringPairs')]
    public function testRoundTripsTheCanonicalForm(string $name, StringConvertible $vo): void
    {
        $type = Type::getType($name);

        $stored = $type->convertToDatabaseValue($vo);
        self::assertSame((string) $vo, $stored);

        $restored = $type->convertToPHPValue($stored);
        self::assertInstanceOf($vo::class, $restored);
        self::assertSame((string) $vo, (string) $restored);
    }

    #[DataProviderExternal(Samples::class, 'stringNames')]
    public function testNullRoundTrips(string $name): void
    {
        $type = Type::getType($name);

        self::assertNull($type->convertToDatabaseValue(null));
        self::assertNull($type->convertToPHPValue(null));
    }

    #[DataProviderExternal(Samples::class, 'stringInvalids')]
    public function testInvalidStoredValueThrowsWithoutEchoingIt(string $name, string $invalid): void
    {
        try {
            Type::getType($name)->convertToPHPValue($invalid);
            self::fail('An invalid stored value was accepted.');
        } catch (InvalidValueObjectException $exception) {
            self::assertStringNotContainsString($invalid, $exception->getMessage());
        }
    }

    #[DataProviderExternal(Samples::class, 'stringPairs')]
    public function testRefusesAValueObjectOfAnotherClassOnWrite(string $name, StringConvertible $vo): void
    {
        $foreign = $vo instanceof Gtin ? Sscc::from('006141411234567890') : Gtin::from('09506000134352');

        try {
            Type::getType($name)->convertToDatabaseValue($foreign);
            self::fail('A value object of another class was written.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame(\sprintf('Expected %s, got %s.', $vo::class, $foreign::class), $exception->getMessage());
        }
    }

    #[DataProviderExternal(Samples::class, 'stringNames')]
    public function testRefusesAPlainStringOnWrite(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        Type::getType($name)->convertToDatabaseValue('09506000134352');
    }

    #[DataProviderExternal(Samples::class, 'stringNames')]
    public function testRefusesANonStringColumnValue(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        Type::getType($name)->convertToPHPValue(123);
    }
}
