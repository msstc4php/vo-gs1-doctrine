<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use Doctrine\ODM\MongoDB\Types\Type;

/**
 * Registers every GS1 value-object type. Call once at bootstrap for bare Doctrine; under Symfony map {@see types()}
 * under `doctrine_mongodb.odm.types` instead. Registration is idempotent, so it is safe in long-running workers.
 */
final class TypeRegistrar
{
    /**
     * @var array<non-empty-string, class-string<Type>>
     */
    private const array TYPES = [
        CpidType::NAME => CpidType::class,
        GcnType::NAME => GcnType::class,
        GdtiType::NAME => GdtiType::class,
        GiaiType::NAME => GiaiType::class,
        GincType::NAME => GincType::class,
        GlnType::NAME => GlnType::class,
        GmnType::NAME => GmnType::class,
        GraiType::NAME => GraiType::class,
        GsinType::NAME => GsinType::class,
        GsrnType::NAME => GsrnType::class,
        GtinType::NAME => GtinType::class,
        SsccType::NAME => SsccType::class,
        DigitalLinkType::NAME => DigitalLinkType::class,
        ElementStringType::NAME => ElementStringType::class,
        EpcPureIdentityUriType::NAME => EpcPureIdentityUriType::class,
        EpcTagUriType::NAME => EpcTagUriType::class,
        EuFmdPackCodeType::NAME => EuFmdPackCodeType::class,
        EuPpnType::NAME => EuPpnType::class,
        EuTobaccoUnitIdentifierType::NAME => EuTobaccoUnitIdentifierType::class,
        EuTobaccoUnitIdentifierWithoutTimeStampType::NAME => EuTobaccoUnitIdentifierWithoutTimeStampType::class,
        RuMarkingCodeType::NAME => RuMarkingCodeType::class,
        RuMarkingIdentifierType::NAME => RuMarkingIdentifierType::class,
        Gs1BasicUdiDiType::NAME => Gs1BasicUdiDiType::class,
        HibccBasicUdiDiType::NAME => HibccBasicUdiDiType::class,
        IccbbaBasicUdiDiType::NAME => IccbbaBasicUdiDiType::class,
        IfaBasicUdiDiType::NAME => IfaBasicUdiDiType::class,
        Gs1UdiType::NAME => Gs1UdiType::class,
        HibcUdiType::NAME => HibcUdiType::class,
        IsbtUdiType::NAME => IsbtUdiType::class,
        NdcType::NAME => NdcType::class,
        Ndc12Type::NAME => Ndc12Type::class,
    ];

    public static function registerAll(): void
    {
        foreach (self::types() as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }
    }

    /**
     * @return array<non-empty-string, class-string<Type>>
     */
    public static function types(): array
    {
        return self::TYPES;
    }
}
