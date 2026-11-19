<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Cpid;
use Override;

final class CpidType extends AbstractStringVoType
{
    public const string NAME = 'gs1_cpid';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Cpid::class;
    }
}
