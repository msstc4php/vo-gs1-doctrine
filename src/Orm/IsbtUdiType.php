<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\IsbtUdi;
use Override;

final class IsbtUdiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_isbt_udi';

    #[Override]
    protected function valueObjectClass(): string
    {
        return IsbtUdi::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 3116;
    }
}
