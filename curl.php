<?php
class Engine {

	
	

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


		return urlencode("Bandeira: ".$json->scheme."<br>"."Tipo: ".$json->type."<br>"."Brand: ".$json->brand."<br>"."País: ".$json->country->name."(".$json->country->emoji.")"."<br>"."Latitude: ".$json->country->latitude."<br>"."Longitude: ".$json->country->longitude."<br>"."Banco: ".$json->bank->name."<br>"."Website: ".$json->bank->url."<br>"."Phone: ".$json->bank->phone."<br>"."Cidade: ".$json->bank->city);

	}
}


class Strings
{

	public $falas = [
		"start"=>"Eai man. Por enquanto minhas unicas funcões são gerar e checar Bins e INN. Para saber o que é uma bin clica na funcão /acerca. clique no comando a seguir para saber como o que faço. /ferramentas para duvidas e perguntas /sobre. ",
		"acerca"=>"bin são os primeiros seis números de um cartão do banco que identificam a bandeira do cartão, o tipo, o país, o número de telefone do banco entre outras informações.BIN quer dizer Bank Identification Number.",
		"erroHTTP"=>"",
		"processando"=>"Processando...",
		"sobre"=>"Criador: Comentador | https://t.me/Comentered. Linguagem: PHP Wsociety@",
		"ferramentas"=>*"Bin-checker:/bin 457173*\n\n\u
		Bin-Generator: /gen"
	];
}








?>
