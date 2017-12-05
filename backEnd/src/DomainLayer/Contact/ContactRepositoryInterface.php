<?php
namespace DomainLayer\Contact;

use Doctrine\Common\Persistence\ObjectRepository;

interface ContactRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(Contact $contact);

    public function doBatchSave();

    public function save(Contact $contact);
}