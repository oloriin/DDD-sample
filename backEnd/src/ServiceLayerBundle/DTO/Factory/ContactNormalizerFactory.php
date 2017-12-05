<?php
namespace ServiceLayerBundle\DTO\Factory;

use ServiceLayerBundle\DTO\ContactNormalizer;

class ContactNormalizerFactory
{
    /**
     * @return ContactNormalizer
     */
    public function getNormalizer() : ContactNormalizer
    {
        return new ContactNormalizer();
    }
}
