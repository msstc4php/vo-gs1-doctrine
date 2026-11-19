<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Giai;
use Override;

final class GiaiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_giai';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Giai::class;
    }
}
