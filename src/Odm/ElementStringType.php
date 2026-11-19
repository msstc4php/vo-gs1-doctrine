<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\ElementString\ElementString;
use Override;

final class ElementStringType extends AbstractStringVoType
{
    public const string NAME = 'gs1_element_string';

    #[Override]
    protected function valueObjectClass(): string
    {
        return ElementString::class;
    }
}
