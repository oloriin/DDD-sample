<?php
namespace DomainLayer\User;

use Doctrine\Common\Persistence\ObjectRepository;

interface UserRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(User $contact);

    public function doBatchSave();

    public function save(User $contact);
}