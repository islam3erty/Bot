<?php
require "simple_html_dom.php";
require "brain.php";
define("BOT_TOKEN", "1111771071:AAFmfJy_4KyYB_FTa_i4iPNwiOQKN3OyjKE");
define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
define("WEBHOOK_URL", "https://half.herokuapp.com/half.php");

$conteudo = file_get_contents("php://input");
$update = json_decode($conteudo, true);
$mensagem = $update['message'];

$opc = [];
$opc["chat_id"] = $mensagem["chat"]["id"];
$opc["texto"] = $mensagem['text'];
$opc["message_id"] = $mensagem["message_id"]+2;;

/*if(isset($update['callback_query'])){
	$engine->callback($update["callback_query"]);
}*/

?>