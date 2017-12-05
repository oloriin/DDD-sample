<?php
namespace DomainLayer\Contact;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class ContactPresenceType extends AbstractEnumType
{
    const ONLINE    = 'ONLINE';
    const IDLE      = 'IDLE';
    const OFFLINE   = 'OFFLINE';

    protected static $choices = [
        self::ONLINE    => 'В сети',
        self::IDLE      => 'Бездействует',
        self::OFFLINE   => 'Не в сети',
    ];
}