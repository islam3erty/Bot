<?php
class Engine {
	public function __construct(){
		$this->str = new Strings();
	}
	//public function getNews(){
		//$json = file_get_contents("https://newsapi.org/      v2/top-headlines?sources=google-news-br&apiKey=9f8c49a46a4d457082730c4b8d9e2a9a");
	//}
	
	public function remoteIp($ip){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/".$ip."?token=03a2079b5357d1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$json = json_decode($output);
		return "ip: ".$json->ip."\ncidade: ".$json->city."\nRegião: ".$json->region."\nPais: ".$json->country."\nLatitude e Longitude: ".$json->loc."\nCodigo postal: ".@$json->postal."\nServidor da: ".$json->org;
	}

	public function genCC($tipo){
		
		$ch= curl_init();
		$url = "https://api.bincodes.com/cc-gen/json/e09351d196aa31f07d053f3571fef571/".$tipo."/";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$resposta = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$json = json_decode($resposta);

		return $json->card."\n".$json->number;
	}
	//Tive que repetir esse metodo porque n achei uma logica para fazer atraves do genCC...
	public function binGen(){

		$bandeiras=[
			"mastercard",
			"visa",
			"amex",
			"jcb",
			"diners",
			"maestro"
		];
		$chose = array_rand($bandeiras, 1);
		
		$ch= curl_init();
		$url = "https://api.bincodes.com/cc-gen/json/e09351d196aa31f07d053f3571fef571/".$bandeiras[$chose]."/";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$resposta = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$json = json_decode($resposta);

		return "BIN".$json->card."\n".substr($json->number, 0,6);
	}


	public function apiRequest($metodo, $param){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, API_URL.$metodo."?");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-Type" => "multipart/form-data"
		]);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$resp = curl_exec($ch);
		curl_close($ch);
	
}
	public function env($opc, $msg){
        $param = [
            "chat_id" => $opc["chat_id"],
            "disable_web_page_preview" => 1,
            "parse_mode" => "Markdown",
            "text" => $msg
        ];
        
        $this->apiRequest("sendMessage", $param);
	}

	public function keyboard($opc, $msg){
		$reply=[
			"inline_keyboard"=>array(array("text"=>"Ola", "url"=>"google.com"))
		];

		$encode=json_encode($reply, true);
		
		$data = [
			'chat_id' => $opc["chat_id"],
			'text' => $msg,
			"reply_markup"=>$reply
			
		];
    		

		$this->apiRequest("sendMessage", $data);
	}
	
	public function bin($bin){
		$ch = curl_init();
		$url = "https://lookup.binlist.net/".$bin;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Version: 3"));
		$resposta = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$json = json_decode($resposta);
		return "*Bandeira:* ".$json->scheme."\n*Tipo:* ".$json->type."\n*Nivel:* ".$json->brand."\n*País:* ".$json->country->name."(".$json->country->emoji.")"."\n*Latitude:* ".$json->country->latitude."\n*Longitude:* ".$json->country->longitude."\n*Banco:* ".$json->bank->name."\n*Website:* ".$json->bank->url."\n*Phone:* ".$json->bank->phone."\n*Cidade:* ".$json->bank->city;
	}


}
class Strings
{
	public $falas = [
		"start"=>"*Sou programado para fazer varias coisas legais. clique no comando* /ferramentas para saber todas minhas funcionalidades e introduza os comandos de acordo como está exemplificado.\nSe ainda n sabe o que é uma BIN clique no comando /acerca.\n Tem Duvidas? clique no comando /sobre.",
		"acerca"=>"bin são os primeiros seis números de um cartão do banco que identificam a bandeira do cartão, o tipo, o país, o número de telefone do banco entre outras informações.BIN quer dizer Bank Identification Number.\n\nUm Endereço de Protocolo da Internet (Endereço IP), do inglês Internet Protocol address (IP address), é um rótulo numérico atribuído a cada dispositivo (computador, impressora, smartphone etc.) conectado a uma rede de computadores que utiliza o Protocolo de Internet para comunicação.[1] Um endereço IP serve a duas funções principais: identificação de interface de hospedeiro ou de rede e endereçamento de localização ex: 159.89.157.64.",
		"sobre"=>"Criador: ̶C̶o̶m̶e̶n̶t̶a̶d̶o̶r̶ | https://t.me/Comentered.\n\nLinguagem: PHP Wsociety@",
		"ferramentas"=>"_Ferramentas_:\n*Checar Bin:* `/bin 404528`\n*Gerar Cartão de Credito:* `/ccgen`\n*Gerar Bin:* `/bgen`\n*Geolocalizar ip:* `/ip 159.89.157.64`",
		"bandeiras"=>"*Escolha a bandeira da cc que deseja gerar:* \n`/mastercard\n/visa\n/amex\n/jcb\n/diners\n/maestro`",
		"sintaxes"=>"Formato incoreto. Insira o comando no seguinte formato:\n\n/bin xxxxxx\n\n em que:\n\n/bin é o comando\n\n xxxxxx são os 6 números da bin que deseja checar"
		

	];
}
?>
