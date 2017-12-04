<?php

namespace CoreDomain\MessageService;

use CoreDomain\Company\Company;
use CoreDomain\Normalized;
use Doctrine\ORM\Mapping as ORM;

/**
 * MessageService
 * CoreDomain\MessageService\MessageService
 * @ORM\Table(name="message_service")
 * @ORM\Entity(repositoryClass="CoreDomainBundle\Repository\PostgresMessageServiceRepository")
 */
class MessageService implements Normalized
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
     * @ORM\ManyToOne(targetEntity="CoreDomain\Company\Company" ,cascade={"persist"})
     * @ORM\JoinColumn(name="company", referencedColumnName="id", nullable=false)
     */
    private $company;

    /**
     * @var MessageServiceType
     *
     * @ORM\Column(name="type", type="MessageServiceType")
     */
    private $type;

    /**
     * @var MessageServiceGatewayType
     *
     * @ORM\Column(name="gatewayType", type="MessageServiceGatewayType")
     */
    private $gatewayType;

    /**
     * @var array
     *
     * @ORM\Column(name="connectionData", type="json_array")
     */
    private $connectionData;

    /**
     * @var string
     *
     * @ORM\Column(name="main_identifier", type="string", nullable=false)
     */
    private $mainIdentifier;

    public function __construct(
        Company $company,
        string $type,
        string $gatewayType,
        array $connectionData,
        string $mainIdentifier
    ) {

        $this->company = $company;
        $this->type = $type;
        $this->gatewayType = $gatewayType;
        $this->connectionData = $connectionData;
        $this->mainIdentifier = $mainIdentifier;
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
     * @param Company $company
     *
     * @return MessageService
     */
    public function setCompany(Company $company) : self
    {
        $this->company = $company;

        return $this;
    }

    /** @return Company */
    public function getCompany() : Company
    {
        return $this->company;
    }

    /**
     * @param string $type
     *
     * @return MessageService
     */
    public function setType(string $type) : self
    {
        $this->type = $type;

        return $this;
    }

    /** @return string */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @param string $gatewayType
     *
     * @return MessageService
     */
    public function setGatewayType(string $gatewayType) : self
    {
        $this->gatewayType = $gatewayType;

        return $this;
    }

    /** @return string */
    public function getGatewayType() : string
    {
        return $this->gatewayType;
    }

    /**
     * @param array $connectionData
     *
     * @return MessageService
     */
    public function setConnectionData(array $connectionData) : self
    {
        $this->connectionData = $connectionData;

        return $this;
    }

    /** @return array */
    public function getConnectionData() : array
    {
        return $this->connectionData;
    }

    /**
     * @param string $name
     * @return bool|mixed
     */
    public function getConnectionDataParam(string $name)
    {
        if (isset($this->connectionData[$name])) {
            return $this->connectionData[$name];
        }

        return false;
    }

    /** @return string */
    public function getMainIdentifier(): string
    {
        return $this->mainIdentifier;
    }

    /**
     * @param string $mainIdentifier
     * @return MessageService
     */
    public function setMainIdentifier(string $mainIdentifier)
    {
        $this->mainIdentifier = $mainIdentifier;
        return $this;
    }

    /**
     * Context from TreeGetSetNormalizer
     * @return array
     */
    public static function getStandardNormalizeContext()
    {
        return [
            'company' => 'getId'
        ];
    }
}
