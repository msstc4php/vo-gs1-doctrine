<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Odm;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\ElementStringType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EuFmdPackCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GtinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\RuMarkingCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\RuMarkingIdentifierType;
use MSSTC4PHP\ValueObject\Gs1\ElementString\ElementString;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\FmdPackCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingIdentifier;

#[ODM\Document(collection: 'shipment')]
class Shipment
{
    #[ODM\Id]
    public ?string $id = null;

    public function __construct(
        #[ODM\Field(type: GtinType::NAME)]
        public Gtin $gtin,
        #[ODM\Field(type: ElementStringType::NAME)]
        public ElementString $element,
        #[ODM\Field(type: RuMarkingCodeType::NAME, nullable: true)]
        public ?MarkingCode $marking = null,
        #[ODM\Field(type: EuFmdPackCodeType::NAME, nullable: true)]
        public ?FmdPackCode $pack = null,
        #[ODM\Field(type: RuMarkingIdentifierType::NAME, nullable: true)]
        public ?MarkingIdentifier $identifier = null,
    ) {
    }
}
