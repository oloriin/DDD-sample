<?php
namespace ServiceLayerBundle\DTO\Factory;

use ServiceLayerBundle\DTO\ContactExternalAccountNormalizer;

class ContactExternalAccountNormalizerFactory
{
    public function createNormalizer(string $messageServiceType, string $mainAccountId):ContactExternalAccountNormalizer
    {
        return new ContactExternalAccountNormalizer($messageServiceType, $mainAccountId);
    }
}
