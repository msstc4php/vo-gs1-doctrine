<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\TobaccoUnitIdentifierWithoutTimeStamp;
use Override;

final class EuTobaccoUnitIdentifierWithoutTimeStampType extends AbstractStringVoType
{
    public const string NAME = 'gs1_eu_tobacco_unit_identifier_without_time_stamp';

    #[Override]
    protected function valueObjectClass(): string
    {
        return TobaccoUnitIdentifierWithoutTimeStamp::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 42;
    }
}
