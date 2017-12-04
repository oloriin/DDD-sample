<?php
namespace CoreDomain\Contact;

use CoreDomain\Company\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="CoreDomainBundle\Repository\PostgresContactRepository")
 */
class Contact
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
     * @var Company
     * @ManyToOne(targetEntity="CoreDomain\Company\Company" ,cascade={"persist"})
     * @JoinColumn(name="company", referencedColumnName="id", nullable=false)
     */
    private $company;

    /**
     * @var Message\Message
     * @OneToMany(targetEntity="CoreDomain\Contact\Message\Message", mappedBy="contact", cascade={"persist"})
     */
    private $messages;

    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="CoreDomain\Contact\ContactExternalAccount\ContactExternalAccount", mappedBy="contact")
     */
    private $contactExternalAccount;

    /**
     * @var ContactPresenceType
     *
     * @ORM\Column(name="presence", type="ContactPresenceType")
     */
    private $presence = ContactPresenceType::OFFLINE;

    public function __construct(Company $company)
    {
        $this->company = $company;
        $this->messages = new ArrayCollection();
        $this->contactExternalAccount = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return Contact
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
        return $this;
    }

    /** @return mixed */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     * @return Contact
     */
    public function setMessages(ArrayCollection $messages) : self
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @return string
     */
    public function getPresence(): string
    {
        return $this->presence;
    }

    /**
     * @param string $presence
     * @return Contact
     */
    public function setPresence(string $presence)
    {
        $this->presence = $presence;
        return $this;
    }

    /** @return ArrayCollection */
    public function getContactExternalAccount()
    {
        return $this->contactExternalAccount;
    }

    /**
     * @param ArrayCollection $contactExternalAccount
     * @return Contact
     */
    public function setContactExternalAccount(ArrayCollection $contactExternalAccount)
    {
        $this->contactExternalAccount = $contactExternalAccount;
        return $this;
    }
}
