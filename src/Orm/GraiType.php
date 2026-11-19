<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 30;
    }
}
