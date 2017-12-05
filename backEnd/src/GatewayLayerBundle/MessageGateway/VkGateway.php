<?php
namespace GatewayLayerBundle\MessageGateway;

use DomainLayer\Contact\Message\MessageDirectionType;
use DomainLayer\MessageService\MessageServiceGatewayType;
use DomainLayer\MessageService\MessageServiceType;
use GuzzleHttp\Client;
use GatewayLayerBundle\Exception\MessageGatewayException;
use GuzzleHttp\Exception\GuzzleException;
use ServiceLayerBundle\DTO\ContactProvider;
use ServiceLayerBundle\DTO\Factory\ContactExternalAccountNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\ContactNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\MessageNormalizerFactory;
use ServiceLayerBundle\DTO\GatewayParseResultDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VkGateway implements MessageGatewayInterface
{
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
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var string
     */
    private $apiToken;
    /**
     * @var string
     */
    private $confirmationCode;

    public function __construct(
        ContactNormalizerFactory $contactNormalizerFactory,
        MessageNormalizerFactory $messageNormalizerFactory,
        ContactExternalAccountNormalizerFactory $externalAccountNormalizerFactory,
        Client $httpClient,
        string $apiToken,
        string $confirmationCode
    ) {
        $this->contactNormalizerFactory = $contactNormalizerFactory;
        $this->messageNormalizerFactory = $messageNormalizerFactory;
        $this->externalAccountNormalizerFactory = $externalAccountNormalizerFactory;
        $this->httpClient = $httpClient;
        $this->apiToken = $apiToken;
        $this->confirmationCode = $confirmationCode;
    }

    public function parseMessageData(string $messageObject): ContactProvider
    {
        $messageObject = json_decode($messageObject);
        $contactProvider = new ContactProvider();
        $contactNormalizer = $this->contactNormalizerFactory->getNormalizer();

        $externalAccountNormalizer = $this->externalAccountNormalizerFactory->createNormalizer(
            MessageServiceType::VKONTAKTE,
            $messageObject->object->user_id
        );
        $contactNormalizer->getContactExternalAccountNormalizerList()->add($externalAccountNormalizer);

        $messageCreateAt = new \DateTime();
        $messageCreateAt->format('Y-m-d H:i:s');
        $messageCreateAt->setTimestamp($messageObject->object->date);
        if ($messageObject->object->out) {
            $direction = MessageDirectionType::OUT;
            $operatorRead = true;
            $contactRead = false;
        } else {
            $direction = MessageDirectionType::IN;
            $operatorRead = false;
            $contactRead = true;
        }

        $messageNormalizer = $this->messageNormalizerFactory->getNormalizer(
            MessageServiceType::VKONTAKTE,
            $messageObject->object->body,
            $messageCreateAt,
            $direction,
            $contactRead,
            $operatorRead
        );
        $contactNormalizer->getMessageNormalizerList()->add($messageNormalizer);

        $contactProvider->add($contactNormalizer);
        return $contactProvider;
    }

    public function getMessageServiceGatewayType(): string
    {
        return MessageServiceGatewayType::VKONTAKTE;
    }

    public function getExternalIdentifierName(): string
    {
        // TODO: Implement getExternalIdentifierName() method.
    }

    public function sendMessage(string $text, string $mainAccountIdentifier, array $additionalAccountIdentifier): bool
    {

        $params = [
            'query' => [
                'access_token'=> $this->apiToken,
                'user_id'     => $mainAccountIdentifier,
                'message'     => $text,
            ],
            'timeout' => 4
        ];

        try {
            $response =  $this->httpClient->request(
                'GET',
                'https://api.vk.com/method/messages.send',
                $params
            );

            $responseBody = json_decode($response->getBody());
            if ($response->getStatusCode() != 200 || !isset($responseBody->response)) {
                throw new MessageGatewayException($response->getBody());
            }
        } catch (GuzzleException $exception) {
            throw new MessageGatewayException($exception->getMessage());
        }

        return true;
    }

    /**
     * @param Request $request
     * @return GatewayParseResultDTO response data
     * @throws MessageGatewayException
     * @internal param string $requestData
     */
    public function handleRequest(Request $request): GatewayParseResultDTO
    {
        $vkEvent = json_decode($request->getContent());

        switch ($vkEvent->type) {
            case 'message_new':
                $contactProvider = $this->parseMessageData($request->getContent());
                $response = new JsonResponse([], 200);
                break;
            case 'confirmation':
                $contactProvider = new ContactProvider();
                $response = new JsonResponse($this->confirmationCode, 200);
                break;
            default:
                throw new MessageGatewayException('Undefined event type.');
        }

        return new GatewayParseResultDTO($contactProvider, $response);
    }
}
