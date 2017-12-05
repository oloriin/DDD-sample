<?php
namespace ServiceLayerBundle\DTO;

class CompanyNormalizer
{
    /** @var  string */
    private $messageServiceMainId;

    /** @var  string */
    private $messageServiceGatewayType;

    public function __construct(string $messageServiceGatewayType, string $messageServiceMainId)
    {
        $this->messageServiceGatewayType = $messageServiceGatewayType;
        $this->messageServiceMainId = $messageServiceMainId;
    }

    /** @return string */
    public function getMessageServiceMainId(): string
    {
        return $this->messageServiceMainId;
    }

    /** @return string */
    public function getMessageServiceGatewayType(): string
    {
        return $this->messageServiceGatewayType;
    }
}
