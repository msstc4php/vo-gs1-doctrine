<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 1024;
    }
}
