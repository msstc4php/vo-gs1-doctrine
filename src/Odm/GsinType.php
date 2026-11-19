<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gsin;
use Override;

final class GsinType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gsin';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gsin::class;
    }
}
