<?php
namespace DomainLayer\Company;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="InfrastructureLayerBundle\Repository\PostgresCompanyRepository")
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DomainLayer\Contact\Contact", cascade={"persist"},  mappedBy="company")
     */
    private $contact;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DomainLayer\MessageService\MessageService", cascade={"persist"},  mappedBy="company")
     */
    private $messageService;

    /**
     * @var string
     *
     * @ORM\Column(name="api_token", type="string", length=255, nullable=true)
     */
    private $apiToken;

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return Company
     */
    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /** @return Collection */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    /**
     * @param ArrayCollection $contact
     * @return Company
     */
    public function setContact(ArrayCollection $contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /** @return Collection */
    public function getMessageService(): Collection
    {
        return $this->messageService;
    }

    /**
     * @param Collection $messageService
     * @return Company
     */
    public function setMessageService(Collection $messageService)
    {
        $this->messageService = $messageService;
        return $this;
    }

    /** @return string */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     * @return Company
     */
    public function setApiToken(string $apiToken)
    {
        $this->apiToken = $apiToken;
        return $this;
    }
}
