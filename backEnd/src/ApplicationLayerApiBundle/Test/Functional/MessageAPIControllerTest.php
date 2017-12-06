<?php
namespace ApplicationLayerApiBundle\Test\Functional;

use Doctrine\Common\DataFixtures\Loader;
use DomainLayer\Contact\Contact;
use DomainLayer\Contact\ContactExternalAccount\DataFixtures\LoadContactExternalAccount;
use DomainLayer\Contact\DataFixtures\LoadContact;
use DomainLayer\Contact\Message\DataFixtures\LoadMessage;
use DomainLayer\Contact\Message\Message;
use DomainLayer\MessageService\MessageServiceType;
use DomainLayer\User\DataFixtures\LoadUser;
use DomainLayer\User\User;
use InfrastructureLayerBundle\Test\IntegrationTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageAPIControllerTest extends WebTestCase
{
    use IntegrationTestTrait;
    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    private $client;

    public function setUp()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadUser());
        $loader->addFixture(new LoadContact());
        $loader->addFixture(new LoadMessage());
        $loader->addFixture(new LoadContactExternalAccount());

        $this->standardSetUp($loader);

        $this->client = static::createClient();
    }

    public function testGetMessageListAction_userHasRights_messageListJson()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);
        $offset = 0;
        $limit = 50;

        /** @var Contact $contact */
        $contact = $this->container->get('infrastructure.repository.contact_external_account')->findOneBy([
            'company' => $user->getCompany(),
            'messageServiceType' => MessageServiceType::TELEGRAM,
            'mainAccountId' => '345302313'
        ])->getContact();


        $this->client->request(
            'GET',
            '/v1/contact/'.$contact->getId().'/message',
            [
                'offset' => $offset,
                'limit' => $limit,
            ],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()]
        );


        $jsonContent = $this->client->getResponse()->getContent();
        $content = json_decode($jsonContent);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(is_array($content));
        $this->assertTrue(is_object($content[0]));
        $this->assertTrue(isset($content[0]->contact));
        $this->assertTrue(isset($content[0]->messageServiceType));
        $this->assertTrue(isset($content[0]->senderAppType));
        $this->assertTrue(isset($content[0]->directionType));
        $this->assertTrue(isset($content[0]->body));
        $this->assertTrue(isset($content[0]->contactRead));
        $this->assertTrue(isset($content[0]->operatorRead));
        $this->assertTrue(isset($content[0]->createAt));
        $this->assertTrue(isset($content[0]->gatewayStatus));
    }

    public function testGetMessageListAction_userHasRightsFilterMessageServiceType_onlyPhoneMessageListJson()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);
        $offset = 0;
        $limit = 50;

        /** @var Contact $contact */
        $contact = $this->container->get('infrastructure.repository.contact_external_account')->findOneBy([
            'company' => $user->getCompany(),
            'messageServiceType' => MessageServiceType::TELEGRAM,
            'mainAccountId' => '245302314'
        ])->getContact();


        $this->client->request(
            'GET',
            '/v1/contact/'.$contact->getId().'/message',
            [
                'offset' => $offset,
                'limit' => $limit,
                'messageServiceType' => MessageServiceType::PHONE,
            ],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()]
        );


        $jsonContent = $this->client->getResponse()->getContent();
        $content = json_decode($jsonContent);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(is_array($content));
        foreach ($content as $message) {
            $this->assertSame(MessageServiceType::PHONE, $message->messageServiceType);
        }
    }

    public function testPostMessageAction_userHasRightsNewMessageJson_sendedMessageJson()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);

        /** @var Contact $contact */
        $contact = $this->container->get('infrastructure.repository.contact_external_account')->findOneBy([
            'company' => $user->getCompany(),
            'messageServiceType' => MessageServiceType::TELEGRAM,
            'mainAccountId' => '345302313'
        ])->getContact();

        $messageData = [
            'body' => 'Привет опетатор Ырчапор',
            'createAt' => 1489147200,
            'messageServiceType' => MessageServiceType::TELEGRAM,
            'senderAppType' => 'App',
            'directionType' => 'In',
            'contactRead' => false,
            'operatorRead' => true,
        ];


        $this->client->request(
            'POST',
            '/v1/contact/'.$contact->getId().'/message',
            [],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()],
            json_encode($messageData)
        );


        $jsonContent = $this->client->getResponse()->getContent();
        $content = json_decode($jsonContent);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(isset($content->id));
    }

    public function testPutPostMessageAction_userHasRightsMessageJson_changedMessageJson(){
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);

        /** @var Contact $contact */
        $contact = $this->container->get('infrastructure.repository.contact_external_account')->findOneBy([
            'company' => $user->getCompany(),
            'messageServiceType' => MessageServiceType::TELEGRAM,
            'mainAccountId' => '345302313'
        ])->getContact();

        $this->client->request(
            'GET',
            '/v1/contact/'.$contact->getId().'/message',
            ['offset' => 0, 'limit' => 50],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()]
        );
        $jsonContent = $this->client->getResponse()->getContent();
        $content = json_decode($jsonContent);

        $changeMessage = $content[2];
        $changeMessage->contactRead = true;


        $this->client->request(
            'PUT',
            '/v1/contact/'.$contact->getId().'/message/'.$changeMessage->id,
            [],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()],
            json_encode($changeMessage)
        );


        /** @var Message $actualMessage */
        $actualMessage = $this->container->get('infrastructure.repository.message')
            ->findOneBy(['contact' => $contact, 'id' => $changeMessage->id]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($actualMessage->getContactRead());
    }
}
