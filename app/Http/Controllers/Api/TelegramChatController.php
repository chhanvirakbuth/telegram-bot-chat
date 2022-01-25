<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TelegramChatController extends Controller
{
    public function webHook($token){
        // get update

        $update = $this -> getUpdate($token);
        if($update['ok']){
            // check if command is /id then send chat id to current chat
            $update = $update['result'][0];
            switch ($update['message']['text']){
                case  '/id':
                    $chat_id = $update['message']['chat']['id'];
                    $chat_type = $update['message']['chat']['type'];
                    $chat_title= $update['message']['chat']['title'];
                    $message = "
                    Chat id : <i>$chat_id</i>
$chat_type : <i>$chat_title</i>
                    ";
                    return $this -> sendMessage($token,$chat_id,urlencode($message));
                    break;
                default:
            }

        }


    }

    private function getUpdate($token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.telegram.org/$token/getUpdates?offset=-1",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
       return json_decode($response,true);

    }

    private function sendMessage($token,$chat_id,$message){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.telegram.org/$token/sendMessage?chat_id=$chat_id&text=$message&parse_mode=HTML",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response,true);
    }
}
