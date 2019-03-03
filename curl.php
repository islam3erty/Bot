<?php
class Engine {

	private $BOT_TOKEN = "765733425:AAGoczJFfcw23Uv-tLI7yWhTeh77oxKCKSE";
	private $API_URL = "https://api.telegram.org/bot".BOT_TOKEN."/";
	private $str = null;

	public function __construct(){
		$this->str = new Strings();
	}
	//public function getNews(){
		//$json = file_get_contents("https://newsapi.org/      v2/top-headlines?sources=google-news-br&apiKey=9f8c49a46a4d457082730c4b8d9e2a9a");

	//}



	/*public function getTemperatura(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://api.weatherunlocked.com/api/current/-25.9653,32.5892?app_id=66fa65c0&app_key=409b1bb29c9bd5643b0744fd5fc15b03");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$json = json_decode($output);
		return $json->wx_desc."<br/>"."Temperatura em Celcius: ".$json->temp_c."°C"."<br/>"."Temperatura em Faranheith: ".$json->temp_f."°F"."<br/>"."Humidade: ".$json->humid_pct."<br/>"."Velocidade do vento(Km/h): ".$json->windspd_kmh."km/h"."<br/>"."Velocidade do vento(m/h): ".$json->windspd_mph."m/h"."<br/>"."Velocidade do vento(m/s): ".$json->windspd_ms."m/s"."<br/>"."Percentagem de Nuvens: ".$json->cloudtotal_pct."%"."<br/>"."Visibilidade: ".$json->vis_km."<br/>"."Pressão do ar: ".$json->slp_mb;
	}*/

	public function getDetails(){
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/?token=03a2079b5357d1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$json = json_decode($output);
		return urlencode("ip: ".$json->ip."\ncidade: ".$json->city."\nRegião: ".$json->region."\nPais: ".$json->country."\nLatitude e Longitude: ".$json->loc."\nCodigo postal: ".@$json->postal."\nServidor da: ".$json->org);
	}

	public function enviarMensagem($metodo, $parametros){
		$options = [
			"HTTP"=>[
				"method"=> "POST",
				"content"=> json_encode($parametros),
				"header"=>"content-type: aplication/json\r\n".
						  "accept: aplication/json\r\n"
			]
		];

		$context = stream_context_create( $options );
		file_get_contents($this->API_URL.$metodo,false,$context);
		
	}

	public function remoteIp($ip){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/".$ip."?token=03a2079b5357d1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		$json = json_decode($output);
		return "ip: ".$json->ip."<br/>"."cidade: ".$json->city."<br/>"."Região: ".$json->region."<br/>"."Pais: ".$json->country."<br/>"."Latitude e Longitude: ".$json->loc."<br/>"."Codigo postal: ".@$json->postal."<br/>"."Servidor da: ".$json->org;
	}
}


class Strings
{

	public $falas = [
		"start"=>"Bem vindo ao Ipf1nd! Fui programado para localizar e dar informações sobre IP* /instruções*"."<br/>"."* /sobre *",
		"instruções"=>"Como vê a informação sobre o seu ip foi mostrada quando iniciou uma conversa comigo!. Agora para saber sobre um ip que não seja o seu basta me enviar o Ip","erroVazio"=>"Nenhum ip informado",
		"erroHTTP"=>"Ip não disponivel para localização",
		"problemas"=>"Criador: C̶o̶m̶e̶n̶t̶a̶d̶o̶r̶  (https://t.me/Comentered)",
		"processando"=>"Processando...",
		"sobre"=>"Endereço IP” significa endereço de protocolo de Internet, e cada dispositivo que está conectado a uma rede (como a Internet) possui um.

		Um endereço IP é similar a seu número de telefone. Seu número de telefone é um conjunto único de números que identifica seu telefone, de forma que outras pessoas possam ligar para você. Da mesma forma, um endereço IP é um conjunto único de números que identifica seu computador, de forma que ele possa enviar e receber dados com outros computadores.

		Atualmente, a maioria dos endereços IP consistem em quatro conjuntos de números, cada um separado por um ponto. 192.168.1.42 é um exemplo de endereço IP."

	];
}








?>
