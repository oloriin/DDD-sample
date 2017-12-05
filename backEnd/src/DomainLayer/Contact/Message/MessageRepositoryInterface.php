<?php
namespace DomainLayer\Contact\Message;

use Doctrine\Common\Persistence\ObjectRepository;

interface MessageRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(Message $message);

    public function doBatchSave();

    public function save(Message $message);
}