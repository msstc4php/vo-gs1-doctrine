<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
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
}
