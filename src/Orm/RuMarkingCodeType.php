<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm;

use MSSTC4PHP\ValueObject\Doctrine\Orm\AbstractStringVoType;
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

    #[Override]
    protected function columnLength(): int
    {
        return 255;
    }
}
