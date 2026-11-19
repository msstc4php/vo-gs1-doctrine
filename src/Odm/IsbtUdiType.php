<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
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
}
