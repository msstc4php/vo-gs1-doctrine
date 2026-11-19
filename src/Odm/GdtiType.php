<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gdti;
use Override;

final class GdtiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gdti';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gdti::class;
    }
}
