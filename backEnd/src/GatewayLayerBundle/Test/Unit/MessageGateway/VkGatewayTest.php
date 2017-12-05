<?php
namespace GatewayLayerBundle\Test\Unit;

use DomainLayer\MessageService\MessageServiceGatewayType;
use GatewayLayerBundle\Test\Resource\HttpClientFixture\VkGatewayFixture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GatewayLayerBundle\MessageGateway\VkGateway;
use ServiceLayerBundle\DTO\ContactNormalizer;
use ServiceLayerBundle\DTO\ContactProvider;
use ServiceLayerBundle\DTO\Factory\ContactExternalAccountNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\ContactNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\MessageNormalizerFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VkGatewayTest extends KernelTestCase
{
    public function testGetMessageServiceGatewayType_Get_RightGatewayType()
    {
        $mock = new MockHandler([]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $vkGateway = $this->factoryVkGateway($client, '', '');

        $actualGatewayType = $vkGateway->getMessageServiceGatewayType();

        $this->assertSame(MessageServiceGatewayType::VKONTAKTE, $actualGatewayType);
    }

    public function testHandleRequest_MessageRequestData_True()
    {
        $mock = new MockHandler([]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $vkGateway = $this->factoryVkGateway($client, '123', '123');
        $request = new \Symfony\Component\HttpFoundation\Request(
            ['SINTEZ-ApiToken' => 'jhvfgcwf4rtw3543453r534bgt'],
            [],
            [],
            [],
            [],
            [],
            VkGatewayFixture::$callbackMessage
        );


        $resultDTO = $vkGateway->handleRequest($request);


        $contactProvider = $resultDTO->getContactProvider();
        $contactNormalizer = $contactProvider->current();
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $resultDTO->getResponse());
        $this->assertInstanceOf(ContactProvider::class, $contactProvider);
        $this->assertContainsOnlyInstancesOf(ContactNormalizer::class, $contactProvider);
        $this->assertCount(1, $contactNormalizer->getContactExternalAccountNormalizerList());
        $this->assertCount(1, $contactNormalizer->getMessageNormalizerList());
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testSendMessage_BadApiKey_Exception()
    {
        $mock = new MockHandler([
            new Response(VkGatewayFixture::$badApiToken['status'], [], VkGatewayFixture::$badApiToken['body'])
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $vkGateway = $this->factoryVkGateway(
            $client,
            'de7528378dbe9c444031a14b535073149e8e629cf7192c2bb6d8b6c96df32fa70edb9edd86723ce2466a5',
            '123'
        );


        $vkGateway->sendMessage('message', '134416935', []);
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testSendMessage_NormalMessageAndConnectionFailed_Exception()
    {
        $mock = new MockHandler([
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $telegramGateway = $this->factoryVkGateway(
            $client,
            'de7528378dbe9c444031a14b535073149e8e629cf7192c2bb6d8b6c96df32fa70edb9edd86723ce2466a5',
            '123'
        );


        $telegramGateway->sendMessage('message', '134416935', []);
    }

    public function testSendMessage_NormalMessage_SendSuccess()
    {
        $mock = new MockHandler([
            new Response(
                VkGatewayFixture::$sendMessageSuccessResponse['status'],
                [],
                VkGatewayFixture::$sendMessageSuccessResponse['body']
            )
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $vkGateway = $this->factoryVkGateway(
            $client,
            'de7528378dbe9c444031a14b535073149e8e629cf7192c2bb6d8b6c96df32fa70edb9edd86723ce2466a5',
            '123'
        );


        $result = $vkGateway->sendMessage('message', '134416935', []);


        $this->assertTrue($result);
    }

    private function factoryVkGateway(Client $client, string $apiToken, string $confirmationCode): VkGateway
    {
        $contactNormalizerFactory = new ContactNormalizerFactory();
        $messageNormalizerFactory = new MessageNormalizerFactory();
        $contactExternalAccountNormalizerFactory = new ContactExternalAccountNormalizerFactory();

        return new VkGateway(
            $contactNormalizerFactory,
            $messageNormalizerFactory,
            $contactExternalAccountNormalizerFactory,
            $client,
            $apiToken,
            $confirmationCode
        );
    }
}
