<?php
namespace ServiceLayerBundle\DTO;

class MessageNormalizer
{
    /** @var string */
    private $body;

    /** @var  string (MessageServiceType) */
    private $messageServiceType;

    /** @var  string (MessageSenderAppType) */
    private $senderAppType;

    /** @var  string (MessageDirectionType) */
    private $directionType;

    /** @var bool */
    private $contactRead;

    /** @var bool */
    private $operatorRead;

    /** @var \DateTime */
    private $createAt;

    public function __construct(
        string $messageServiceType,
        string $body,
        \DateTime $createAt,
        string $directionType,
        bool $contactRead,
        bool $operatorRead
    ) {
        $this->messageServiceType = $messageServiceType;
        $this->body = $body;
        $this->createAt = $createAt;
        $this->directionType = $directionType;
        $this->contactRead = $contactRead;
        $this->operatorRead = $operatorRead;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return MessageNormalizer
     */
    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    /** @return string */
    public function getMessageServiceType(): string
    {
        return $this->messageServiceType;
    }

    /**
     * @param string $messageServiceType
     * @return MessageNormalizer
     */
    public function setMessageServiceType(string $messageServiceType)
    {
        $this->messageServiceType = $messageServiceType;
        return $this;
    }

    /** @return mixed */
    public function getSenderAppType()
    {
        return $this->senderAppType;
    }

    /**
     * @param string $senderAppType
     * @return MessageNormalizer
     */
    public function setSenderAppType(string $senderAppType) : self
    {
        $this->senderAppType = $senderAppType;
        return $this;
    }

    /** @return string */
    public function getDirectionType(): string
    {
        return $this->directionType;
    }

    /**
     * @param string $directionType
     * @return MessageNormalizer
     */
    public function setDirectionType(string $directionType)
    {
        $this->directionType = $directionType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isContactRead(): bool
    {
        return $this->contactRead;
    }

    /**
     * @param bool $contactRead
     * @return MessageNormalizer
     */
    public function setContactRead(bool $contactRead)
    {
        $this->contactRead = $contactRead;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOperatorRead(): bool
    {
        return $this->operatorRead;
    }

    /**
     * @param bool $operatorRead
     * @return MessageNormalizer
     */
    public function setOperatorRead(bool $operatorRead)
    {
        $this->operatorRead = $operatorRead;
        return $this;
    }

    /** @return \DateTime */
    public function getCreateAt(): \DateTime
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     * @return MessageNormalizer
     */
    public function setCreateAt(\DateTime $createAt)
    {
        $this->createAt = $createAt;
        return $this;
    }
}
