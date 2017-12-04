<?php
namespace CoreDomain\Contact;

use Doctrine\Common\Persistence\ObjectRepository;

interface ContactRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(Contact $contact);

    public function doBatchSave();

    public function save(Contact $contact);

    public function findSystemPropertyFromContact(Contact $contact, string $propertyName);

    public function findExternalAccountFromMessageServiceType(Contact $contact, string $messageServiceType);
}