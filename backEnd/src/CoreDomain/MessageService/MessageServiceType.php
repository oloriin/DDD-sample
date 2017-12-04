<?php
namespace CoreDomain\MessageService;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MessageServiceType extends AbstractEnumType
{
    const SMS           = 'Sms';
    const EMAIL         = 'Email';
    const PHONE         = 'Phone';
    const POP_UP        = 'Pop-up';
    const WHATSAPP      = 'Whatsapp';
    const TELEGRAM      = 'Telegram';
    const WEB_CHAT      = 'Web chat';
    const UNDEFINED     = 'Undefined';
    const SKYPE         = 'Skype';
    const INSTAGRAM_DIRECT  = 'Instagram_direct';
    const VKONTAKTE     = 'Vkontakte';
    const FACEBOOK      = 'Facebook';

    protected static $choices = [
        self::SMS           => 'Sms',
        self::EMAIL         => 'Email',
        self::PHONE         => 'Phone',
        self::POP_UP        => 'Pop-up',
        self::WHATSAPP      => 'Whatsapp',
        self::TELEGRAM      => 'Telegram',
        self::WEB_CHAT      => 'Web chat',
        self::UNDEFINED     => 'Undefined',
        self::SKYPE         => 'Skype',
        self::INSTAGRAM_DIRECT  => 'Instagram_direct',
        self::VKONTAKTE     => 'Vkontakte',
        self::FACEBOOK      => 'Facebook',
    ];
}
