<?php
	
	require "freeClass.php";

	$start = new Bot();
	$buttons = new Strings();

	define("BOT_TOKEN", "833445680:AAGjpwc2TMP2RMXv0G04meBpdluL-qRmKsU");
	define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
	define("WEBHOOK_URL", "https://botip.herokuapp.com/free.php");

	$opc = array();
	$receive = file_get_contents("php://input");
	$decode = json_decode($receive, true);

	if(isset($decode["message"]) && !empty($decode["message"])){
		$mensagem = $decode["message"];
		$opc["chat_id"] = $mensagem["chat"]["id"];
	}else{
		$mensagem = $decode["edited_channel_post"];
		$opc["chat_id"]="@".$mensagem["chat"]["username"];
		$opc["reply_markup"] = $mensagem["reply_markup"];
	}


	$opc["texto"] = $mensagem["text"];
	$opc["message_id"] = $mensagem["message_id"];
	$opc["chat_type"] = $mensagem["chat"]["type"];

	array_push($opc["reply_markup"]["inline_keyboard"], $buttons->falas["contact"]);

	if(isset($decode["callback_query"])){
		$start->callback($update["callback_query"]);
	}
	
	if($opc["chat_type"] == "private"){

		if(substr($opc["texto"], 0, 4) == "/txt"){

			$novo = trim(substr($opc["texto"], 4));
			$buttons->setButtons($novo, "text");
			$start->sendMessage($opc, "Button Text Changed");

		}elseif(substr($opc["texto"], 0, 4) == "/url"){
			
			$novo = trim(substr($opc["texto"], 4));
			$buttons->setButtons($novo, "url");
			$start->sendMessage($opc, "URL Changed");
		}
	}
	
	if($opc["chat_type"] == "channel"){

		if(isset($mensagem["photo"])){

			$opc["file"] = $mensagem["photo"][0]["file_unique_id"];
			$opc["caption"] = $mensagem["photo"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "photo", $opc["reply_markup"]);

		}elseif(isset($mensagem["document"])){

			$opc["file"] = $mensagem["document"]["file_id"];
			$opc["caption"] = $mensagem["document"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "document", $opc["reply_markup"]);

		}elseif(isset($mensagem["video"])){

			$opc["file"] = $mensagem["video"]["file_unique_id"];
			$opc["caption"] = $mensagem["video"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "video", $opc["reply_markup"]);

		}elseif(isset($mensagem["audio"])){

			$opc["file"] = $mensagem["photo"]["file_unique_id"];
			$opc["caption"] = $mensagem["photo"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "audio", $opc["reply_markup"]);

		}elseif(isset($mensagem["voice"])){

			$opc["file"] = $mensagem["photo"]["file_unique_id"];
			$opc["caption"] = $mensagem["photo"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "voice", $opc["reply_markup"]);

		}elseif(isset($mensagem["animation"])){

			$opc["file"] = $mensagem["photo"]["file_unique_id"];
			$opc["caption"] = $mensagem["photo"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "animation", $opc["reply_markup"]);

		}else{
			$start->deleteMessage($opc);
			sleep(1);
			$start->sendMessage($opc, $opc["texto"], $opc["reply_markup"]);
		}
		
	}
	
?>