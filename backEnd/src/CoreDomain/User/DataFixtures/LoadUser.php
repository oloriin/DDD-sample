<?php
namespace CoreDomain\User\DataFixtures;

use CoreDomain\Company\DataFixtures\LoadCompany;
use CoreDomain\User\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUser extends AbstractFixture implements DependentFixtureInterface
{
    private $templateList = [
        [
            'Company'               => 'company.name_Марка',
            'Username'              => 'tor@valhalla.sky',
            'UsernameCanonical'     => 'tor@valhalla.sky',
            'Email'                 => 'tor@valhalla.sky',
            'EmailCanonical'        => 'tor@valhalla.sky',
            'Phone'                 => '+79247211055',
            'Enabled'               => true,
            'Salt'                  => '4JPad/5eus2hq9mZuIKnEhOP7G4EPUQWLUmKHuLnufs',
            'Password'              => '3b0bqA/mmCoPfieH1rGXv7OJfKKggjSkDib49qZsAcjXLYtGA95Ix0/7o0Q1H/a94UzKPGKBsKUePm6NmtVvBw==',
            'LastLogin'             => '2017-06-19 04:12:31',
            'ConfirmationToken'     => null,
            'PasswordRequestedAt'   => null,
            'Roles'                 => [User::ROLE_AGENCY_MANAGER],
            'fixtureReference'      => 'user.Tor.company.name_Марка',
            'realPassword'          => '12345',
            'apiToken'              => 'd7ae113ccf61ae1bc8db96e0d0b047b6',
            'avatar'                => __DIR__.'/avatar_1.png',
        ],
        [
            'Company'               => 'company.name_Марка',
            'Username'              => 'odin@valhalla.sky',
            'UsernameCanonical'     => 'odin@valhalla.sky',
            'Email'                 => 'odin@valhalla.sky',
            'EmailCanonical'        => 'odin@valhalla.sky',
            'Phone'                 => '+79247268455',
            'Enabled'               => true,
            'Salt'                  => 'yUwZYFSUMAb4snZswsMWIyOed40yr8I4ZJbffsjQMjQ',
            'Password'              => '4GELNm1Ip7z89lZvJ3nv3fM60MG1Y7tPl/zlUY6JPqUn7SohoIFWQnlJIHbHyA+gR3ilQ/m3gWMdStfaaWNbWA==',
            'LastLogin'             => '2017-06-19 04:18:29',
            'ConfirmationToken'     => null,
            'PasswordRequestedAt'   => null,
            'Roles'                 => [User::ROLE_SUPER_ADMIN],
            'fixtureReference'      => 'user.Odin.company.name_Марка',
            'realPassword'          => '54321',
                'apiToken'              => 'a1f7ae44ee5fb59a9e959fc3e128c93c',
            'avatar'                => __DIR__.'/avatar_2.png',
        ],
        [
            'Company'               => 'company.name_Марка',
            'Username'              => 'frigg@valhalla.sky',
            'UsernameCanonical'     => 'frigg@valhalla.sky',
            'Email'                 => 'frigg@valhalla.sky',
            'EmailCanonical'        => 'frigg@valhalla.sky',
            'Phone'                 => '+79247744955',
            'Enabled'               => true,
            'Salt'                  => 'bPlCbOHWY1KlW/6iTGA/SxwOaiFVfqYg1Hmei6UjXwY',
            'Password'              => '31f8O0yx1up8jwtdBLEx1H4AQwICRi0RrYJntAPgZ/9a1Zvx31XBP1YPYfWnW5s8nSzYCLVID/HhrIghcessYw==',
            'LastLogin'             => '2017-06-19 04:20:02',
            'ConfirmationToken'     => null,
            'PasswordRequestedAt'   => null,
            'Roles'                 => [User::ROLE_AGENCY_ADMIN],
            'fixtureReference'      => 'user.Frigg.company.name_Марка',
            'realPassword'          => 'df34werf34',
            'apiToken'              => 'c5ba0de39e6caa50d4f50ef965cb2716',
        ],
        [
            'Company'               => 'company.name_Марка',
            'Username'              => 'baldr@valhalla.sky',
            'UsernameCanonical'     => 'baldr@valhalla.sky',
            'Email'                 => 'baldr@valhalla.sky',
            'EmailCanonical'        => 'baldr@valhalla.sky',
            'Phone'                 => '+792472976545',
            'Enabled'               => true,
            'Salt'                  => '6R6zXmKXTdo56TgVmLd6A/lEWNjAdRnjy5djoKJx8pc',
            'Password'              => 'UxjXxINYQ5lZVdDn7GEk4ljB4s5Igjgd5FA2/00x87eQo7mSXOoJfxSakIQa8jFtIKpmp9MNRTXG89lT3DwCjw==',
            'LastLogin'             => '2017-06-19 04:20:40',
            'ConfirmationToken'     => null,
            'PasswordRequestedAt'   => null,
            'Roles'                 => [User::ROLE_OPERATOR],
            'fixtureReference'      => 'user.Baldr.company.name_Марка',
            'realPassword'          => 'b6587h9875&^',
            'apiToken'              => 'eeadcfe50cb51fcec68d436ccee3e76b',

        ],
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->templateList as $userData) {
            $userData['Company'] = $this->getReference($userData['Company']);
            $userData['LastLogin'] = new \DateTime($userData['LastLogin']);
            $user = new User();

            $this->fillObject($user, $userData);
            $userReflection = new \ReflectionClass($user);
            $property = $userReflection->getProperty('apiToken');
            $property->setAccessible(true);
            $property->setValue($user, $userData['apiToken']);

            $manager->persist($user);
            if (isset($userData['avatar'])) {
                $user->setAvatar($userData['avatar'], 'png');
            }
            if (isset($userData['contactBackground'])) {
                $user->setContactBackground($userData['contactBackground'], 'png');
            }
            $manager->persist($user);

            $this->setReference($userData['fixtureReference'], $user);
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
            if ($propertyName == 'fixtureReference' ||
                $propertyName == 'realPassword'||
                $propertyName == 'contactBackground'||
                $propertyName == 'avatar'||
                $propertyName == 'apiToken'
            ) {
                continue;
            }

            $propertyName = 'set'.$propertyName;
            $object->$propertyName($propertyValue);
        }
        return $object;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadCompany::class,
        ];
    }
}
