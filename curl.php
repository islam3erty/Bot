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
		return "ip: ".$json->ip."\ncidade: ".$json->city."\nRegião: ".$json->region."\nPais: ".$json->country."\nLatitude e Longitude: ".$json->loc."\nCodigo postal: ".@$json->postal."\nServidor da: ".$json->org;
	}

	public function apiRequest($metodo, $param){

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, API_URL.$metodo."?");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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


	return "Bandeira: ".$json->scheme."\nTipo: ".$json->type."\nBrand: ".$json->brand."\nPaís: ".$json->country->name."(".$json->country->emoji.")"."\nLatitude: ".$json->country->latitude."\nLongitude: ".$json->country->longitude."\nBanco: ".$json->bank->name."\nWebsite: ".$json->bank->url."\nPhone: ".$json->bank->phone."\nCidade: ".$json->bank->city;
}

}


class Strings
{

	public $falas = [
		"start"=>"Eai mano você ta querendo checar sua bin?. ta no lugar certo. clique no comando /ferramentas para saber todas minhas funcionalidades.\nSe ainda n sabe o que é uma BIN clique no comando /acerca.\n Tem Duvidas? clique no comando /sobre.",
		"acerca"=>"bin são os primeiros seis números de um cartão do banco que identificam a bandeira do cartão, o tipo, o país, o número de telefone do banco entre outras informações.BIN quer dizer Bank Identification Number.",
		"sobre"=>"Criador: ̶C̶o̶m̶e̶n̶t̶a̶d̶o̶r̶ | https://t.me/Comentered.\nLinguagem: PHP Wsociety@",
		"ferramentas"=>"Ferramentas:\n/bin 404528\n\n/gen"

	];
}









?>
