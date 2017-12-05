<?php
namespace DomainLayer\MessageService;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MessageServiceGatewayType extends AbstractEnumType
{
    const SMSPROFI          = 'Smsprofi';
    const ALLOKA_PHONE      = 'Alloka_phone';
    const CARROT_EMAIL      = 'Carrot_email';
    const WHATSUP           = 'Whatsup';
    const TELEGRAM          = 'Telegram';
    const CARROT_WEB_CHAT   = 'CarrotQuest_web_chat';
    const CARROT_POPUP      = 'CarrotQuest_popup';
    const SKYPE             = 'Skype';
    const INSTAGRAM_DIRECT  = 'Instagram_direct';
    const VKONTAKTE         = 'Vkontakte';
    const FACEBOOK          = 'Facebook';

    protected static $choices = [
        self::SMSPROFI          => 'Smsprofi',
        self::ALLOKA_PHONE      => 'Alloka_phone',
        self::CARROT_EMAIL      => 'Carrot_email',
        self::WHATSUP           => 'Whatsup',
        self::TELEGRAM          => 'Telegram',
        self::CARROT_WEB_CHAT   => 'CarrotQuest web chat',
        self::CARROT_POPUP      => 'CarrotQuest popup',
        self::SKYPE             => 'Skype',
        self::VKONTAKTE         => 'Vkontakte',
        self::FACEBOOK          => 'Facebook',
        self::INSTAGRAM_DIRECT  => 'Instagram_direct',
    ];

    private static $gatewayType = [
        self::SMSPROFI          => MessageServiceType::SMS,
        self::ALLOKA_PHONE      => MessageServiceType::PHONE,
        self::CARROT_EMAIL      => MessageServiceType::EMAIL,
        self::WHATSUP           => MessageServiceType::WHATSAPP,
        self::TELEGRAM          => MessageServiceType::TELEGRAM,
        self::CARROT_WEB_CHAT   => MessageServiceType::WEB_CHAT,
        self::CARROT_POPUP      => MessageServiceType::POP_UP,
        self::SKYPE             => MessageServiceType::SKYPE,
        self::VKONTAKTE         => MessageServiceType::VKONTAKTE,
        self::FACEBOOK          => MessageServiceType::FACEBOOK,
    ];

    public static function getMessageServiceType(string $gatewayType): ?string
    {
        if (isset(static::$gatewayType[$gatewayType])) {
            return static::$gatewayType[$gatewayType];
        }

        return null;
    }
}
