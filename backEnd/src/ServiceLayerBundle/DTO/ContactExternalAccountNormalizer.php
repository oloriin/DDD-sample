<?php
namespace ServiceLayerBundle\DTO;

class ContactExternalAccountNormalizer
{
    /** @var string (MessageServiceType) */
    private $messageServiceType;

    /** @var  string */
    private $mainAccountId;

    /** @var  array */
    private $additionalAccountId = [];

    public function __construct(string $messageServiceType, string $mainAccountId)
    {
        $this->messageServiceType = $messageServiceType;
        $this->mainAccountId = $mainAccountId;
    }

    /** @return string */
    public function getMessageServiceType(): string
    {
        return $this->messageServiceType;
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
     * @param array $additionalAccountId
     * @return ContactExternalAccountNormalizer
     */
    public function setAdditionalAccountId(array $additionalAccountId)
    {
        $this->additionalAccountId = $additionalAccountId;
        return $this;
    }

}
