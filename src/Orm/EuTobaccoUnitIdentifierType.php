<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\TobaccoUnitIdentifier;
use Override;

final class EuTobaccoUnitIdentifierType extends AbstractStringVoType
{
    public const string NAME = 'gs1_eu_tobacco_unit_identifier';

    #[Override]
    protected function valueObjectClass(): string
    {
        return TobaccoUnitIdentifier::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 50;
    }
}
