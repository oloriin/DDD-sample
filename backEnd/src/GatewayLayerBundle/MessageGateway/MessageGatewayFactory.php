<?php
namespace GatewayLayerBundle\MessageGateway;

use DomainLayer\Company\Company;
use DomainLayer\MessageService\MessageService;
use DomainLayer\MessageService\MessageServiceGatewayType;
use DomainLayer\MessageService\MessageServiceRepositoryInterface;
use GatewayLayerBundle\Exception\MessageGatewayException;
use GuzzleHttp\Client;
use ServiceLayerBundle\DTO\Factory\ContactExternalAccountNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\ContactNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\MessageNormalizerFactory;

class MessageGatewayFactory
{
    /** @var MessageServiceRepositoryInterface */
    private $messageServiceRepository;

    /** @var Client */
    private $client;
    /**
     * @var ContactNormalizerFactory
     */
    private $contactNormalizerFactory;
    /**
     * @var MessageNormalizerFactory
     */
    private $messageNormalizerFactory;
    /**
     * @var ContactExternalAccountNormalizerFactory
     */
    private $externalAccountNormalizerFactory;

    public function __construct(
        ContactNormalizerFactory $contactNormalizerFactory,
        MessageNormalizerFactory $messageNormalizerFactory,
        ContactExternalAccountNormalizerFactory $externalAccountNormalizerFactory,
        MessageServiceRepositoryInterface $messageServiceRepository,
        Client $client
    ) {
        $this->messageServiceRepository = $messageServiceRepository;
        $this->client = $client;
        $this->contactNormalizerFactory = $contactNormalizerFactory;
        $this->messageNormalizerFactory = $messageNormalizerFactory;
        $this->externalAccountNormalizerFactory = $externalAccountNormalizerFactory;
    }

    public function createByMessageServiceType(
        Company $company,
        string $messageServiceType
    ): MessageGatewayInterface {
        /** @var MessageService $messageService */
        $messageService = $this->messageServiceRepository
            ->findOneBy(['company' => $company, 'type' => $messageServiceType]);

        if (empty($messageService)) {
            throw new MessageGatewayException(
                'MessageService not found. Company - '.$company->getName().
                ' MessageServiceType - '.$messageServiceType
            );
        }

        return $this->createMessageGateway($messageService);
    }

    public function createByMessageServiceGatewayType(
        Company $company,
        string $messageServiceGatewayType
    ): MessageGatewayInterface {
        /** @var MessageService $messageService */
        $messageService = $this->messageServiceRepository->findOneBy(
            ['company' => $company, 'gatewayType' => $messageServiceGatewayType]
        );

        if (empty($messageService)) {
            throw new MessageGatewayException(
                'MessageService not found. Company - '.$company->getName().
                ' MessageServiceGatewayType - '.$messageServiceGatewayType
            );
        }

        return $this->createMessageGateway($messageService);
    }

    private function createMessageGateway(MessageService $messageService)
    {
        switch ($messageService->getGatewayType()) {
            case MessageServiceGatewayType::TELEGRAM:
                $messageServiceGateway = new TelegramGateway(
                    $this->contactNormalizerFactory,
                    $this->messageNormalizerFactory,
                    $this->externalAccountNormalizerFactory,
                    $this->client,
                    $messageService->getConnectionDataParam('authToken')
                );
                break;

            case MessageServiceGatewayType::VKONTAKTE:
                $messageServiceGateway = new VkGateway(
                    $this->contactNormalizerFactory,
                    $this->messageNormalizerFactory,
                    $this->externalAccountNormalizerFactory,
                    $this->client,
                    $messageService->getConnectionDataParam('authToken'),
                    $messageService->getConnectionDataParam('confirmationCode')
                );
                break;

            default:
                throw new MessageGatewayException('Undefined GatewayType - '.$messageService->getGatewayType());
        }

        return $messageServiceGateway;
    }
}
