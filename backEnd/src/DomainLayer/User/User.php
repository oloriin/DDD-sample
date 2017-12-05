<?php
namespace DomainLayer\User;

use DomainLayer\Company\Company;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Imagine\Image\Box;
use Imagine\Imagick\Imagine;

/**
 *
 * @ORM\Table(name="sintez_user")
 * @ORM\Entity(repositoryClass="InfrastructureLayerBundle\Repository\PostgresUserRepository")
 */
class User extends BaseUser
{
    const ROLE_USER              = 'ROLE_USER';
    const ROLE_AGENCY_ADMIN      = 'ROLE_AGENCY_ADMIN';
    const ROLE_AGENCY_MANAGER    = 'ROLE_AGENCY_MANAGER';
    const ROLE_OPERATOR          = 'ROLE_OPERATOR';
    const ROLE_MAIN_OPERATOR     = 'ROLE_MAIN_OPERATOR';


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="DomainLayer\Company\Company")
     * @ORM\JoinColumn(name="company", referencedColumnName="id", nullable=false)
     */
    private $company;

    /**
     * @var string apiToken
     * @ORM\Column(type="string", unique=true)
     */
    private $apiToken;

    /**
     * @var string phone
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * 24x24
     * @var string avatarMicro
     * @ORM\Column(name="avatar_micro", type="string", nullable=true)
     */
    private $avatarMicro;

    /**
     * 36x36
     * @var string avatarSmall
     * @ORM\Column(name="avatar_small", type="string", nullable=true)
     */
    private $avatarSmall;

    /**
     * 50x50
     * @var string avatarMedium
     * @ORM\Column(name="avatar_medium", type="string", nullable=true)
     */
    private $avatarMedium;

    /**
     * 160x160
     * @var string avatarLarge
     * @ORM\Column(name="avatar_large", type="string", nullable=true)
     */
    private $avatarLarge;

    public function __construct()
    {
        parent::__construct();
    }

    /** @return Company */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return User
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
        return $this;
    }

    /** @return mixed */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**

     * @return User
     */
    public function generateApiToken()
    {
        /**
         * TODO незабудь посолить перед сервировкой на прод!
         */
        $this->apiToken = md5($this->username.$this->password);

        return $this;
    }

    /** @return string */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $avatarTmpPath
     * @param string $imageType
     * @return User
     */
    public function setAvatar(string $avatarTmpPath, string $imageType): self
    {
        if (!($imageType == 'jpeg' || $imageType == 'png')) {
            /** @TODO Exeption */
            return $this;
        }

        $imagine = new Imagine();
        $avatarURL = '/images/company/'.$this->getCompany()->getId().'/user/'.$this->getId().'/';
        $avatarDirectory = __DIR__.'/../../../web'.$avatarURL;

        if (is_dir($avatarDirectory) === false) {
            mkdir($avatarDirectory, 0775, true);
        }

        $imageSmall = $imagine->open($avatarTmpPath);
        $imageSmall->resize(new Box(24, 24))->save($avatarDirectory.'avatarMicro.'.$imageType);
        $this->avatarMicro = $avatarURL.'avatarMicro.'.$imageType;

        $imageSmall = $imagine->open($avatarTmpPath);
        $imageSmall->resize(new Box(36, 36))->save($avatarDirectory.'avatarSmall.'.$imageType);
        $this->avatarSmall = $avatarURL.'avatarSmall.'.$imageType;

        $imageSmall = $imagine->open($avatarTmpPath);
        $imageSmall->resize(new Box(50, 50))->save($avatarDirectory.'avatarMedium.'.$imageType);
        $this->avatarMedium = $avatarURL.'avatarMedium.'.$imageType;

        $imageSmall = $imagine->open($avatarTmpPath);
        $imageSmall->resize(new Box(160, 160))->save($avatarDirectory.'avatarLarge.'.$imageType);
        $this->avatarLarge = $avatarURL.'avatarLarge.'.$imageType;

        return $this;
    }

    /** @return string */
    public function getAvatarMicro(): ?string
    {
        return $this->avatarMicro;
    }

    /** @return string */
    public function getAvatarSmall(): ?string
    {
        return $this->avatarSmall;
    }

    /** @return string */
    public function getAvatarMedium(): ?string
    {
        return $this->avatarMedium;
    }

    /** @return string */
    public function getAvatarLarge(): ?string
    {
        return $this->avatarLarge;
    }

    public static function getStandardNormalizeContext()
    {
        return [
            'company'            => 'getId',
            'apiToken'           => false,
            'salt'               => false,
            'password'           => false,
            'plainPassword'      => false,
            'confirmationToken'  => false,
            'passwordRequestedAt'=> false,
            'groups'             => false,
            'lastLogin'          => function (User $user) {
                return $user->getLastLogin()->getTimestamp();
            },
        ];
    }
}
