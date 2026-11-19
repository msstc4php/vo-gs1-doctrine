<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 1024;
    }
}
