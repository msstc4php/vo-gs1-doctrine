<?php

declare(strict_types=1);

namespace MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Integration;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoException;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\ElementStringType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\EuFmdPackCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\GtinType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\RuMarkingCodeType;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Odm\TypeRegistrar;
use MSSTC4PHP\ValueObject\Gs1\Doctrine\Tests\Support\Odm\Shipment;
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
#[UsesClass(TypeRegistrar::class)]
final class OdmRoundTripTest extends TestCase
{
    private const string DATABASE = 'vo_gs1_doctrine_test';

    private DocumentManager $documentManager;

    private Client $client;

    private bool $connected = false;

    #[Override]
    protected function setUp(): void
    {
        $uri = \getenv('MONGODB_URI');
        $server = $uri !== false && $uri !== '' ? $uri : 'mongodb://127.0.0.1:27017';
        $this->client = new Client($server, ['serverSelectionTimeoutMS' => 1000]);

        try {
            $this->client->selectDatabase('admin')->command(['ping' => 1]);
        } catch (MongoException) {
            self::markTestSkipped('MongoDB is not reachable; set MONGODB_URI to run the ODM integration test.');
        }

        $this->connected = true;
        TypeRegistrar::registerAll();
        $this->client->dropDatabase(self::DATABASE);

        $config = new Configuration();
        $config->setDefaultDB(self::DATABASE);
        $config->setMetadataDriverImpl(new AttributeDriver([__DIR__ . '/../Support/Odm']));
        $config->setProxyDir(\sys_get_temp_dir() . '/gs1-proxies');
        $config->setProxyNamespace('Gs1Proxies');
        $config->setHydratorDir(\sys_get_temp_dir() . '/gs1-hydrators');
        $config->setHydratorNamespace('Gs1Hydrators');
        $config->setAutoGenerateProxyClasses(Configuration::AUTOGENERATE_EVAL);
        $config->setAutoGenerateHydratorClasses(Configuration::AUTOGENERATE_EVAL);

        $this->documentManager = DocumentManager::create($this->client, $config);
    }

    #[Override]
    protected function tearDown(): void
    {
        if ($this->connected) {
            $this->client->dropDatabase(self::DATABASE);
        }
    }

    public function testElementStringWithGroupSeparatorSurvivesAFlushAndReload(): void
    {
        $element = ElementString::from('010950600013435210ABC' . Samples::GS . '21XYZ');
        $this->documentManager->persist(new Shipment(Gtin::from('09506000134352'), $element));
        $this->documentManager->flush();
        $this->documentManager->clear();

        $shipment = $this->documentManager->getRepository(Shipment::class)->findOneBy([]);

        self::assertInstanceOf(Shipment::class, $shipment);
        self::assertTrue($element->equals($shipment->element));
        self::assertSame('09506000134352', (string) $shipment->gtin);
        self::assertSame('XYZ', $shipment->element->get('21'));
        self::assertNull($shipment->marking);
        self::assertNull($shipment->pack);
    }

    public function testControlCharactersOfMarkingAndPackCodesAreStoredLosslessly(): void
    {
        $marking = MarkingCode::from(
            '0104600439931256215Ab+cDe-fGh.J' . Samples::GS . '91EE10' . Samples::GS . '92' . Samples::RU_CODE_44,
        );
        $pack = FmdPackCode::from(Samples::IFA_PACK);
        $this->documentManager->persist(new Shipment(
            Gtin::from('09506000134352'),
            ElementString::from('(01)09506000134352'),
            $marking,
            $pack,
        ));
        $this->documentManager->flush();
        $this->documentManager->clear();

        $raw = $this->client->selectCollection(
            self::DATABASE,
            'shipment',
        )->findOne([], ['typeMap' => ['root' => 'array', 'document' => 'array']]);
        $shipment = $this->documentManager->getRepository(Shipment::class)->findOneBy([]);

        self::assertIsArray($raw);
        self::assertSame((string) $marking, $raw['marking']);
        self::assertSame(Samples::IFA_PACK, $raw['pack']);
        self::assertInstanceOf(Shipment::class, $shipment);
        self::assertNotNull($shipment->marking);
        self::assertNotNull($shipment->pack);
        self::assertTrue($marking->equals($shipment->marking));
        self::assertTrue($pack->equals($shipment->pack));
        self::assertSame(Samples::IFA_PACK, $shipment->pack->value);
    }
}
