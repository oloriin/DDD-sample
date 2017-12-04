<?php
namespace CoreDomain\Contact\Message;

use CoreDomain\Contact\Contact;
use CoreDomain\Normalized;
use CoreDomain\MessageService\MessageServiceType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="CoreDomainBundle\Repository\PostgresMessageRepository")
 */
class Message implements Normalized
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
     * @var MessageServiceType
     *
     * @ORM\Column(name="message_service_type", type="MessageServiceType")
     */
    private $messageServiceType;

    /**
     * @var MessageSenderAppType
     *
     * @ORM\Column(name="sender_app_type", type="MessageSenderAppType")
     */
    private $senderAppType;

    /**
     * @var MessageDirectionType
     *
     * @ORM\Column(name="direction_type", type="MessageDirectionType")
     */
    private $directionType;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var bool
     *
     * @ORM\Column(name="contact_read", type="boolean")
     */
    private $contactRead = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="operator_read", type="boolean")
     */
    private $operatorRead = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var Contact
     * @ORM\ManyToOne(targetEntity="CoreDomain\Contact\Contact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="gateway_status_type", type="MessageGatewayStatusType")
     */
    private $gatewayStatus = MessageGatewayStatusType::CREATED;

    public function __construct(
        Contact $contact,
        string $body,
        \DateTime $createAt,
        string $messageServiceType,
        string $senderAppType,
        string $directionType,
        bool $contactRead,
        bool $operatorRead
    ) {
        $this->contact = $contact;
        $this->body = $body;
        $this->createAt = $createAt;
        $this->messageServiceType = $messageServiceType;
        $this->senderAppType = $senderAppType;
        $this->directionType = $directionType;
        $this->contactRead = $contactRead;
        $this->operatorRead = $operatorRead;
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
     * @param string $messageServiceType (MessageServiceType)
     *
     * @return Message
     */
    public function setMessageServiceType(string $messageServiceType) : self
    {
        $this->messageServiceType = $messageServiceType;

        return $this;
    }

    /** @return string (MessageServiceType) */
    public function getMessageServiceType() : string
    {
        return $this->messageServiceType;
    }

    /** @return string */
    public function getSenderAppType(): string
    {
        return $this->senderAppType;
    }

    /**
     * @param string $senderAppType
     * @return Message
     */
    public function setSenderAppType(string $senderAppType) : self
    {
        $this->senderAppType = $senderAppType;
        return $this;
    }

    /**
     * @param string $directionType (MessageDirectionType)
     *
     * @return Message
     */
    public function setDirectionType(string $directionType) : self
    {
        $this->directionType = $directionType;

        return $this;
    }

    /** @return string (MessageDirectionType) */
    public function getDirectionType() : string
    {
        return $this->directionType;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set contactRead
     *
     * @param boolean $contactRead
     *
     * @return Message
     */
    public function setContactRead($contactRead)
    {
        $this->contactRead = $contactRead;

        return $this;
    }

    /**
     * Get contactRead
     *
     * @return bool
     */
    public function getContactRead()
    {
        return $this->contactRead;
    }

    /**
     * Set operatorRead
     *
     * @param boolean $operatorRead
     *
     * @return Message
     */
    public function setOperatorRead($operatorRead)
    {
        $this->operatorRead = $operatorRead;

        return $this;
    }

    /**
     * Get operatorRead
     *
     * @return bool
     */
    public function getOperatorRead()
    {
        return $this->operatorRead;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Message
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /** @return Contact */
    public function getContact(): Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     * @return Message
     */
    public function setContact(Contact $contact) : self
    {
        $this->contact = $contact;
        return $this;
    }

    /** @return string */
    public function getGatewayStatus(): string
    {
        return $this->gatewayStatus;
    }

    /**
     * @param string $gatewayStatus
     * @return Message
     */
    public function setGatewayStatus(string $gatewayStatus)
    {
        $this->gatewayStatus = $gatewayStatus;
        return $this;
    }


    /**
     * Context from TreeGetSetNormalizer
     * @return array
     */
    public static function getStandardNormalizeContext()
    {
        return [
            'contact'    => 'getId',
            'createAt'   => function (Message $message) {
                return $message->getCreateAt()->getTimestamp();
            },
        ];
    }
}
