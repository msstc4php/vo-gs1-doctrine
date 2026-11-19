<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Orm;

use Doctrine\ORM\Mapping as ORM;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\ElementStringType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EuFmdPackCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GtinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\RuMarkingCodeType;
use MSSTC4PHP\ValueObject\Gs1\ElementString\ElementString;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\FmdPackCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingCode;

#[ORM\Entity]
#[ORM\Table(name: 'shipment')]
class Shipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    public function __construct(
        #[ORM\Column(type: GtinType::NAME)]
        public Gtin $gtin,
        #[ORM\Column(type: ElementStringType::NAME)]
        public ElementString $element,
        #[ORM\Column(type: RuMarkingCodeType::NAME, nullable: true)]
        public ?MarkingCode $marking = null,
        #[ORM\Column(type: EuFmdPackCodeType::NAME, nullable: true)]
        public ?FmdPackCode $pack = null,
    ) {
    }
}
