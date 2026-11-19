<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 30;
    }
}
