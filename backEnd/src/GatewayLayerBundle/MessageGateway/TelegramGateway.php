<?php
namespace GatewayLayerBundle\MessageGateway;

use DomainLayer\Contact\Message\MessageDirectionType;
use DomainLayer\MessageService\MessageServiceGatewayType;
use DomainLayer\MessageService\MessageServiceType;
use GatewayLayerBundle\Exception\MessageGatewayException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use ServiceLayerBundle\DTO\ContactProvider;
use ServiceLayerBundle\DTO\Factory\ContactExternalAccountNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\ContactNormalizerFactory;
use ServiceLayerBundle\DTO\Factory\MessageNormalizerFactory;
use ServiceLayerBundle\DTO\GatewayParseResultDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TelegramGateway implements MessageGatewayInterface
{

    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var string
     */
    private $botToken;
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
        Client $httpClient,
        string $botToken
    ) {

        $this->httpClient = $httpClient;
        $this->botToken = $botToken;
        $this->contactNormalizerFactory = $contactNormalizerFactory;
        $this->messageNormalizerFactory = $messageNormalizerFactory;
        $this->externalAccountNormalizerFactory = $externalAccountNormalizerFactory;
    }

    public function parseMessageData(string $messageData): ContactProvider
    {
        $contactProvider = new ContactProvider();

        $singleMessageData = json_decode($messageData);
        if (is_null($singleMessageData)) {
            throw new MessageGatewayException('Parse telegram message. Json not valid. '.$messageData);
        }

        $contactNormalizer = $this->contactNormalizerFactory->getNormalizer();

        $externalAccountNormalizer = $this->externalAccountNormalizerFactory->createNormalizer(
            MessageServiceType::TELEGRAM,
            $singleMessageData->message->from->id
        );

        $createdDate = new \DateTime();
        $createdDate->format('Y-m-d H:i:s');
        $createdDate->setTimestamp($singleMessageData->message->date);

        $messageNormalizer = $this->messageNormalizerFactory->getNormalizer(
            MessageServiceType::TELEGRAM,
            $singleMessageData->message->text,
            $createdDate,
            MessageDirectionType::IN,
            true,
            false
        );

        $contactNormalizer->getMessageNormalizerList()->add($messageNormalizer);
        $contactNormalizer->getContactExternalAccountNormalizerList()->add($externalAccountNormalizer);
        $contactProvider->add($contactNormalizer);

        return $contactProvider;
    }

    public function sendMessage(string $text, string $chatId, array $additionalAccountIdentifier): bool
    {

        $params = [
            'query' => [
                'chat_id'     => $chatId,
                'text'       => $text,
            ],
            'timeout' => 4
        ];

        try {
            $this->httpClient->request(
                'GET',
                'https://api.telegram.org/bot'.$this->botToken.'/sendMessage',
                $params
            );
        } catch (GuzzleException $exception) {
            throw new MessageGatewayException($exception->getMessage());
        }

        /**
         * @TODO add message transaction
         */
        return true;
    }

    public function getMessageServiceGatewayType(): string
    {
        return MessageServiceGatewayType::TELEGRAM;
    }

    public function getExternalIdentifierName(): string
    {
        return 'telegram_chat_id';
    }

    public function handleRequest(Request $request): GatewayParseResultDTO
    {
        $contactProvider = $this->parseMessageData($request->getContent());
        $response = new JsonResponse([], 200);

        return new GatewayParseResultDTO($contactProvider, $response);
    }
}
