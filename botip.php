<?php

if(session_id() == ""){
	session_start();
}
require "curl.php";
$motor = new Engine();
$strings = new Strings();
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
	sleep(2);
	$motor->get_id($update);
}else if(substr($opc["texto"], 0, 4)==="/bin"){
	$bin = substr($opc["texto"], 5, 10);
	$motor->env($opc, $motor->bin($bin));
	
}else if($opc["texto"] === "/acerca"){
	$motor->get_id($update);
}else if($opc["texto"] === "/sobre"){
	
	$motor->editMessage($opc, $strings->falas["sobre"]);
}else if($opc["texto"] === "/tools"){
	
	$motor->editMessage($opc, $strings->falas["ferramentas"]);
}else if($opc["texto"] === "/ccgen"){
	
	$motor->keyboard($opc, "*Escolha a sua bandeira*", $strings->falas["bandeiras"]);
}else if($opc["texto"] === "/bgen"){
	$motor->editMessage($opc, $motor->binGen());
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
