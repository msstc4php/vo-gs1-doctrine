<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm;

use MSSTC4PHP\ValueObject\Doctrine\Odm\AbstractStringVoType;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingCode;
use Override;

final class RuMarkingCodeType extends AbstractStringVoType
{
    public const string NAME = 'gs1_ru_marking_code';

    #[Override]
    protected function valueObjectClass(): string
    {
        return MarkingCode::class;
    }
}
