<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Unit\Orm;

use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use MSSTC4PHP\ValueObject\Contract\StringConvertible;
use MSSTC4PHP\ValueObject\Exception\InvalidValueObjectException;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\CpidType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\DigitalLinkType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\ElementStringType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EpcPureIdentityUriType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EpcTagUriType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EuFmdPackCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EuPpnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EuTobaccoUnitIdentifierType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EuTobaccoUnitIdentifierWithoutTimeStampType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GcnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GdtiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GiaiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GincType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GlnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GmnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GraiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\Gs1BasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\Gs1UdiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GsinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GsrnType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GtinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\HibccBasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\HibcUdiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\IccbbaBasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\IfaBasicUdiDiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\IsbtUdiType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\Ndc12Type;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\NdcType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\RuMarkingCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\RuMarkingIdentifierType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\SsccType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\TypeRegistrar;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Samples;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use MSSTC4PHP\ValueObject\Gs1\Key\Sscc;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingIdentifier;
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
    private SQLitePlatform $platform;

    #[Override]
    protected function setUp(): void
    {
        TypeRegistrar::registerAll();
        $this->platform = new SQLitePlatform();
    }

    #[DataProviderExternal(Samples::class, 'stringPairs')]
    public function testRoundTripsTheCanonicalForm(string $name, StringConvertible $vo): void
    {
        $type = Type::getType($name);

        $stored = $type->convertToDatabaseValue($vo, $this->platform);
        self::assertSame((string) $vo, $stored);

        $restored = $type->convertToPHPValue($stored, $this->platform);
        self::assertInstanceOf($vo::class, $restored);
        self::assertSame((string) $vo, (string) $restored);
    }

    #[DataProviderExternal(Samples::class, 'stringLengths')]
    public function testColumnFitsTheLongestAcceptedForm(string $name, StringConvertible $vo, int $length, bool $atMax): void
    {
        self::assertSame(
            \in_array($name, Samples::TEXT_TYPES, true) ? $this->platform->getClobTypeDeclarationSQL([]) : \sprintf('VARCHAR(%d)', $length),
            Type::getType($name)->getSQLDeclaration([], $this->platform),
        );
        self::assertLessThanOrEqual($length, \mb_strlen((string) $vo));

        if (!$atMax) {
            return;
        }

        self::assertSame($length, \mb_strlen((string) $vo));
        $this->expectException(InvalidValueObjectException::class);
        $vo::from($vo . \mb_substr((string) $vo, -1));
    }

    #[DataProviderExternal(Samples::class, 'stringNames')]
    public function testNullRoundTrips(string $name): void
    {
        $type = Type::getType($name);

        self::assertNull($type->convertToDatabaseValue(null, $this->platform));
        self::assertNull($type->convertToPHPValue(null, $this->platform));
    }

    #[DataProviderExternal(Samples::class, 'stringInvalids')]
    public function testInvalidStoredValueThrowsWithoutEchoingIt(string $name, string $invalid): void
    {
        try {
            Type::getType($name)->convertToPHPValue($invalid, $this->platform);
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
            Type::getType($name)->convertToDatabaseValue($foreign, $this->platform);
            self::fail('A value object of another class was written.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame(\sprintf('Expected %s, got %s.', $vo::class, $foreign::class), $exception->getMessage());
        }
    }

    #[DataProviderExternal(Samples::class, 'stringNames')]
    public function testRefusesAPlainStringOnWrite(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        Type::getType($name)->convertToDatabaseValue('09506000134352', $this->platform);
    }

    #[DataProviderExternal(Samples::class, 'stringNames')]
    public function testRefusesANonStringColumnValue(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);

        Type::getType($name)->convertToPHPValue(123, $this->platform);
    }

    public function testA0xRowOfAnAmbiguousPackIdentifierLoadsAsThePack(): void
    {
        $type = Type::getType(RuMarkingIdentifierType::NAME);
        $identifier = $type->convertToPHPValue("0104600439930501215U!Mq'X", $this->platform);

        self::assertInstanceOf(MarkingIdentifier::class, $identifier);
        self::assertTrue($identifier->isTobaccoConsumerPack());
        self::assertSame("]d10104600439930501215U!Mq'X", $type->convertToDatabaseValue($identifier, $this->platform));
    }
}
