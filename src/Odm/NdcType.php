<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
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
}
