<?php
namespace InfrastructureGatewayBundle\Test\Integration\Message;

use DomainLayer\Company\Company;
use DomainLayer\Company\DataFixtures\LoadCompany;
use DomainLayer\Contact\Message\DataFixtures\LoadMessage;
use DomainLayer\MessageService\DataFixtures\LoadMessageService;
use DomainLayer\MessageService\MessageServiceType;
use GatewayLayerBundle\MessageGateway\TelegramGateway;
use GatewayLayerBundle\MessageGateway\VkGateway;
use InfrastructureLayerBundle\Test\IntegrationTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageGatewayFactoryTest extends KernelTestCase
{
    use IntegrationTestTrait;

    protected function setUp()
    {
        $loader = new \Doctrine\Common\DataFixtures\Loader();
        $loader->addFixture(new LoadMessageService());
        $loader->addFixture(new LoadCompany());
        $loader->addFixture(new LoadMessage());

        $this->standardSetUp($loader);
    }

    public function getMessageServiceTypes()
    {
        $types = [
            [MessageServiceType::TELEGRAM     , TelegramGateway::class],
            [MessageServiceType::VKONTAKTE    , VkGateway::class],
        ];

        return $types;
    }

    /**
     * @dataProvider getMessageServiceTypes
     */
    public function testCreateByMessageServiceType_existDataGateway_SmsGatewayInterface($type, $expectedType)
    {
        $company = $this->em->getRepository(Company::class)->findOneBy(['name' => 'Марка']);
        $messageGatewayFactory = $this->container->get('gateway.message_gateway_factory');


        $messageGateway = $messageGatewayFactory->createByMessageServiceType($company, $type);


        $this->assertInstanceOf($expectedType, $messageGateway);
    }
}
