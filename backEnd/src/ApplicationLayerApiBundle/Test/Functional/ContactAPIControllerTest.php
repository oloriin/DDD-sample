<?php
namespace ApplicationLayerApiBundle\Test\Functional;

use Doctrine\Common\DataFixtures\Loader;
use DomainLayer\Contact\ContactExternalAccount\DataFixtures\LoadContactExternalAccount;
use DomainLayer\Contact\ContactPresenceType;
use DomainLayer\Contact\DataFixtures\LoadContact;
use DomainLayer\Contact\Message\DataFixtures\LoadMessage;
use DomainLayer\User\DataFixtures\LoadUser;
use DomainLayer\User\User;
use InfrastructureLayerBundle\Test\IntegrationTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactAPIControllerTest extends WebTestCase
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

    public function tearDown()
    {
        $this->em->close();
        $this->em = null;
        $this->container = null;
        parent::tearDown();
    }

    public function testGetContactList_userHasRightsAndPresenceFilter_onlyOnlineContactListJson()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);

        $offset = 0;
        $limit = 50;


        $this->client->request(
            'GET',
            '/v1/contact',
            [
                'presence' => ContactPresenceType::ONLINE,
                'offset' => $offset,
                'limit' => $limit,
            ],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()]
        );


        $jsonContent = $this->client->getResponse()->getContent();
        $content = json_decode($jsonContent);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        foreach ($content as $contact) {
            $this->assertSame(ContactPresenceType::ONLINE, $contact->presence);
        }
    }
}
