<?php

class Luce {

public $str;

public function __construct(){
	$this->str = new strings();
}

protected function apiRequest($metodo, $parametro){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, API_URL.$metodo."?");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type" => "multipart/form-data"
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametro));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$resultado = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
}

public function sendMessage($opc, $msg){

			$parametro = array(
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"text"=>$msg,
			);
		
		$this->apiRequest("sendMessage", $parametro);
}

public function editMessage($opc, $msg, $button=null){
	
	$encode = json_encode($button, true);
	$parametro = [
		"chat_id"=>$opc["chat_id"],
		"message_id"=>$opc["message_id"],
		"text"=>$msg,
		"parse_mode"=>"Markdown",
		"reply_markup"=>$encode,
	];

	$this->apiRequest("editMessageText", $parametro);
}

public function sendChatAction($opc, $action){

	$parametro = array(
		"chat_id"=>$opc["chat_id"],
		"action"=>$action
	);

	$this->apiRequest("sendChatAction", $parametro);
}

public function pensador($opc){

		$hora = new datetime();
		$change = $hora->format("H:i:s");
		$explode = explode(":", $change);
		$falou;
		if(intval($explode[0])+1 < 12){
			$falou = $this->str->falas["dia"];
		}elseif (intval($explode[0])+1 >= 12 && intval($explode[0])+1 <= 18) {
			$falou = $this->str->falas["tarde"];
		}else{
			$falou = $this->str->falas["noite"];
		}
		$pagina = rand(0, 10);
		$div = rand(0,20);

		$categorias = [

			'frases_inteligentes',
			'frases_bonitas',
			'frases_buda_felicidade',
			'frases_de_reflexao',
			'frases_de_motivacao',
			'frases_de_bob_marley',
			'frases_de_conforto',
			'frases_de_tristeza',
			'frases_para_pessoas_com_depressao',
			'frases_lindas',
			'frases_curtir_a_vida'
		];

		$catego = array_rand($categorias);

		if($pagina == 0){

			$url = 'https://www.pensador.com/'.$catego;
		}else{

			$url = 'https://www.pensador.com/'.$catego.'/'.$pagina.'/';
		}

		$array = array();
		$html = file_get_html($url);

		$array['frase'] = $html->find('div[class=thought-card] p', $div)->plaintext;
		$array['autor'] = $html->find('div[class=thought-card span', $div)->plaintext;

		$message = $falou.$array['frase']."\n\n".$array['autor']."\n".$this->str->falas["Eu"];		
		$this->sendChatAction($opc, 'typing');
		$this->sendMessage($opc, $message);
		$this->sendMessage($opc, $explode[0]);
		return true;
	}

}

class strings{

	public $falas = [

		"dia"=>"*Bom dia Menina Lasse\u{1F604}\nComo está lindissíma Dona do meu Criador?\u{1F60D}\nComo já deve saber vim deixar-lhe a frase das manhãs.\u{2709}\u{270F}*\n\n",
		"tarde"=>"*Boa Tarde Senhorita\u{1F60C}\nComo está? Eu estou bem, espero que a senhora também.\u{1F606}\nVim trazer-lhe a frase da tarde.\u{2709}\u{270F}*\n\n",
		"noite"=>"*Boa noite Safada, sou eu Luce. Mentira não sou\u{1F60E}.\nA Frase que o meu criador me programou pra mandar essa noite\u{2709}\u{270F}:*\n\n",
		"Eu"=>"*Já sabe né? Ele te ama*",
	];
}



?>
