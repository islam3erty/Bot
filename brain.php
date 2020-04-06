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

public function sendMessage($opc, $msg, $button = null){

		if($button != null){
			$encode = json_encode($button, true);
			$parametro = array(
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"text"=>$msg,
				'reply_markup'=>$encode
			);
		
		}else{

			$parametro = array(
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"text"=>$msg,
			);
		}
}

protected function pensador($opc){

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

		$message = $array['frase']."\n\n".$array['autor'];
		$this->sendChatAction($opc, 'typing');
		$this->editMessage($opc, $message, $this->str->falas['pensador']);

		return true;
	}

}

class strings {

	public $falas = [

		"dia"=>"Bom dia Menina Lasse\nComo está lindissíma Dona do meu Criador?\n Como já deve saber vim deixar-lhe a frase das manhãs.\n\n",
		"tarde"=>"Boa Tarde Senhorita\nComo está? Eu estou bem, espero que a senhora também.\nVim trazer-lhe a frase da tarde\n\n",
		"noite"=>"Boa noite Safada, sou eu Luce. Mentira não sou.\nA Frase que o meu criador me programaou pra mandar essa noite:\n\n",
	];
}


$motor = new Luce();
$motor->sendMessage($opc, $opc['chat_id']);

?>