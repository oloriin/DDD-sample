<?php
namespace CoreDomain\Contact\Message;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MessageDirectionType extends AbstractEnumType
{
    const IN        = 'In';
    const OUT       = 'Out';
    const SYSTEM    = 'System';

    protected static $choices = [
        self::IN        => 'In',
        self::OUT       => 'Out',
        self::SYSTEM    => 'System',
    ];
}