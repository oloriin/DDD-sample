<?php
namespace DomainLayer\Contact\ContactExternalAccount;

use Doctrine\Common\Persistence\ObjectRepository;

interface ContactExternalAccountRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(ContactExternalAccount $contactExternalAccount);

    public function doBatchSave();

    public function save(ContactExternalAccount $contactExternalAccount);
}