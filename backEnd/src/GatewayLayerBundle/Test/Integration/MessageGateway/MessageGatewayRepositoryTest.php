<?php

namespace InfrastructureGatewayBundle\Test\Integration\Message;

use Doctrine\Common\DataFixtures\Loader;
use DomainLayer\Company\Company;
use DomainLayer\Company\DataFixtures\LoadCompany;
use DomainLayer\MessageService\DataFixtures\LoadMessageService;
use DomainLayer\MessageService\MessageServiceType;
use GatewayLayerBundle\MessageGateway\MessageGatewayInterface;
use GatewayLayerBundle\MessageGateway\TelegramGateway;
use InfrastructureLayerBundle\Test\IntegrationTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageGatewayRepositoryTest extends KernelTestCase
{
    use IntegrationTestTrait;

    protected function setUp()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadMessageService());
        $loader->addFixture(new LoadCompany());

        $this->standardSetUp($loader);
    }


    public function testGetGateway_getTwoTelegramGateway_IdenticalTelegramGateways()
    {
        $company = $this->em->getRepository(Company::class)->findOneBy(['name' => 'Марка']);
        $messageGatewayRepository = $this->container->get('gateway.message_gateway_repository');


        $telegramGatewayFirst = $messageGatewayRepository->getGateway($company, MessageServiceType::TELEGRAM);
        $telegramGatewaySecond = $messageGatewayRepository->getGateway($company, MessageServiceType::TELEGRAM);


        $this->assertInstanceOf(MessageGatewayInterface::class, $telegramGatewayFirst);
        $this->assertInstanceOf(TelegramGateway::class, $telegramGatewayFirst);
        $this->assertTrue(($telegramGatewayFirst === $telegramGatewaySecond));
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testGetGateway_notExistSmsGatewayData_Exception()
    {
        $company = $this->em->getRepository(Company::class)->findOneBy(['name' => 'Камень']);
        $messageGatewayRepository = $this->container->get('gateway.message_gateway_repository');


        $messageGatewayRepository->getGateway($company, MessageServiceType::UNDEFINED);
    }
}
