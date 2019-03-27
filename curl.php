<?php
class Engine {
	public $str;
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

		return "BIN ".$json->card."\n".substr($json->number, 0,6);
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

	public function keyboard($opc, $msg, $botao){
		

		$encode=json_encode($botao, true);
		
		$data = [
			'chat_id' => $opc["chat_id"],
			'text' => $msg,
			"reply_markup"=>$encode,
			"parse_mode"=>"Markdown"
			
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

	protected function answercallback($callback_id, $alert, $time, $text){
		$param = array(
			"callback_query_id"=>$callback_id,
			"show_alert"=>$alert,
			"cache_time"=>$time,
			"text"=>$text
		);

		$this->apiRequest("answerCallbackQuery", $param);
	}

	public function callback($callback){
		
			$cb_chat_id = $callback["message"]["chat"]["id"];
			$cb_message_id = $callback["message"]["id"];
			$cb_id = $callback["id"];
			$cb_data = $callback["data"];

			$opc = [
				"chat_id"=>$cb_chat_id,
				"msg_id"=>$cb_message_id


			];

			if($cb_data == "Visa"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->genCC("visa"));

			}elseif($cb_data == "Mastercard"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->genCC("mastercard"));
			
			}elseif($cb_data == "Amex"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->genCC("amex"));
			
			}elseif($cb_data == "Diners"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->genCC("diners"));
			
			}elseif($cb_data == "Maestro"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->genCC("maestro"));
			
			}elseif($cb_data == "Jcb"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->genCC("jcb"));
			
			}elseif($cb_data == "cpf"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->gerarValidar("cpf"));
			
			}elseif($cb_data == "cnpj"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->gerarValidar("cnpj"));
			
			}elseif($cb_data == "cns"){
				$text = null;

				$this->answercallback($cb_id, false, 3, $text);
				$this->env($opc, $this->gerarValidar("cns"));
			
			}elseif($cb_data == "cep"){
				$this->env($opc, $this->str->falas["cep"]);
				$text = null;
				$this->answercallback($cb_id, false, 3, $text);
			}



	}

	public function WebHook($wh){
        if($wh == "on"){
            $param = [
                "url" => WEBHOOK_URL,
                "max_connections" => 100,
                "allowed_updates" => array("message", "callback_query")
            ];
            return $this->apiRequest("setWebhook", $param);
        }elseif($wh == "off"){
            $param = [ "url" => "delete" ];
            return $this->apiRequest("setWebhook", $param);
        }elseif($wh == "info"){
            return $this->apiRequest("getWebhookInfo", array());
        }
    }

    public function gerarValidar($doc){

    	
		$endpoint="http://geradorapp.com/api/v1/".$doc."/generate?token=72f87a0206bce9b1cd3d18038808345d";
    	
    	$ch = curl_init();

    	curl_setopt($ch, CURLOPT_URL, $endpoint);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
    	$resposta = curl_exec($ch);
    	curl_close($ch);

    	$decode = json_decode($resposta);

    	return "*".$doc.": *".$decode->data->number_formatted."\n\n"."\u{1F5A8}".$decode->data->message;



    }

    public function cep($cep){

    	$ch = curl_init("http://geradorapp.com/api/v1/cep/search/".$cep."?token=72f87a0206bce9b1cd3d18038808345d");

    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
    	$resposta = curl_exec($ch);
    	curl_close($ch);

    	$decode = json_decode($resposta);

    	if($decode->status == 0){
    		return $decode->data->message;
    	}else{
    		return "*CEP:* ".$decode->data->number."\n*Estado:* ".$decode->data->state_name."(".$decode->data->state.")"."\n*Cidade:* ".$decode->data->city."\n*Bairro:* ".$decode->data->district."\n*Rua/Avenida:* ".$decode->data->address."\n*Nome do local:* ".$decode->data->address_name."\n*Codigo da cidade:* ".$decode->data->city_code;
    	}
    }


}
class Strings
{
	public $falas = [
		
		"doc"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"\u{1F39F} CPF", "callback_data"=>"cpf")), 
				array(array("text"=>"\u{1F39F} CNPJ", "callback_data"=>"cnpj")), 
				array(array("text"=>"\u{1F39F} CNS", "callback_data"=>"cns")), 
				array(array("text"=>"\u{1F39F} Localizar CEP", "callback_data"=>"cep")) 
			)
		),

		"start"=>"*Sou programado para fazer varias coisas legais. clique no comando* /ferramentas para saber todas minhas funcionalidades e introduza os comandos de acordo como está exemplificado.\nSe ainda n sabe o que é uma BIN clique no comando /acerca.\n Tem Duvidas? clique no comando /sobre.",
		"acerca"=>"bin são os primeiros seis números de um cartão do banco que identificam a bandeira do cartão, o tipo, o país, o número de telefone do banco entre outras informações.BIN quer dizer Bank Identification Number.\n\nUm Endereço de Protocolo da Internet (Endereço IP), do inglês Internet Protocol address (IP address), é um rótulo numérico atribuído a cada dispositivo (computador, impressora, smartphone etc.) conectado a uma rede de computadores que utiliza o Protocolo de Internet para comunicação.[1] Um endereço IP serve a duas funções principais: identificação de interface de hospedeiro ou de rede e endereçamento de localização ex: 159.89.157.64.",
		"sobre"=>"Criador: ̶C̶o̶m̶e̶n̶t̶a̶d̶o̶r̶ | https://t.me/Comentered.\n\nLinguagem: PHP Wsociety@",
		"ferramentas"=>"\u{1F5C3}_Ferramentas_:\n\n*\u{1F449}Checar Bin:* `/bin 404528`\n*\u{1F449}Gerar Cartão de Credito:* `/ccgen`\n*\u{1F449}Gerar Bin:* `/bgen`\n*\u{1F449}Geolocalizar ip:* `/ip 159.89.157.64`\n\u{1F449}*Gerar(CEP, CPF,...):* `/doc`",
		"bandeiras"=>"*Escolha a bandeira da cc que deseja gerar:* \n`/mastercard\n/visa\n/amex\n/jcb\n/diners\n/maestro`",
		"sintaxes"=>"Formato incoreto. Insira o comando no seguinte formato:\n\n/bin xxxxxx\n\n em que:\n\n/bin é o comando\n\n xxxxxx são os 6 números da bin que deseja checar",
		"invalid"=>"*O ip deve conter pelomenos 6 números separados por ponto(.)*",
		"cep"=>"*Para Localizar CEP introduza o comando com a seguinte sintaxe: /cep 37435-971 ou /cep 37435971*",
		
		"bandeiras"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"\u{1F4B3} Visa", "callback_data"=>"Visa")), 
				array(array("text"=>"\u{1F4B3} Mastercard", "callback_data"=>"Mastercard")), 
				array(array("text"=>"\u{1F4B3} Amex", "callback_data"=>"Amex")), 
				array(array("text"=>"\u{1F4B3} Diners", "callback_data"=>"Diners")), 
				array(array("text"=>"\u{1F4B3} Jcb", "callback_data"=>"Jcb")),
				array(array("text"=>"\u{1F4B3} Maestro", "callback_data"=>"Maestro"))
			)
		),
		
	];

}
?>
