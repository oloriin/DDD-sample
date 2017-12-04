<?php
namespace CoreDomain\Contact\DataFixtures;

use CoreDomain\Company\DataFixtures\LoadCompany;
use CoreDomain\Contact\ContactPresenceType;
use CoreDomain\Contact\Contact;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContact extends AbstractFixture implements DependentFixtureInterface
{
    private $date = [
        [
            'Company'           => 'company.name_Марка',
            'Merged'            => false,
            'Presence'          => ContactPresenceType::ONLINE,
            'fixtureReference'  => 'contact.company_name_Марка.num_1',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Merged'            => true,
            'Presence'          => ContactPresenceType::ONLINE,
            'fixtureReference'  => 'contact.company_name_Марка.num_2',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Merged'            => false,
            'Presence'          => ContactPresenceType::IDLE,
            'fixtureReference'  => 'contact.company_name_Марка.num_3',
        ],
        [
            'Company'           => 'company.name_Марка',
            'Merged'            => false,
            'Presence'          => ContactPresenceType::IDLE,
            'fixtureReference'  => 'contact.company_name_Марка.num_6',
        ],
        [
            'Company'           => 'company.name_Камень',
            'Merged'            => false,
            'Presence'          => ContactPresenceType::OFFLINE,
            'fixtureReference'  => 'contact.company_name_Камень.num_4',
        ],
        [
            'Company'           => 'company.name_Пипетка',
            'Merged'            => false,
            'Presence'          => ContactPresenceType::OFFLINE,
            'fixtureReference'  => 'contact.company_name_Пипетка.num_5',
        ],
    ];

    /** @inheritdoc */
    public function load(ObjectManager $manager)
    {
        foreach ($this->date as $contactData) {
            $contactData['Company'] = $this->getReference($contactData['Company']);
            $contact = new Contact($contactData['Company']);

            $this->fillObject($contact, $contactData);

            $manager->persist($contact);
            $this->setReference($contactData['fixtureReference'], $contact);
        }

        $manager->flush();
    }

    /**
     * @param object $object
     * @param array $template
     * @return object
     */
    private function fillObject($object, array $template)
    {
        foreach ($template as $propertyName => $propertyValue) {
            if ($propertyName == 'fixtureReference') {
                continue;
            }

            $propertyName = 'set'.$propertyName;
            $object->$propertyName($propertyValue);
        }
        return $object;
    }

    /** @inheritdoc */
    public function getDependencies()
    {
        return [
            LoadCompany::class,
        ];
    }
}
