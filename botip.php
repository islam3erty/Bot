<?php
require "curl.php";
$motor = new Engine();
$strings = new Strings();

if ($motor->opc["texto"] === "/start"){
	
	$motor->env($motor->opc, $strings->falas["start"]);
	$motor->env($motor->opc, $motor->opc["message_id"]);
	sleep(3);
	

}else if(substr($motor->opc["texto"], 0, 4)==="/bin"){
	$bin = substr($motor->opc["texto"], 5, 10);

	$motor->env($motor->opc, $motor->bin($bin));
	

}else if($motor->opc["texto"] === "/acerca"){
	
	//$motor->env($motor->opc, $strings->falas["acerca"]);
	
	$motor->env($motor->opc, $motor->card(10, "|"));

}else if($motor->opc["texto"] === "/sobre"){
	
	$motor->editMessage($motor->opc, $strings->falas["sobre"]);

}else if($motor->opc["texto"] === "/tools"){
	
	$motor->editMessage($motor->opc, $strings->falas["ferramentas"]);

}else if($motor->opc["texto"] === "/ccgen"){
	
	$motor->keyboard($motor->opc, "*Escolha a sua bandeira*", $strings->falas["bandeiras"]);

}else if($motor->opc["texto"] === "/bgen"){
	$motor->editMessage($motor->opc, $motor->binGen());

}elseif(substr($motor->opc["texto"], 0, 3)==="/ip"){
	$ip = substr($motor->opc["texto"], 4, 19);

	if(strlen($ip)<9 & strlen($ip)>16){
		$motor->env($motor->opc, $strings->falas["invalid"]);
	}else{
		$motor->env($motor->opc, $motor->remoteIp($ip));
	}
}elseif($motor->opc["texto"] === "/doc"){
	$motor->keyboard($motor->opc, "*O que deseja gerar:*", $strings->falas["doc"]);

}elseif(substr($motor->opc["texto"], 0, 4) === "/cep"){
	$cep = substr($motor->opc["texto"], 5, 15);
	
	$motor->env($motor->opc, $motor->cep($cep));
}

?>
