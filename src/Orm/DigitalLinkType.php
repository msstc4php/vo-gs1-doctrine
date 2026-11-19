<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\DigitalLink\DigitalLink;
use Override;

final class DigitalLinkType extends AbstractStringVoType
{
    public const string NAME = 'gs1_digital_link';

    #[Override]
    protected function valueObjectClass(): string
    {
        return DigitalLink::class;
    }

    #[Override]
    protected function columnLength(): int
    {
        return 14178;
    }
}
