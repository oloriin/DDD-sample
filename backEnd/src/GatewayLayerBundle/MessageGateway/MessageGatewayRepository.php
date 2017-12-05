<?php
namespace GatewayLayerBundle\MessageGateway;

use DomainLayer\Company\Company;

class MessageGatewayRepository
{
    /** @var array */
    private $identityMap;

    /** @var MessageGatewayFactory */
    private $messageGatewayFactory;

    public function __construct(MessageGatewayFactory $messageGatewayFactory)
    {
        $this->messageGatewayFactory = $messageGatewayFactory;
    }

    /**
     * @param Company $company
     * @param string $messageServiceType
     * @param string $messageServiceGatewayType
     * @return string
     */
    private function getHash(Company $company, string $messageServiceType, string $messageServiceGatewayType)
    {
        $hash = 'company_'.$company->getId().'.service_type_'.$messageServiceType.'.gateway_type_'.$messageServiceGatewayType;
        return $hash;
    }

    private function setGateway(
        Company $company,
        string $messageServiceType,
        string $messageServiceGatewayType,
        MessageGatewayInterface $messageGateway
    ) {
        $hash = $this->getHash($company, $messageServiceType, $messageServiceGatewayType);
        $this->identityMap[$hash] = $messageGateway;
    }

    public function getGateway(
        Company $company,
        string $messageServiceType,
        string $messageServiceGatewayType = ''
    ): MessageGatewayInterface {
        if ($messageServiceGatewayType == '') {
            $messageGateway = $this->messageGatewayFactory->createByMessageServiceType($company, $messageServiceType);
            $messageServiceGatewayType = $messageGateway->getMessageServiceGatewayType();
        }

        $hash = $this->getHash($company, $messageServiceType, $messageServiceGatewayType);

        if (isset($this->identityMap[$hash])) {
            return $this->identityMap[$hash];
        } else {
            $messageGateway = $this->messageGatewayFactory
                ->createByMessageServiceGatewayType($company, $messageServiceGatewayType);
            $this->setGateway($company, $messageServiceType, $messageServiceGatewayType, $messageGateway);
            return $messageGateway;
        }
    }
}
