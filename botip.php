<?php

include "curl.php";
include "parser.php";
$curl = new Engine();

define("BOT_TOKEN", "765733425:AAGoczJFfcw23Uv-tLI7yWhTeh77oxKCKSE");
define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");

function ler($message){
	$message_id = $message['message_id'];
	$chat_id = $message['chat']['id'];

	




?>