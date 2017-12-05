<?php
namespace GatewayLayerBundle\Test\Resource\HttpClientFixture;

class TelegramGatewayFixture
{
    private $webHookNewMessage = [
        'status'    => 200,
        'body'      => '
        {"update_id":574800766,
            "message":{
                "message_id":29,
                "from":{
                    "id":345302313,
                    "first_name":"Oloriin",
                    "language_code":"ru"
                },
                "chat":{
                    "id":345302313,
                    "first_name":"Oloriin",
                    "type":"private"
                },
                "date":1503375810,
                "text":"dfg"
            }
        }'
    ];

    private $getUpdates = [
        'status'    => 200,
        'body'      => '
            {
              "ok": true,
              "result": [
                {
                  "update_id": 931734054,
                  "message": {
                    "message_id": 23,
                    "from": {
                      "id": 345302313,
                      "first_name": "Oloriin",
                      "language_code": "ru"
                    },
                    "chat": {
                      "id": 345302313,
                      "first_name": "Oloriin",
                      "type": "private"
                    },
                    "date": 1495169139,
                    "text": "gvhjgh"
                  }
                },
                {
                  "update_id": 931734055,
                  "message": {
                    "message_id": 24,
                    "from": {
                      "id": 345302313,
                      "first_name": "Oloriin",
                      "language_code": "ru"
                    },
                    "chat": {
                      "id": 345302313,
                      "first_name": "Oloriin",
                      "type": "private"
                    },
                    "date": 1495169144,
                    "text": "34234534564yj675"
                  }
                }
              ]
            }
        '
    ];

    private $sendMessageRightResponse = [
        'status'=> 200,
        'body'  => '
            {
              "ok": true,
              "result": {
                "message_id": 11,
                "from": {
                  "id": 385483367,
                  "first_name": "dev_bot",
                  "username": "oloriin_dev_bot"
                },
                "chat": {
                  "id": 345302313,
                  "first_name": "Oloriin",
                  "type": "private"
                },
                "date": 1494890068,
                "text": "Привет проверка"
              }
            }
        '
    ];

    private $sendMessageChatNotFound = [
        'status' => 400,
        'body'   => '
            {
              "ok": false,
              "error_code": 400,
              "description": "Bad Request: chat not found"
            }
        '
    ];

    private $unauthorized = [
        'status'=> 401,
        'body'  => '
            {
              "ok": false,
              "error_code": 401,
              "description": "Unauthorized"
            }
        '
    ];

    /** @return array */
    public function getSendMessageRightResponse(): array
    {
        return $this->sendMessageRightResponse;
    }

    /** @return array */
    public function getSendMessageChatNotFound(): array
    {
        return $this->sendMessageChatNotFound;
    }

    /** @return array */
    public function getUnauthorized(): array
    {
        return $this->unauthorized;
    }

    /** @return array */
    public function getGetUpdates(): array
    {
        return $this->getUpdates;
    }

    /** @return array */
    public function getWebHookNewMessage(): array
    {
        return $this->webHookNewMessage;
    }
}
