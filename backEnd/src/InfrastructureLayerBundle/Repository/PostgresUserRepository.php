<?php

namespace InfrastructureLayerBundle\Repository;

use DomainLayer\User\User;
use DomainLayer\User\UserRepositoryInterface;

class PostgresUserRepository extends \Doctrine\ORM\EntityRepository implements UserRepositoryInterface
{

    public function prepareBatchSave(User $user)
    {
        $this->_em->persist($user);
    }

    public function doBatchSave()
    {
        $this->_em->flush();
    }

    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush($user);
    }
}
