<?php

namespace ServiceLayerBundle\DTO;

use Symfony\Component\HttpFoundation\Response;

class GatewayParseResultDTO
{
    /**
     * @var ContactProvider
     */
    private $contactProvider;
    /**
     * @var Response
     */
    private $response;

    public function __construct(
        ContactProvider $contactProvider,
        Response $response
    ) {
        $this->contactProvider = $contactProvider;
        $this->response = $response;
    }

    /** @return ContactProvider */
    public function getContactProvider(): ContactProvider
    {
        return $this->contactProvider;
    }

    /** @return Response */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
