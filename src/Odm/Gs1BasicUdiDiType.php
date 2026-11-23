<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\Gs1BasicUdiDi;
use Override;

final class Gs1BasicUdiDiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gs1_basic_udi_di';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gs1BasicUdiDi::class;
    }
}
