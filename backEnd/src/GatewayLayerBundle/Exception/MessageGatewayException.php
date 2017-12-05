<?php
namespace GatewayLayerBundle\Exception;

class MessageGatewayException extends \Exception
{
    public function __construct($msg = null)
    {
        $text = "Критическая ошибка обмена сообщениями. \n".$msg;
        parent::__construct($text);
    }
}
