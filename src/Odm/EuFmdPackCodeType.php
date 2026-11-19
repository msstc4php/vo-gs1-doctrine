<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\FmdPackCode;
use Override;

final class EuFmdPackCodeType extends AbstractStringVoType
{
    public const string NAME = 'gs1_eu_fmd_pack_code';

    #[Override]
    protected function valueObjectClass(): string
    {
        return FmdPackCode::class;
    }
}
