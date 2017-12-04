<?php
namespace CoreDomain\MessageService;

use Doctrine\Common\Persistence\ObjectRepository;

interface MessageServiceRepositoryInterface extends ObjectRepository
{
    public function prepareBatchSave(MessageService $message);

    public function doBatchSave();

    public function save(MessageService $message);
}