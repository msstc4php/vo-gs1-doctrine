<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Grai;
use Override;

final class GraiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_grai';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Grai::class;
    }
}
