<?php
namespace GatewayLayerBundle\Test\Unit;

use GatewayLayerBundle\MessageGateway\TelegramGateway;
use GatewayLayerBundle\Test\Resource\HttpClientFixture\TelegramGatewayFixture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use ServiceLayerBundle\DTO\ContactNormalizer;
use ServiceLayerBundle\DTO\ContactProvider;
use ServiceLayerBundle\DTO\Factory\ContactExternalAccountNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\ContactNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\MessageNormalizerFactory;
use ServiceLayerBundle\DTO\MessageNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class TelegramGatewayTest extends KernelTestCase
{
    /** @var TelegramGatewayFixture  */
    private $telegramGatewayFixture;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->telegramGatewayFixture = new TelegramGatewayFixture();
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testSendMessage_NormalMessageAndConnectionFailed_Exception()
    {
        $mock = new MockHandler([
            new RequestException("Error Communicating with Server", new \GuzzleHttp\Psr7\Request('GET', 'test'))
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $telegramGateway = $this->factoryTelegramGateway($client, '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw');


        $telegramGateway->sendMessage('message', '345302313', []);
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testSendMessage_NormalMessageAndBadAuthToken_Exception()
    {
        $httpFixture = $this->telegramGatewayFixture->getUnauthorized();
        $mock = new MockHandler([
            new Response($httpFixture['status'], [], $httpFixture['body'])
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $telegramGateway = $this->factoryTelegramGateway($client, 'SFWSEFS');


        $telegramGateway->sendMessage('message', '345302313', []);
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testSendMessage_BadChatId_Exception()
    {
        $httpFixture = $this->telegramGatewayFixture->getSendMessageChatNotFound();
        $mock = new MockHandler([
            new Response($httpFixture['status'], [], $httpFixture['body'])
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $telegramGateway = $this->factoryTelegramGateway($client, '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw');


        $telegramGateway->sendMessage('message', '3453023133774983749823', []);
    }

    public function testSendMessage_NormalMessage_SendSuccess()
    {
        $httpFixture = $this->telegramGatewayFixture->getSendMessageRightResponse();
        $mock = new MockHandler([
            new Response($httpFixture['status'], [], $httpFixture['body'])
        ]);

        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $telegramGateway = $this->factoryTelegramGateway($client, '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw');


        $result = $telegramGateway->sendMessage('message', '3453023133774983749823', []);


        $this->assertTrue($result);
    }

    /**
     * @expectedException \GatewayLayerBundle\Exception\MessageGatewayException
     */
    public function testHandleRequest_notValidMessageData_Exception()
    {
        $mock = new MockHandler([]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $messageData = '';
        $telegramGateway = $this->factoryTelegramGateway($client, '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw');
        $request = $this->factoryRequest($messageData);


        $telegramGateway->handleRequest($request);
    }

    public function testHandleRequest_validMessageData_ArrayCollectionOfMessageNormalizer()
    {
        $httpFixture = $this->telegramGatewayFixture->getWebHookNewMessage();
        $messageData = $httpFixture['body'];

        $mock = new MockHandler([]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);

        $telegramGateway = $this->factoryTelegramGateway($client, '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw');
        $request = $this->factoryRequest($messageData);


        $DTO = $telegramGateway->handleRequest($request);
        $parsedData = $DTO->getContactProvider();
        $messageNormalizerList = $parsedData->current()->getMessageNormalizerList();


        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $DTO->getResponse());
        $this->assertInstanceOf(ContactProvider::class, $parsedData);
        $this->assertContainsOnlyInstancesOf(ContactNormalizer::class, $parsedData);
        $this->assertContainsOnlyInstancesOf(MessageNormalizer::class, $messageNormalizerList);
    }

    private function factoryTelegramGateway(Client $client, string $botToken): TelegramGateway
    {
        $contactNormalizerFactory = new ContactNormalizerFactory();
        $messageNormalizerFactory = new MessageNormalizerFactory();
        $contactExternalAccountNormalizerFactory = new ContactExternalAccountNormalizerFactory();

        return new TelegramGateway(
            $contactNormalizerFactory,
            $messageNormalizerFactory,
            $contactExternalAccountNormalizerFactory,
            $client,
            $botToken
        );
    }

    private function factoryRequest($body): Request
    {
        $request = new Request(
            ['sample-ApiToken' => 'jhvfgcwf4rtw3543453r534bgt'],
            [],
            [],
            [],
            [],
            [],
            $body
        );

        return $request;
    }
}
