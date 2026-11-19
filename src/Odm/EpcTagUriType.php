<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Epc\EpcTagUri;
use Override;

final class EpcTagUriType extends AbstractStringVoType
{
    public const string NAME = 'gs1_epc_tag_uri';

    #[Override]
    protected function valueObjectClass(): string
    {
        return EpcTagUri::class;
    }
}
