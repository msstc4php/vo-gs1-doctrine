<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Gsrn;
use Override;

final class GsrnType extends AbstractStringVoType
{
    public const string NAME = 'gs1_gsrn';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Gsrn::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 18;
    }
}
