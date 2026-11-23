<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use MSSTC4PHP\ValueObject\Contract\StringConvertible;
use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Exception\AmbiguousValueException;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingIdentifier;
use Override;

final class RuMarkingIdentifierType extends AbstractStringVoType
{
    public const string NAME = 'gs1_ru_marking_identifier';

    /**
     * A row written by vo-gs1 0.x may hold an ambiguous pack КИ as its bare 25 characters, which 0.x read as the
     * pack; 1.0 writes that pack with its `]d1` marker, so a bare ambiguous value can only be such a row.
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?MarkingIdentifier
    {
        if (!\is_string($value)) {
            $identifier = parent::convertToPHPValue($value, $platform);
            \assert(!$identifier instanceof StringConvertible || $identifier instanceof MarkingIdentifier);

            return $identifier;
        }

        try {
            return MarkingIdentifier::from($value);
        } catch (AmbiguousValueException) {
            return MarkingIdentifier::fromTobaccoConsumerPack($value);
        }
    }

    #[Override]
    protected function valueObjectClass(): string
    {
        return MarkingIdentifier::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 74;
    }
}
