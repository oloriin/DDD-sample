<?php
namespace GatewayLayerBundle\Test\Resource\HttpClientFixture;

class VkGatewayFixture
{
    public static $callbackMessage = '
        { 
           "type":"message_new", 
           "object":{ 
              "id":694, 
              "date":1499441696, 
              "out":0, 
              "user_id":123456, 
              "read_state":0, 
              "title":" ... ", 
              "body":"start" 
           }, 
           "group_id":1, 
           "secret":"sjr948dff3kjnfd3" 
        }
    ';

    public static $badApiToken = [
        'status'=> 200,
        'body'  => '
        {
            "error": {
                "error_code": 5,
                "error_msg": "User authorization failed: invalid access_token (4).",
                "request_params": [
                    {
                        "key": "oauth",
                        "value": "1"
                    },
                    {
                        "key": "method",
                        "value": "messages.send"
                    },
                    {
                        "key": "user_id",
                        "value": "134416935"
                    },
                    {
                        "key": "message",
                        "value": "shj"
                    }
                ]
            }
        }
    '];

    public static $sendMessageSuccessResponse = [
        'status'    => 200,
        'body'      => '
            {
                "response": 7
            }'
    ];
}
