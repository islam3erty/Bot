<?php
	
	sleep(1);
	require "freeClass.php";

	$start = new Bot();

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

	if(isset($decode["callback_query"])){
		$start->callback($update["callback_query"]);
	}
	
	if($opc["chat_type"] == "private"){

		if(substr($opc["texto"], 0, 7) == "/change"){
			$buttons = new Strings();
			$novo = trim(substr($opc["texto"], 7));
			$lista = explode(" ", $novo);
			if(count($lista) > 1){
				if(isset($lista[2])){
					$start->editStrings($lista[0]." ".$lista[1], $lista[2]);
					$start->sendMessage($opc, "Changed");
				}else{
					$start->editStrings($lista[0], $lista[1]);
					$start->sendMessage($opc, "Changed");
				}
				
			}else{
				$start->sendMessage($opc, $buttons->falas["sintaxe"]);
			}

		}
	}
	
	if($opc["chat_type"] == "channel"){

		$buttons = new Strings();
		array_push($opc["reply_markup"]["inline_keyboard"], $buttons->falas["contact"]);

		if(isset($mensagem["photo"])){

			$opc["file"] = $mensagem["photo"][0]["file_id"];
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

			$opc["file"] = $mensagem["video"]["file_id"];
			$opc["caption"] = $mensagem["video"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "video", $opc["reply_markup"]);

		}elseif(isset($mensagem["audio"])){

			$opc["file"] = $mensagem["audio"]["file_id"];
			$opc["caption"] = $mensagem["audio"]["caption"];

			$start->deleteMessage($opc);
			sleep(1);
			$start->sendFile($opc, $opc["file"], "audio", $opc["reply_markup"]);

		}elseif(isset($mensagem["voice"])){

			die;

		}elseif(isset($mensagem["animation"])){

			$opc["file"] = $mensagem["animation"]["file_id"];
			$opc["caption"] = $mensagem["animation"]["caption"];

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