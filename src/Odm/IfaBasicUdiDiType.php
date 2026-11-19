<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\IfaBasicUdiDi;
use Override;

final class IfaBasicUdiDiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_ifa_basic_udi_di';

    #[Override]
    protected function valueObjectClass(): string
    {
        return IfaBasicUdiDi::class;
    }
}
