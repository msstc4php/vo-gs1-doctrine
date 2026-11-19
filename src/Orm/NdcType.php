<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\Ndc;
use Override;

final class NdcType extends AbstractStringVoType
{
    public const string NAME = 'gs1_ndc';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Ndc::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 13;
    }
}
