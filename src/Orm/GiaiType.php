<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Giai;
use Override;

final class GiaiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_giai';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Giai::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 30;
    }
}
