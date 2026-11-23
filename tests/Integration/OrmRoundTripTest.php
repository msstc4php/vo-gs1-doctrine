<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Integration;

use Composer\InstalledVersions;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Tools\SchemaTool;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\ElementStringType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\EuFmdPackCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\GtinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\RuMarkingCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\RuMarkingIdentifierType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Orm\TypeRegistrar;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Orm\Shipment;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Samples;
use MSSTC4PHP\ValueObject\Gs1\ElementString\ElementString;
use MSSTC4PHP\ValueObject\Gs1\Key\Gtin;
use MSSTC4PHP\ValueObject\Gs1\Marking\EU\FmdPackCode;
use MSSTC4PHP\ValueObject\Gs1\Marking\RU\MarkingCode;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GtinType::class)]
#[CoversClass(ElementStringType::class)]
#[CoversClass(RuMarkingCodeType::class)]
#[CoversClass(EuFmdPackCodeType::class)]
#[CoversClass(RuMarkingIdentifierType::class)]
#[UsesClass(TypeRegistrar::class)]
final class OrmRoundTripTest extends TestCase
{
    private Connection $connection;

    private EntityManager $entityManager;

    #[Override]
    protected function setUp(): void
    {
        TypeRegistrar::registerAll();

        $this->connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
        $config = new Configuration();
        $config->setMetadataDriverImpl(new AttributeDriver([__DIR__ . '/../Support/Orm']));
        $config->setProxyDir(\sys_get_temp_dir() . '/gs1-proxies');
        $config->setProxyNamespace('Gs1Proxies');
        // ORM 3.3 (the lowest supported) has no native lazy objects and falls back to its own proxies.
        if (\version_compare(InstalledVersions::getVersion('doctrine/orm') ?? '0', '3.4.0', '>=')) {
            $config->enableNativeLazyObjects(true);
        }

        $this->entityManager = new EntityManager($this->connection, $config);
        new SchemaTool($this->entityManager)->createSchema([$this->entityManager->getClassMetadata(Shipment::class)]);
    }

    public function testSchemaDeclaresTheColumnLengthsOfTheClasses(): void
    {
        $sql = \implode("\n", new SchemaTool($this->entityManager)->getCreateSchemaSql(
            [$this->entityManager->getClassMetadata(Shipment::class)],
        ));

        self::assertStringContainsString('gtin VARCHAR(14) NOT NULL', $sql);
        self::assertStringContainsString('element CLOB NOT NULL', $sql);
        self::assertStringContainsString('marking VARCHAR(255) DEFAULT NULL', $sql);
        self::assertStringContainsString('pack VARCHAR(514) DEFAULT NULL', $sql);
        self::assertStringContainsString('identifier VARCHAR(74) DEFAULT NULL', $sql);
    }

    public function testElementStringWithGroupSeparatorSurvivesAFlushAndReload(): void
    {
        $element = ElementString::from('010950600013435210ABC' . Samples::GS . '21XYZ');
        $this->entityManager->persist(new Shipment(Gtin::from('09506000134352'), $element));
        $this->entityManager->flush();
        $this->entityManager->clear();

        $shipment = $this->entityManager->getRepository(Shipment::class)->findOneBy([]);

        self::assertInstanceOf(Shipment::class, $shipment);
        self::assertTrue($element->equals($shipment->element));
        self::assertSame('09506000134352', (string) $shipment->gtin);
        self::assertSame('XYZ', $shipment->element->get('21'));
        self::assertNull($shipment->marking);
        self::assertNull($shipment->pack);
        self::assertSame(
            '(01)09506000134352(10)ABC(21)XYZ',
            $this->connection->fetchOne('SELECT element FROM shipment'),
        );
    }

    public function testControlCharactersOfMarkingAndPackCodesAreStoredLosslessly(): void
    {
        $marking = MarkingCode::from(
            '0104600439931256215Ab+cDe-fGh.J' . Samples::GS . '91EE10' . Samples::GS . '92' . Samples::RU_CODE_44,
        );
        $pack = FmdPackCode::from(Samples::IFA_PACK);
        $this->entityManager->persist(new Shipment(
            Gtin::from('09506000134352'),
            ElementString::from('(01)09506000134352'),
            $marking,
            $pack,
        ));
        $this->entityManager->flush();
        $this->entityManager->clear();

        $row = $this->connection->fetchAssociative('SELECT marking, pack FROM shipment');
        $shipment = $this->entityManager->getRepository(Shipment::class)->findOneBy([]);

        self::assertIsArray($row);
        self::assertSame((string) $marking, $row['marking']);
        self::assertSame(Samples::IFA_PACK, $row['pack']);
        self::assertStringContainsString(Samples::GS, $row['marking']);
        self::assertStringContainsString(Samples::RS . Samples::EOT, $row['pack']);
        self::assertInstanceOf(Shipment::class, $shipment);
        self::assertNotNull($shipment->marking);
        self::assertNotNull($shipment->pack);
        self::assertTrue($marking->equals($shipment->marking));
        self::assertTrue($pack->equals($shipment->pack));
        self::assertSame(Samples::IFA_PACK, $shipment->pack->value);
    }

    public function testA0xRowOfAnAmbiguousPackIdentifierHydratesAsThePack(): void
    {
        $this->connection->insert('shipment', [
            'gtin' => '09506000134352',
            'element' => '(01)09506000134352',
            'identifier' => Samples::AMBIGUOUS_PACK_IDENTIFIER,
        ]);

        $shipment = $this->entityManager->getRepository(Shipment::class)->findOneBy([]);

        self::assertInstanceOf(Shipment::class, $shipment);
        self::assertNotNull($shipment->identifier);
        self::assertTrue($shipment->identifier->isTobaccoConsumerPack());
        self::assertSame(']d1' . Samples::AMBIGUOUS_PACK_IDENTIFIER, (string) $shipment->identifier);
    }
}
