<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Epc\EpcPureIdentityUri;
use Override;

final class EpcPureIdentityUriType extends AbstractStringVoType
{
    public const string NAME = 'gs1_epc_pure_identity_uri';

    #[Override]
    protected function valueObjectClass(): string
    {
        return EpcPureIdentityUri::class;
    }
}
