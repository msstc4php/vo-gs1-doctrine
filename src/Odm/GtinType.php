<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
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
}
