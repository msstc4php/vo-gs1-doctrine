<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Ginc;
use Override;

final class GincType extends AbstractStringVoType
{
    public const string NAME = 'gs1_ginc';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Ginc::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 30;
    }
}
