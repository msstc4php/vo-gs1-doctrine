<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 17;
    }
}
