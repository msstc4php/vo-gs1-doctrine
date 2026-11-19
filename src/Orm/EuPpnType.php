<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\Ppn;
use Override;

final class EuPpnType extends AbstractStringVoType
{
    public const string NAME = 'gs1_eu_ppn';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Ppn::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 28;
    }
}
