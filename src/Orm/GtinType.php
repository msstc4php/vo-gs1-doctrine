<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use Override;

final class GtinType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gtin';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gtin::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 14;
    }
}
