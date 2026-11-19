<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Udi\BasicUdiDi\IccbbaBasicUdiDi;
use Override;

final class IccbbaBasicUdiDiType extends AbstractStringVoType
{
    public const string NAME = 'gs1_iccbba_basic_udi_di';

    #[Override]
    protected function valueObjectClass(): string
    {
        return IccbbaBasicUdiDi::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 18;
    }
}
