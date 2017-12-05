<?php
namespace ServiceLayerBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;

class ContactNormalizer
{
    /** @var ArrayCollection*/
    private $messageNormalizerList;

    /** @var  ArrayCollection */
    private $contactExternalAccountNormalizerList;

    /** @var string */
    private $presence;

    /** @var CompanyNormalizer */
    private $companyNormalizer;

    public function __construct()
    {
        $this->messageNormalizerList = new ArrayCollection();
        $this->contactExternalAccountNormalizerList = new ArrayCollection();
    }

    /** @return CompanyNormalizer */
    public function getCompanyNormalizer(): CompanyNormalizer
    {
        return $this->companyNormalizer;
    }

    /**
     * @param CompanyNormalizer $companyNormalizer
     * @return ContactNormalizer
     */
    public function setCompanyNormalizer(CompanyNormalizer $companyNormalizer)
    {
        $this->companyNormalizer = $companyNormalizer;
        return $this;
    }

    /** @return ArrayCollection */
    public function getContactExternalAccountNormalizerList(): ArrayCollection
    {
        return $this->contactExternalAccountNormalizerList;
    }

    /**
     * @param ArrayCollection $contactExternalAccountNormalizerList
     * @return ContactNormalizer
     */
    public function setContactExternalAccountNormalizerList(ArrayCollection $contactExternalAccountNormalizerList)
    {
        $this->contactExternalAccountNormalizerList = $contactExternalAccountNormalizerList;
        return $this;
    }

    /** @return ArrayCollection */
    public function getMessageNormalizerList(): ArrayCollection
    {
        return $this->messageNormalizerList;
    }

    /**
     * @param ArrayCollection $messageNormalizerList
     * @return ContactNormalizer
     */
    public function setMessageNormalizerList(ArrayCollection $messageNormalizerList)
    {
        $this->messageNormalizerList = $messageNormalizerList;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresence()
    {
        return $this->presence;
    }

    /**
     * @param string $presence
     * @return ContactNormalizer
     */
    public function setPresence(string $presence) : ContactNormalizer
    {
        $this->presence = $presence;
        return $this;
    }
}
