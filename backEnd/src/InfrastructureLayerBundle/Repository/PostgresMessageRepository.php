<?php

namespace InfrastructureLayerBundle\Repository;

use DomainLayer\Contact\Message\Message;
use DomainLayer\Contact\Message\MessageRepositoryInterface;

/**
 * MessageRepository
 * 
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostgresMessageRepository extends \Doctrine\ORM\EntityRepository implements MessageRepositoryInterface
{
    public function prepareBatchSave(Message $message)
    {
        $this->_em->persist($message);
    }

    public function doBatchSave()
    {
        $this->_em->flush();
    }

    public function save(Message $message)
    {
        $this->_em->persist($message);
        $this->_em->flush($message);
    }
}
