<?php

/*include "curl.php";
$curl = new Engine();
$strings = new Strings();

define("BOT_TOKEN", "765733425:AAGoczJFfcw23Uv-tLI7yWhTeh77oxKCKSE");
define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");

function process($message){
		$chat_id=$message['chat']['id'];
		$message_id=$message["message_id"];

		if(isset($message['text'])){
			$text = $message['text'];

			if(strpos($text, "/start")){
				$param = [
					"chat_id"=>$chat_id, 
					"text"=> $strings->str->falas['start'].
					" Para além do seu Ip pode saber sobre outro ip basta informa-lo."];
				$ip = [
					"chat_id"=>$chat_id,
					"text"=>$curl->getDetails()
				];

				$curl->enviarMensagem('sendMessage', $param);
				$curl->enviarMensagem('sendMessage', $ip);

			}
		}else{
			if($text === "/instruções"){
				$strings->falas["instruções"];
			}else if($text ==="/sobre"){
				$strings->fala["sobre"];
			}else{
				$strings->falas["processando"]
				$curl->remoteIp($text);
			}
			
		}

	}

$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);

if(isset($update["message"])){
	process($update["message"]);
}*/

define("BOT_TOKEN", "765733425:AAGoczJFfcw23Uv-tLI7yWhTeh77oxKCKSE");
define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");

$conteudo = file_get_contents("php://input");
$update = json_decode($conteudo, TRUE);
$mensagem = $update["message"];

$chat_id=$mensagem["chat"]["id"];
$texto = $mensagem["text"];

$reply = "eu sou o cara mais foda do mundo";

if($texto === "/start"){
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=".$reply);
}



?>
