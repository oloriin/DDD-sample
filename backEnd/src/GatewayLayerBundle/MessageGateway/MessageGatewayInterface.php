<?php
namespace GatewayLayerBundle\MessageGateway;

use ServiceLayerBundle\DTO\ContactProvider;
use ServiceLayerBundle\DTO\GatewayParseResultDTO;
use Symfony\Component\HttpFoundation\Request;

interface MessageGatewayInterface
{
    public function parseMessageData(string $messageData): ContactProvider;
    public function getMessageServiceGatewayType(): string;
    public function getExternalIdentifierName(): string;
    public function sendMessage(string $text, string $mainAccountIdentifier, array $additionalAccountIdentifier): bool;
    public function handleRequest(Request $request): GatewayParseResultDTO;
}
