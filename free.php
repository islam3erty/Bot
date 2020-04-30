<?php
	sleep(2);
	require "freeClass.php";

	define("BOT_TOKEN", "833445680:AAGjpwc2TMP2RMXv0G04meBpdluL-qRmKsU");
	define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
	define("WEBHOOK_URL", "https://botip.herokuapp.com/free.php");

	$receive = file_get_contents("php://input");
	$decode = json_decode($receive, true);
	$mensagem = $decode["edited_channel_post"];

	if(isset($mensagem["photo"])){
		$opc["photo"] = $mensagem["photo"]["file_id"];
		$opc["caption"] = $mensagem["photo"]["caption"];
	}

	$opc["chat_id"]="@latitudeDell";/*$mensagem["chat"]["username"]*/
	$opc["texto"] = $mensagem["text"];
	$opc["message_id"] = $mensagem["message_id"];
	$opc["reply_markup"] = $mensagem["reply_markup"];

	$start = new Bot();
	$buttons = new Strings();

	$final_markup = array_push($opc["reply_markup"]["inline_keyboard"], $buttons->falas["contact"]);

	if(isset($decode["callback_query"])){
		$start->callback($update["callback_query"]);
	}

	$start->sendMessage($opc, $opc["message_id"], $final_markup);
	
?>
