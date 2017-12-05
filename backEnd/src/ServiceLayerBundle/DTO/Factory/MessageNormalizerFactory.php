<?php
namespace ServiceLayerBundle\DTO\Factory;

use ServiceLayerBundle\DTO\MessageNormalizer;

class MessageNormalizerFactory
{
    /**
     * @param string $messageServiceType
     * @param string $body
     * @param \DateTime $createAt
     * @param string $directionType
     * @param bool $contactRead
     * @param bool $operatorRead
     * @return MessageNormalizer
     */
    public function getNormalizer(
        string $messageServiceType,
        string $body,
        \DateTime $createAt,
        string $directionType,
        bool $contactRead,
        bool $operatorRead
    ) : MessageNormalizer {
        return new MessageNormalizer(
            $messageServiceType,
            $body,
            $createAt,
            $directionType,
            $contactRead,
            $operatorRead
        );
    }
}
