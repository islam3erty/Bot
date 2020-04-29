<?php

	require "freeClass.php";

	define("BOT_TOKEN", "833445680:AAGjpwc2TMP2RMXv0G04meBpdluL-qRmKsU");
	define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
	define("WEBHOOK_URL", "https://botip.herokuapp.com/free.php");

	$receive = file_get_contents("php://input");
	$decode = json_decode($receive, true);
	$mensagem = $decode["message"];

	$opc["chat_id"]=600217408;
	$opc["texto"] = $mensagem['text'];
	$opc["message_id"] = $mensagem["message_id"]+2;
	$opc["reply_markup"] = $mensagem["reply_markup"];

	$start = new Bot();
	$buttons = new Strings();

	$final_markup = array_push($opc["reply_markup"]["inline_keyboard"], $buttons->falas["contact"]);

	if(isset($decode["callback_query"])){
		$start->callback($update["callback_query"]);
	}

	switch ($opc["texto"]) {
		case 'start':
			$start->sendMessage($opc, "Eai Viado Como vai?", $buttons->falas["inline"]);
			break;
		
		case "edit":
			$start->editReply_Markup($opc, $final_markup);
			break;
		default:
			sleep(1);
			break;
	}
?>