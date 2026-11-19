<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gsrn;
use Override;

final class GsrnType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gsrn';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gsrn::class;
    }
}
