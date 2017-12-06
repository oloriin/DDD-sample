<?php
namespace ApplicationLayerApiBundle\Test\Functional;

use Doctrine\Common\DataFixtures\Loader;
use DomainLayer\Company\Company;
use DomainLayer\Company\DataFixtures\LoadCompany;
use DomainLayer\User\DataFixtures\LoadUser;
use DomainLayer\User\User;
use InfrastructureLayerBundle\Test\IntegrationTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompanyAPIControllerTest extends WebTestCase
{
    use IntegrationTestTrait;

    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    private $client;

    public function setUp()
    {
        $loader = new Loader();
        $loader->addFixture(new LoadUser());
        $loader->addFixture(new LoadCompany());

        $this->standardSetUp($loader);

        $this->client = static::createClient();
    }

    public function testGetCompanyAction_userHasRights_companyJson()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);

        $this->client->request(
            'GET',
            '/v1/company/'.$user->getCompany()->getId(),
            [],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()]
        );


        $jsonContent = $this->client->getResponse()->getContent();
        $content = json_decode($jsonContent);


        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($jsonContent);
        $this->assertSame($user->getCompany()->getId(), $content->id);
        $this->assertSame($user->getCompany()->getName(), $content->name);
    }

    public function testPutCompanyAction_userHasRights_changeCompanyJson()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'odin@valhalla.sky']);

        $expectCompanyArray = [
            'id'    => $user->getCompany()->getId(),
            'name'  => 'dkvjndkfjvnd..iiii...aaa',
        ];


        $this->client->request(
            'PUT',
            '/v1/company/'.$expectCompanyArray['id'],
            [],
            [],
            ['HTTP_sample-ApiToken' => $user->getApiToken()],
            json_encode($expectCompanyArray)
        );


        $this->em->clear();
        $actualCompany = $this->em->getRepository(Company::class)->find($user->getCompany()->getId());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertInstanceOf(Company::class, $actualCompany);
        $this->assertSame($expectCompanyArray['id'], $actualCompany->getId());
        $this->assertSame($expectCompanyArray['name'], $actualCompany->getName());
    }
}
