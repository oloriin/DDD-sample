<?php
namespace CoreDomain\Contact\Message;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MessageSenderAppType extends AbstractEnumType
{
    const WEB_PANEL     = 'Web panel';
    const APP           = 'App';
    const DISTRIBUTION  = 'Distribution';
    const TRIGGER       = 'Trigger';
    const API           = 'API';
    const UNDEFINED     = 'Undefined';

    protected static $choices = [
        self::WEB_PANEL     => 'Web panel',
        self::APP           => 'Приложение',
        self::DISTRIBUTION  => 'Рассылка',
        self::TRIGGER       => 'Автособщение (Цепочки)',
        self::UNDEFINED     => 'Undefined',
        self::API           => 'API',
    ];
}