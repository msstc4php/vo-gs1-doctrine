<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractTextVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\Gs1Udi;
use Override;

final class Gs1UdiType extends AbstractTextVoType
{
    public const string NAME = 'gs1_udi';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gs1Udi::class;
    }
}
