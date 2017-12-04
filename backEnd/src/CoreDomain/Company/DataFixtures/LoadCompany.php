<?php
namespace CoreDomain\Company\DataFixtures;

use CoreDomain\Company\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCompany extends AbstractFixture
{

    private $date = [
        [
            'Name'              => 'Марка',
            'ApiToken'          => 'jhvfgcwf4rtw3543453r534bgt',
            'fixtureReference'  => 'company.name_Марка',
        ],
        [
            'Name'              => 'Камень',
            'fixtureReference'  => 'company.name_Камень',
        ],
        [
            'Name'              => 'Пипетка',
            'fixtureReference'  => 'company.name_Пипетка',
        ],

    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->date as $companyData){
            $company = new Company();
            $this->fillObject($company, $companyData);

            $manager->persist($company);
            $this->setReference($companyData['fixtureReference'], $company);
        }

        $manager->flush();
    }

    /**
     * @param object $object
     * @param array $template
     * @return object
     */
    private function fillObject($object, array $template){
        foreach ($template as $propertyName => $propertyValue){
            if($propertyName == 'fixtureReference'){
                continue;
            }

            $propertyName = 'set'.$propertyName;
            $object->$propertyName($propertyValue);
        }
        return $object;
    }

}