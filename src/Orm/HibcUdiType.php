<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\HibcUdi;
use Override;

final class HibcUdiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_hibc_udi';

    #[Override]
    protected function valueObjectClass(): string
    {
        return HibcUdi::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 113;
    }
}
