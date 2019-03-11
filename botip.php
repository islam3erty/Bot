<?php


require "curl.php";
$motor = new Engine();
$strings = new Strings();


define("BOT_TOKEN", "765733425:AAGoczJFfcw23Uv-tLI7yWhTeh77oxKCKSE");
define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");

$conteudo = file_get_contents("php://input");
$update = json_decode($conteudo, TRUE);
$mensagem = $update["message"];

$chat_id=$mensagem["chat"]["id"];
$texto = $mensagem["text"];




/*if ($texto === "/start"){
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=".$strings->falas["start"]);
}else if(substr($texto, 0, 4)==="/bin"){
	$bin = substr($texto, 5, 10);
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=".$motor->bin($bin));
}else if($texto === "/acerca"){
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=".$strings->falas["acerca"]);
}else if($texto === "/sobre"){
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=".$strings->falas["sobre"]);
}else if($texto === "/ferramentas"){
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=".$strings->falas["ferramentas"]);
}else{
	file_get_contents(API_URL."sendmessage?chat_id=".$chat_id."&text=Função desconhecida");
}*/

if ($texto === "/start"){
	
	$motor->env($chat_id, $strings->falas["start"]);

}else if(substr($texto, 0, 4)==="/bin"){
	$bin = substr($texto, 5, 10);
	
	$motor->env($chat_id, $motor->bin($bin));

}else if($texto === "/acerca"){
	
	$motor->env($chat_id, $strings->falas["acerca"]);

}else if($texto === "/sobre"){
	
	$motor->env($chat_id, $strings->falas["sobre"]);
}else if($texto === "/ferramentas"){
	
	$motor->env($chat_id, $strings->falas["ferramentas"]);
}else{
	
	$motor->env($chat_id, "Função desconhecida");
}





?>
