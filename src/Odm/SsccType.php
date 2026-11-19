<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Key\Sscc;
use Override;

final class SsccType extends AbstractStringVoType
{
    public const string NAME = 'gs1_sscc';

    #[Override]
    protected function valueObjectClass(): string
    {
        return Sscc::class;
    }
}
