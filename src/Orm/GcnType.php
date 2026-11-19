<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gcn;
use Override;

final class GcnType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gcn';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gcn::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 25;
    }
}
