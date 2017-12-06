<?php
namespace DomainLayer\Contact\ContactExternalAccount\DataFixtures;

use DomainLayer\Company\DataFixtures\LoadCompany;
use DomainLayer\Contact\DataFixtures\LoadContact;
use DomainLayer\Contact\ContactExternalAccount\ContactExternalAccount;
use DomainLayer\MessageService\MessageServiceType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContactExternalAccount extends AbstractFixture implements DependentFixtureInterface
{
    private $date = [
        [
            'Company'           => 'company.name_Марка',
            'Contact'           => 'contact.company_name_Марка.num_1',
            'MessageServiceType'=> MessageServiceType::TELEGRAM,
            'MainAccountId'     => '345302313',
            'fixtureReference'  => 'contact.company_name_Марка.num_1.MessageServiceType_'.MessageServiceType::TELEGRAM,
        ],
        [
            'Company'           => 'company.name_Марка',
            'Contact'           => 'contact.company_name_Марка.num_2',
            'MessageServiceType'=> MessageServiceType::TELEGRAM,
            'MainAccountId'     => '245302314',
            'fixtureReference'  => 'contact.company_name_Марка.num_2.MessageServiceType_'.MessageServiceType::TELEGRAM,
        ],
        [
            'Company'           => 'company.name_Марка',
            'Contact'           => 'contact.company_name_Марка.num_1',
            'MessageServiceType'=> MessageServiceType::SMS,
            'MainAccountId'     => '79247211055',
            'fixtureReference'  => 'contact.company_name_Марка.num_1.MessageServiceType_'.MessageServiceType::SMS,
        ],
        [
            'Company'           => 'company.name_Марка',
            'Contact'           => 'contact.company_name_Марка.num_1',
            'MessageServiceType'=> MessageServiceType::PHONE,
            'MainAccountId'     => '79247211055',
            'fixtureReference'  => 'contact.company_name_Марка.num_1.MessageServiceType_'.MessageServiceType::PHONE,
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach ($this->date as $companyData) {
            $company = new ContactExternalAccount(
                $this->getReference($companyData['Company']),
                $this->getReference($companyData['Contact']),
                $companyData['MessageServiceType'],
                $companyData['MainAccountId']
            );

            $manager->persist($company);
            $this->setReference($companyData['fixtureReference'], $company);
        }

        $manager->flush();
    }

    /** @return array */
    public function getDependencies()
    {
        return [
            LoadContact::class,
            LoadCompany::class
        ];
    }
}
