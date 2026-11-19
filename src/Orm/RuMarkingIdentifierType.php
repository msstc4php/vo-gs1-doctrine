<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingIdentifier;
use Override;

final class RuMarkingIdentifierType extends AbstractStringVoType
{
    public const string NAME = 'gs1_ru_marking_identifier';

    #[Override]
    protected function valueObjectClass(): string
    {
        return MarkingIdentifier::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 255;
    }
}
