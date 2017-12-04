<?php
namespace CoreDomain\MessageService\DataFixtures;

use CoreDomain\Company\DataFixtures\LoadCompany;
use CoreDomain\MessageService\MessageService;
use CoreDomain\MessageService\MessageServiceGatewayType;
use CoreDomain\MessageService\MessageServiceType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMessageService extends AbstractFixture implements DependentFixtureInterface
{
    private $templateList = [
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::SMS,
            'GatewayType'       => MessageServiceGatewayType::SMSPROFI,
            'ConnectionData'    => ['login' => 'sintezmanager', 'password' => 'Sintezpassword'],
            'MainIdentifier'    => 'sintezmanager',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_smsprofi',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::TELEGRAM,
            'GatewayType'       => MessageServiceGatewayType::TELEGRAM,
            'ConnectionData'    => ['authToken' => '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw' ],
            'MainIdentifier'    => '385483367',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_telegram',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::EMAIL,
            'GatewayType'       => MessageServiceGatewayType::CARROT_EMAIL,
            'ConnectionData'    => ['appId' => '123', 'authToken' => '321'],
            'MainIdentifier'    => '123',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_carrotEmail',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::WEB_CHAT,
            'GatewayType'       => MessageServiceGatewayType::CARROT_WEB_CHAT,
            'ConnectionData'    => ['appId' => '123', 'authToken' => '321'],
            'MainIdentifier'    => '123',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_carrotCarrotWebChat',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::VKONTAKTE,
            'GatewayType'       => MessageServiceGatewayType::VKONTAKTE,
            'ConnectionData'    => ['confirmationCode' => '45a930f0', 'authToken' => 'de7528378dbe9c444031a14b535073149e8e629cf7192c2bb6d8b6c96df32fa70edb9edd86723ce2466a5'],
            'MainIdentifier'    => 'vldcorg',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_vkontakte',
        ],
        [
            'Company'           => 'company.name_Пипетка',
            'Type'              => MessageServiceType::SMS,
            'GatewayType'       => MessageServiceGatewayType::SMSPROFI,
            'ConnectionData'    => ['login' => '123', 'password' => '321'],
            'MainIdentifier'    => '123',
            'fixtureReference'  => 'messageService.company_Пипетка.gatewayType_smsprofi',
        ],
        [
            'Company'           => 'company.name_Пипетка',
            'Type'              => MessageServiceType::TELEGRAM,
            'GatewayType'       => MessageServiceGatewayType::TELEGRAM,
            'ConnectionData'    => ['authToken' => '385483367:AAEnaKB8im4JpKZUhW3gx-OMyYbGgmflUjw' ],
            'MainIdentifier'    => '385483367',
            'fixtureReference'  => 'messageService.name_Пипетка.gatewayType_telegram',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::SKYPE,
            'GatewayType'       => MessageServiceGatewayType::SKYPE,
            'ConnectionData'    => [
                'serverUrl' => 'https://test.loc/skype',
                'botId'     => '751d047l4566adijgc',
                'botName'   => 'edylcrmbot',
                'botToken'  => 'eefNdskLimqUNECnWfb0ZrT',
            ],
            'MainIdentifier'    => '751d047l4566adijgc',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_skype',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::FACEBOOK,
            'GatewayType'       => MessageServiceGatewayType::FACEBOOK,
            'ConnectionData'    => [
                'pageId'       => '350575505382249',
                'accessToken'  => 'EAACOt0Q7N4oBAJm8BV7DlpAwMPt9maJd67eMk5h5DiZAYmRtH1nkjsmqOgFe0WdSSnaP2ZAVp1cxc8ELEWeYaZB4MAO0UhvzNzCiTdBGRJx9Vn3aAdVZBlZBH2LP1KZAsahkyiyWp9BHwNuxJsYgcshTIFZBO0v5qS8HrdCy5Q9u8UbjrqF2s4X',
            ],
            'MainIdentifier'    => '350575505382249',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_facebook',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Type'              => MessageServiceType::INSTAGRAM_DIRECT,
            'GatewayType'       => MessageServiceGatewayType::INSTAGRAM_DIRECT,
            'ConnectionData'    => [
                'password'       => 'Ie1QjXm1kCZ6',
            ],
            'MainIdentifier'    => '+79247211055',
            'fixtureReference'  => 'messageService.company_Марка.gatewayType_instagram',
        ],
        [
            'Company'           => 'company.name_Пипетка',
            'Type'              => MessageServiceType::FACEBOOK,
            'GatewayType'       => MessageServiceGatewayType::FACEBOOK,
            'ConnectionData'    => [
                'pageId'       => '350575534687689',
                'accessToken'  => '231d04776976adijgc',
            ],
            'MainIdentifier'    => '350575534687689',
            'fixtureReference'  => 'messageService.company_Пипетка.gatewayType_facebook',
        ],
    ];


    /** @param ObjectManager $manager */
    public function load(ObjectManager $manager)
    {
        foreach ($this->templateList as $messageServiceTemplate) {
            $messageService = new MessageService(
                $this->getReference($messageServiceTemplate['Company']),
                $messageServiceTemplate['Type'],
                $messageServiceTemplate['GatewayType'],
                $messageServiceTemplate['ConnectionData'],
                $messageServiceTemplate['MainIdentifier']
            );

            $manager->persist($messageService);
            $this->setReference($messageServiceTemplate['fixtureReference'], $messageService);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadCompany::class,
        ];
    }
}
