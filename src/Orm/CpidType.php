<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 30;
    }
}
