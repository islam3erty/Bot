<?php
require "curl.php";
$motor = new Engine();
$strings = new Strings();
define("BOT_TOKEN", "765733425:AAGoczJFfcw23Uv-tLI7yWhTeh77oxKCKSE");
define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
define("WEBHOOK_URL", "https://botip.herokuapp.com/botip.php");
$conteudo = file_get_contents("php://input");
$update = json_decode($conteudo, TRUE);
$mensagem = $update["message"];
$opc = [];
$opc["chat_id"]=$mensagem["chat"]["id"];
$opc["texto"] = $mensagem["text"];
$opc["message_id"] = $mensagem["message_id"]-1;

if(isset($update["callback_query"])){
	$motor->callback($update["callback_query"]);
}


//Metodo Get pra quem quiser simplicidade. Mais n faz quebra de linhas. by C̶o̶m̶e̶n̶t̶a̶d̶o̶r̶ 
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
if ($opc["texto"] === "/start"){
	
	$motor->env($opc, $strings->falas["start"]);
	$motor->env($opc, $opc["message_id"]);
	$motor->editMessage($opc, "Ola eu modifiquei a mensagem", null);

}else if(substr($opc["texto"], 0, 4)==="/bin"){
	$bin = substr($opc["texto"], 5, 10);

	$motor->env($opc, $motor->bin($bin));
	

}else if($opc["texto"] === "/acerca"){
	
	//$motor->env($opc, $strings->falas["acerca"]);
	
	$motor->env($opc, $motor->card(10, "|"));

}else if($opc["texto"] === "/sobre"){
	
	$motor->env($opc, $strings->falas["sobre"]);

}else if($opc["texto"] === "/tools"){
	
	$motor->env($opc, $strings->falas["ferramentas"]);

}else if($opc["texto"] === "/ccgen"){
	
	$motor->keyboard($opc, "*Escolha a sua bandeira*", $strings->falas["bandeiras"]);

}else if($opc["texto"] === "/bgen"){
	$motor->env($opc, $motor->binGen());

}elseif(substr($opc["texto"], 0, 3)==="/ip"){
	$ip = substr($opc["texto"], 4, 19);

	if(strlen($ip)<9 & strlen($ip)>16){
		$motor->env($opc, $strings->falas["invalid"]);
	}else{
		$motor->env($opc, $motor->remoteIp($ip));
	}
}elseif($opc["texto"] === "/doc"){
	$motor->keyboard($opc, "*O que deseja gerar:*", $strings->falas["doc"]);

}elseif(substr($opc["texto"], 0, 4) === "/cep"){
	$cep = substr($opc["texto"], 5, 15);
	
	$motor->env($opc, $motor->cep($cep));
}

?>
