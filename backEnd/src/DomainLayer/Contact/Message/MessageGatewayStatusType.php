<?php
namespace DomainLayer\Contact\Message;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MessageGatewayStatusType extends AbstractEnumType
{
    const CREATED       = 'Created';
    const SENT          = 'Sent';
    const DELIVERED     = 'Delivered';
    const NOT_DELIVERED = 'Not delivered';
    const THEY_SAY      = 'They say';
    const DID_NOT_ANSWER= 'Did not answer';


    protected static $choices = [
        self::CREATED        => 'Созданно',
        self::SENT           => 'Отправленно',
        self::DELIVERED      => 'Доставленно',
        self::NOT_DELIVERED  => 'Не доставленно',
        self::THEY_SAY       => 'Говорят',
        self::DID_NOT_ANSWER => 'Не ответил',
    ];

}