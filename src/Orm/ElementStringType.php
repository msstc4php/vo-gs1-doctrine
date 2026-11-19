<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractTextVoType;
use MSSTC4PHP\ValueObject\Gs1\ElementString\ElementString;
use Override;

final class ElementStringType extends AbstractTextVoType
{
    public const string NAME = 'gs1_element_string';

    #[Override]
    protected function valueObjectClass(): string
    {
        return ElementString::class;
    }
}
