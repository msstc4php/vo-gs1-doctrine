<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\Ndc12;
use Override;

final class Ndc12Type extends AbstractStringVoType
{
    public const string NAME = 'gs1_ndc12';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Ndc12::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 14;
    }
}
