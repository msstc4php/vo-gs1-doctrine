<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gln;
use Override;

final class GlnType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gln';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gln::class;
    }
}
