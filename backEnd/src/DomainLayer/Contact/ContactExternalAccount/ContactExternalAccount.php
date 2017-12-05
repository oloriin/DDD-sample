<?php
namespace DomainLayer\Contact\ContactExternalAccount;

use DomainLayer\Company\Company;
use DomainLayer\Contact\Contact;
use DomainLayer\Normalized;
use DomainLayer\MessageService\MessageServiceType;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactExternalAccount
 *
 * @ORM\Table(name="contact_external_account")
 * @ORM\Entity(repositoryClass="InfrastructureLayerBundle\Repository\PostgresContactExternalAccountRepository")
 */
class ContactExternalAccount implements Normalized
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
     * @ORM\ManyToOne(targetEntity="DomainLayer\Company\Company" )
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     */
    private $company;

    /**
     * @var Contact
     * @ORM\ManyToOne(targetEntity="DomainLayer\Contact\Contact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @var MessageServiceType
     *
     * @ORM\Column(name="message_service_type", type="MessageServiceType")
     */
    private $messageServiceType;

    /**
     * @var string
     *
     * @ORM\Column(name="main_account_id", type="string", length=255)
     */
    private $mainAccountId;

    /**
     * @var array
     *
     * @ORM\Column(name="additional_account_id", type="array")
     */
    private $additionalAccountId;

    public function __construct(Company $company, Contact $contact, string $messageServiceType, string $mainAccountId)
    {
        $this->company = $company;
        $this->contact = $contact;
        $this->messageServiceType = $messageServiceType;
        $this->mainAccountId = $mainAccountId;
        $this->additionalAccountId = [];
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

    /** @return Company */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return ContactExternalAccount
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
        return $this;
    }

    /** @return Contact */
    public function getContact(): Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     * @return ContactExternalAccount
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param string $messageServiceType
     *
     * @return ContactExternalAccount
     */
    public function setMessageServiceType(string $messageServiceType): self
    {
        $this->messageServiceType = $messageServiceType;

        return $this;
    }

    /**
     * @return string (MessageServiceType)
     */
    public function getMessageServiceType(): string
    {
        return $this->messageServiceType;
    }

    /**
     * @param string $mainAccountId
     *
     * @return ContactExternalAccount
     */
    public function setMainAccountId(string $mainAccountId): self
    {
        $this->mainAccountId = $mainAccountId;

        return $this;
    }

    /** @return string */
    public function getMainAccountId(): string
    {
        return $this->mainAccountId;
    }

    /** @return array */
    public function getAdditionalAccountId(): array
    {
        return $this->additionalAccountId;
    }

    /**
     * @return ContactExternalAccount
     */
    public function removeAdditionalAccountId(): self
    {
        $this->additionalAccountId = [];
        return $this;
    }

    /**
     * @param array $additionalAccountId
     * @return ContactExternalAccount
     */
    public function addAdditionalAccountId(array $additionalAccountId): self
    {
        $this->additionalAccountId = array_merge($this->additionalAccountId, $additionalAccountId);
        return $this;
    }

    /**
     * Context from TreeGetSetNormalizer
     * @return array
     */
    public static function getStandardNormalizeContext()
    {
        return [
            'contact' => 'getId',
            'company' => 'getId',
        ];
    }
}
