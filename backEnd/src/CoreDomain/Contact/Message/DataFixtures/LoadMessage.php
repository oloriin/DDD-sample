<?php
namespace CoreDomain\Contact\Message\DataFixtures;

use CoreDomain\Contact\DataFixtures\LoadContact;
use CoreDomain\Contact\Message\Message;
use CoreDomain\Contact\Message\MessageDirectionType;
use CoreDomain\Contact\Message\MessageSenderAppType;
use CoreDomain\MessageService\DataFixtures\LoadMessageService;
use CoreDomain\MessageService\MessageServiceType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMessage extends AbstractFixture implements DependentFixtureInterface
{
    private $templateList = [
        [
            'Contact'           => 'contact.company_name_Марка.num_1',
            'MessageServiceType'=> MessageServiceType::SMS,
            'SenderAppType'     => MessageSenderAppType::APP,
            'DirectionType'     => MessageDirectionType::IN,
            'Body'              => 'Привет опетатор',
            'ContactRead'       => true,
            'OperatorRead'      => true,
            'CreateAt'          => '2017-03-10 12:00:00',
            'fixtureReference'  => 'message.contactnum_1.fixtureId_1',
        ],
        [
            'Contact'           => 'contact.company_name_Марка.num_1',
            'MessageServiceType'=> MessageServiceType::SMS,
            'SenderAppType'     => MessageSenderAppType::WEB_PANEL,
            'DirectionType'     => MessageDirectionType::OUT,
            'Body'              => 'Привет контакт',
            'ContactRead'       => true,
            'OperatorRead'      => true,
            'CreateAt'          => '2017-03-10 13:00:00',
            'fixtureReference'  => 'message.contactnum_1.fixtureId_2',
        ],
        [
            'Contact'           => 'contact.company_name_Марка.num_1',
            'MessageServiceType'=> MessageServiceType::SMS,
            'SenderAppType'     => MessageSenderAppType::APP,
            'DirectionType'     => MessageDirectionType::IN,
            'Body'              => 'Есть чё?',
            'ContactRead'       => false,
            'OperatorRead'      => true,
            'CreateAt'          => '2017-03-10 14:00:00',
            'fixtureReference'  => 'message.contactnum_1.fixtureId_3',
        ],
        [
            'Contact'           => 'contact.company_name_Марка.num_2',
            'MessageServiceType'=> MessageServiceType::SMS,
            'SenderAppType'     => MessageSenderAppType::APP,
            'DirectionType'     => MessageDirectionType::IN,
            'Body'              => 'Привет',
            'ContactRead'       => true,
            'OperatorRead'      => false,
            'CreateAt'          => '2017-03-10 14:00:00',
            'fixtureReference'  => 'message.contactnum_2.fixtureId_4',
        ],
        [
            'Contact'           => 'contact.company_name_Марка.num_2',
            'MessageServiceType'=> MessageServiceType::PHONE,
            'SenderAppType'     => MessageSenderAppType::APP,
            'DirectionType'     => MessageDirectionType::IN,
            'Body'              => 'Url to phone call mp3',
            'ContactRead'       => true,
            'OperatorRead'      => false,
            'CreateAt'          => '2017-03-10 15:00:00',
            'fixtureReference'  => 'message.contactnum_2.fixtureId_5',
        ],
        [
            'Contact'           => 'contact.company_name_Марка.num_2',
            'MessageServiceType'=> MessageServiceType::PHONE,
            'SenderAppType'     => MessageSenderAppType::APP,
            'DirectionType'     => MessageDirectionType::OUT,
            'Body'              => 'Url to phone call mp3',
            'ContactRead'       => true,
            'OperatorRead'      => true,
            'CreateAt'          => '2017-03-10 16:00:00',
            'fixtureReference'  => 'message.contactnum_2.fixtureId_6',
        ],
    ];

    /** @param ObjectManager $manager */
    public function load(ObjectManager $manager)
    {
        foreach ($this->templateList as $template) {
            $template['Contact'] = $this->getReference($template['Contact']);
            $template['CreateAt'] = new \DateTime($template['CreateAt']);

            $message = new Message(
                $template['Contact'],
                $template['Body'],
                $template['CreateAt'],
                $template['MessageServiceType'],
                $template['SenderAppType'],
                $template['DirectionType'],
                $template['ContactRead'],
                $template['OperatorRead']
            );
//            $this->fillObject($message, $template);

            $this->addReference($template['fixtureReference'], $message);
            $manager->persist($message);
        }
        $manager->flush();
    }

    /** @return array */
    public function getDependencies()
    {
        return [
            LoadContact::class,
            LoadMessageService::class,
        ];
    }
}