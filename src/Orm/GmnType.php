<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gmn;
use Override;

final class GmnType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gmn';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gmn::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 25;
    }
}
