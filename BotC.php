<?php

class Divulga{

	public $str;
	public $id;
	public $db;
	public $fich;

	public function __construct(){
		set_time_limit(0);
		$this->str = new Strings();
		require "simple_html_dom.php";
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
		

		$this->apiRequest("sendMessage", $parametro);
		$this->saveID($opc);
	}

	public function editMessage($opc, $msg, $button=array()){
		$encode = json_encode($button);
		$val = $this->getInfo("id".$opc['chat_id']);
		$val = intval($val);
		$val = $val+1;
		$parametro = array(
			"chat_id"=>$opc["chat_id"],
			"text"=>$msg,
			"message_id"=>$val,
			"reply_markup"=>$encode,
			"parse_mode"=>"Markdown",
		);

		$this->apiRequest("editMessageText", $parametro);

	}

	protected function answerCallbackQuery($callback_id, $alert, $time, $text){
		$parametro = array(
			"callback_query_id"=>$callback_id,
			"show_alert"=>$alert,
			"cache_time"=>$time,
			"text"=>$text,
		);

		$this->apiRequest("answerCallbackQuery", $parametro);
	}

	public function callback($callback){
		
		$cb_chat_id = $callback["message"]["chat"]["id"];
		$cb_message_id = $callback["message"]["id"];
		$cb_id = $callback["id"];
		$cb_data = $callback["data"];
		$cb_from_id = $callback['from']['id'];
		$cb_from_name = $callback['from']['first_name'];
		$cb_from_user = $callback['from']['username'];

		$opc = [
			
			"chat_id"=>$cb_chat_id,
			"user_id"=>$cb_from_id,
			'username'=>$cb_from_user,
			'name'=>$cb_from_name,
		];

		$info = "*ID: ".$opc['user_id']."\nNome: ".$opc['name']."\nUser: @".$opc['username']."*\n\n  https://t.me/".$opc['username'];

		switch($cb_data){
			
			case "proxys":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas["tipo_proxy"], $this->str->falas["proxy"]);
				$this->saveEdited("proxy", 'query'.$opc['chat_id']);
				break;
			case 'parser':
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $info, $this->str->falas['primeira']);
				$this->saveEdited('tools', 'query'.$opc['chat_id']);
				break;
			case "5":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->proxy($opc, 5, $this->getInfo('proxy_type'.$opc['chat_id'])), $this->str->falas["testar"]);
				break;
			case "1":
				$this->showNews($opc, 1);
				break;
			case "2":
				$this->showNews($opc, 2);
				break;
			case "3":
				$this->showNews($opc, 3);
				break;
			case "4":
				$this->showNews($opc, 4);
				break;
			case "quinto":
				$this->showNews($opc, 5);
				break;
			case "6":
				$this->showNews($opc, 6);
				break;
			case "7":
				$this->showNews($opc, 7);
				break;
			case "8":
				$this->showNews($opc, 8);
				break;
			case "9":
				$this->showNews($opc, 9);
				break;
			case "10":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->proxy($opc, 10, $this->getInfo('proxy_type'.$opc['chat_id'])), $this->str->falas["testar"]);
				break;
			case "15":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->proxy($opc, 15, $this->getInfo('proxy_type'.$opc['chat_id'])), $this->str->falas["testar"]);
				break;
			case "tools":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, "*Escolha uma ferramenta*", $this->str->falas["Ferramentas"]);
				$this->saveEdited("tools", 'query'.$opc['chat_id']);
				break;
			case "main":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas["welcome"], $this->str->falas["menu"]);
				break;
			case "test":
				$text = "Aguarde...";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->testProxy($opc, $this->getInfo('proxy_type'.$opc['chat_id']));
				break;
			case "sock4":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['quantidade'], $this->str->falas["quant"]);
				$this->saveEdited("socks4", 'proxy_type'.$opc['chat_id']);
				break;	
			case "sock5":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['quantidade'], $this->str->falas["quant"]);
				$this->saveEdited("socks5", 'proxy_type'.$opc['chat_id']);
				break;
			case "http":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['quantidade'], $this->str->falas["quant"]);
				$this->saveEdited("http", 'proxy_type'.$opc['chat_id']);
				break;
			case "news":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['Jornal'], $this->str->falas['Noticiario']);
				$this->saveEdited('jornal', 'query'.$opc['chat_id']);
				break;
			case "pais":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['categorias'], $this->str->falas['Pais']);
				$this->saveEdited('categoria', 'query'.$opc['chat_id']);
				break;
			case "sociedade":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc, 'sociedade');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "politica":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc, 'politica');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "internacional":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc, 'internacional');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "economia":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc, 'economia');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "desporto":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc ,'desporto');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "cultura":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc, 'cultura');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "opiniao":
				$text = "Aguarde a notícia esta sendo processada";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->scraper($opc,'opiniao');
				$this->sendChatAction($opc, 'typing');
				sleep(1);
				$this->showNews($opc);
				break;
			case "espera":
				$text = "Aguarde";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				break;
			case "me":
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['Contacto'], $this->str->falas['primeira']);
				$this->saveEdited('contacto', 'query'.$opc['chat_id']);
				break;
			case 'about':
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['sobre'], $this->str->falas['primeira']);
				$this->saveEdited('sobre', 'query'.$opc['chat_id']);
				break;
			case 'group':
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['groupal'], $this->str->falas['grupos']);
				$this->saveEdited('group', 'query'.$opc["chat_id"]);
				break;
			case 'last':
				$text=null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['categorias'], $this->str->falas['tec']);
				$this->saveEdited('categoria', 'query'.$opc['chat_id']);
				break;
			case 'novidades':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'novidades');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('novidades', 'categoria'.$opc['chat_id']);
				break;
			case 'software':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'software');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('software', 'categoria'.$opc['chat_id']);
				break;
			case 'seguranca':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'seguranca');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('seguranca', 'categoria'.$opc['chat_id']);
				break;
			case 'internet':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'internet');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('internet', 'categoria'.$opc['chat_id']);
				break;
			case 'produto':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'produto');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('produto', 'categoria'.$opc['chat_id']);
				break;
			case 'celular':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'celular');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('celular', 'categoria'.$opc['chat_id']);
				break;
			case 'tutorial':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'tutorial');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('tutorial', 'categoria'.$opc['chat_id']);
				break;
			case 'log':
				$text = 'Essa funcão ainda está em desenvolvimento...';
				$this->answerCallbackQuery($cb_id, false, 4, $text);
				break;
			case 'review':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'review');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('review', 'categoria'.$opc['chat_id']);
				break;
			case 'popular':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'mais-lidas');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('mais-lidas', 'categoria'.$opc['chat_id']);
				break;
			case 'dispositivos':
				$text='Aguarde';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->tecNews($opc, 'dispositivos-moveis');
				$this->saveEdited('tec', 'query'.$opc['chat_id']);
				$this->saveEdited('1', 'page'.$opc['chat_id']);
				$this->saveEdited('dispositivos-moveis', 'categoria'.$opc['chat_id']);
				break;
			case '...':
				$text = 'Noticiando';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$pagina = $this->getInfo('page'.$opc['chat_id']);
				$pagina = intval($pagina) + 1;
				$categoria = $this->getInfo('categoria'.$opc['chat_id']);
				$this->tecNews($opc, $categoria, $pagina);
				$this->saveEdited('categoria', 'query'.$opc['chat_id']);
				$this->saveEdited(strval($pagina), 'page'.$opc['chat_id']);
				break;
			case 'really':
				$text = "Jornal Verdade se encontra indisponivel do momento...";
				$this->answerCallbackQuery($cb_id, false, 5, $text);
				break;
			case 'frases':
				$text = 'Pensando em uma frase';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->pensador($opc);
				$this->saveEdited('frases', 'query'.$opc['chat_id']);
				break;
			case 'another':
				$text = 'Pensado em outra frase';
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->pensador($opc);
				break;
			case 'wsociety':
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['wSociety'], $this->str->falas['primeira']);
				$this->saveEdited('crew', 'query'.$opc['chat_id']);
				break;
			case 'project':
				$text = null;
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				$this->editMessage($opc, $this->str->falas['sugestion'], $this->str->falas['sugestione']);
				$this->saveEdited('frases', 'query'.$opc['chat_id']);
				$this->saveEdited('sugest', 'sugestion'.$opc['chat_id']);
				break;
			case 'hehe':
				$text = "Apenas escreva sua sugestão";
				$this->answerCallbackQuery($cb_id, false, 3, $text);
				break;
			case 'back':
				$text = null;
				$estado = $this->getInfo('query'.$opc['chat_id']);

				//niveis
				switch($estado){

					case 'contacto':
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['welcome'], $this->str->falas['menu']);
						$this->saveEdited('menu', 'query'.$opc['chat_id']);
						break;
					case 'sobre':
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['welcome'], $this->str->falas['menu']);
						$this->saveEdited('menu', 'query'.$opc['chat_id']);
						break;
					case 'group':
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['welcome'], $this->str->falas['menu']);
						$this->saveEdited('menu', 'query'.$opc['chat_id']);
						break;
					case 'crew':
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, "*Escolha uma ferramenta*", $this->str->falas["Ferramentas"]);
						$this->saveEdited('tools', 'query'.$opc['chat_id']);
						break;
					case 'tec':
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['categorias'], $this->str->falas['tec']);
						$this->saveEdited('jornal', 'query'.$opc['chat_id']);
						break;
					case "tools":
						$text= null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas["welcome"], $this->str->falas["menu"]);
						$this->saveEdited('menu', 'query'.$opc['chat_id']);
						break;
					case "quantidade":
						$text= null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas["tipo_proxy"], $this->str->falas["proxy"]);
						$this->saveEdited('proxy', 'query'.$opc['chat_id']);
						break;
					case "testar":
						$text= null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas["quantidade"], $this->str->falas["quant"]);
						$this->saveEdited('quantidade', 'query'.$opc['chat_id']);
						break;
					case "proxy":
						$text= null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, "*Escolha uma ferramenta*", $this->str->falas["Ferramentas"]);
						$this->saveEdited('tools', 'query'.$opc['chat_id']);
						break;
					case "pais":
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['Jornal'], $this->str->falas['Noticiario']);
						$this->saveEdited('jornal', 'query'.$opc['chat_id']);
						break;
					case "quantidade":
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['tipo_proxy'], $this->str->falas['proxy']);
						$this->saveEdited("proxy", 'query'.$opc['chat_id']);
						break;
					case "jornal":
						$text =  null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas["welcome"], $this->str->falas["menu"]);
						$this->saveEdited("menu", 'query'.$opc['chat_id']);
						break;
					case "categoria":
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, $this->str->falas['Jornal'], $this->str->falas['Noticiario']);
						$this->saveEdited('jornal', 'query'.$opc['chat_id']);
						break;
					case 'frases':
						$text = null;
						$this->answerCallbackQuery($cb_id, false, 3, $text);
						$this->editMessage($opc, "*Escolha uma ferramenta*", $this->str->falas["Ferramentas"]);
						$this->saveEdited('tools', 'query'.$opc['chat_id']);
						break;
				}
				break;
		}

		return true;
	}

	public function saveID($opc){

		$abertura = fopen(md5("id".$opc['chat_id']).'.txt', "w+");
		$cont = $opc["message_id"];
		$write = fwrite($abertura, $cont);
		fclose($abertura);
	}



	public function sendChatAction($opc, $action){ 

		$parametro = [

			"chat_id"=>$opc["chat_id"],
			"action"=>$action
		];

		$this->apiRequest("sendChatAction", $parametro);
	}

	public function proxy($opc ,$quantidade, $tipo){

		$proxys = file_get_contents("https://api.proxyscrape.com/?request=displayproxies&proxytype=".$tipo."&timeout=10000&country=all&anonymity=all&ssl=all");
		$separate = explode("\n", $proxys);
		$texto = "";
		for($i=0; $i<$quantidade; $i++){
			
			$random_keys = array_rand($separate);
			$texto = $texto.$separate[$random_keys]."\r\n";
		}
		$this->saveEdited($texto, 'proxys'.$opc['chat_id']);
		return $texto;
	}

	public function testProxy($opc, $type='http'){

		$live ="\u{2705}";
		$dead ="\u{274C}";
		$proxys = $this->getInfo('proxys'.$opc['chat_id']);
		$array = explode("\r\n", $proxys);
		$estado;
		array_pop($array);
		$total = count($array);
		$contador = $total - 2;

		for($i=0; $i<$total; $i++){

			if($i > $contador){
				$estado = 'mais';
			}else{
				$estado = 'espere';
			}
			$prox = $this->getInfo("proxys".$opc["chat_id"]);
			$array = explode("\r\n", $prox);
			
			$url = "https://www.google.com";
			$start_time = microtime(true);
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			if($type == 'socks4'){
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
			}else if($type == 'socks5'){
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
			}
			curl_setopt($ch, CURLOPT_PROXY, trim($array[$i]));
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, trim($array[$i]));
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$return = curl_exec($ch);
			curl_close($ch);
			$speed = floor((microtime(true) - $start_time)*1000);
			$string = stripos($return, '</html>');

			if($string > 0){
				
				$replace = str_replace($array[$i], $array[$i].$live." ".$speed."ms", $prox);
				$this->saveEdited($replace, 'proxys'.$opc['chat_id']);
				$this->editMessage($opc, $replace, $this->str->falas[$estado]);

			}else{
				
				$replace = str_replace($array[$i], $array[$i].$dead, $prox);
				$this->saveEdited($replace, 'proxys'.$opc['chat_id']);
				$this->editMessage($opc, $replace, $this->str->falas[$estado]);

			}

		}

		return true;

	}

	public function getInfo($file, $prefixo = '.txt'){
		return file_get_contents(md5($file).$prefixo);
	}

	public function saveEdited($content, $file){

		$fl = md5($file).".txt";
		$fp = fopen($fl, 'w+');
		@fwrite($fp, $content);
		@fclose($fp);
	}

	public function scraper($opc ,$categoria){

		$html = file_get_html("http://opais.sapo.mz/".$categoria);
		if($categoria != "opiniao"){

			$postagem = $html->find('li[class=blog-list-item]');
			$contador = count($postagem);
			$contador = $contador + 1;
			for($i=0; $i<$contador; $i++){

				$titulo = "*".$html->find('li[class=blog-list-item] h4', $i)->plaintext."*";
				$texto = $html->find('li[class=blog-list-item] p', $i)->plaintext;
				$link = $html->find('li[class=blog-list-item] h4 a', $i);
				$link = $link->href;
				$post = $titulo."\n\n".$texto."\n\n".$link;
				$this->saveEdited($post, $opc['chat_id'].$i);
			}

		}else{

			$postagem = $html->find('div[class=entry-box]');
			$count = count($postagem);

			for($i=0; $i<$contador; $i++){

				$titulo = "*".$html->find('div[class=entry-box] h4', $i)->plaintext."*";
				$texto = $html->find('div[class=entry-box] p', $i)->plaintext;
				$link = $html->find('div[class=entry-box] h4 a', $i);
				$link = $link->href;
				$post = $titulo."\n\n".$texto."\n\n".$link;
				$this->saveEdited($post, $opc['chat_id'].$i);
			}
		}

	}

	public function showNews($opc, $pagina = 1){

		$news = $this->getInfo($opc['chat_id'].$pagina);
		$this->editMessage($opc, $news, $this->str->falas['paginas']);
		return true;
	}

	public function forwardMessage($opc, $from_chat_id, $message_id, $disable_notification = false){

		$param = [
			"chat_id"=> $opc['chat_id'],
			"from_chat_id"=>$from_chat_id,
			"disable_notification"=>$disable_notification,
			"message_id"=>$message_id,
		];

		$this->apiRequest('forwardMessage', $param);
	}

	public function tecNews($opc , $categoria, $pagina = null){

		if($pagina != null){

			$url =  'https://www.tecmundo.com.br/'.$categoria.'?page='.$pagina;
		}else{
			$url = 'https://www.tecmundo.com.br/'.$categoria;
		}
		$html = file_get_html($url);
		$posts = $html->find('div[class=tec--list__item]');
		$limit = count($posts);
		$array = array();

		for($i=0; $i<20; $i++){

			$array['title'] = $html->find('h3[class=tec--card__title]', $i)->plaintext;
			$link = $html->find('h3[class=tec--card__title] a', $i);
			$array['link'] = $link->href;
			$array['data'] = $html->find('div[class=tec--timestamp__item z--font-semibold]', $i)->plaintext;
			$array['hora'] = $html->find('div[class=z--truncate z-flex-1]', $i)->plaintext;
			
			$message = $array['title']."\n".$array['data']."  ".$array['hora']."\n\n".$array['link'];
			$this->sendChatAction($opc, 'typing');
			if($i == 19){
				$this->sendNews($opc, $message, $this->str->falas['more']);
			}else{
				$this->sendNews($opc, $message);
			}

		}

		$message_id = $this->getInfo('id'.$opc['chat_id']);
		$message_id = intval($message_id) + 20;
		$this->saveEdited($message_id, 'id'.$opc['chat_id']);
		return true;
	}

	protected function sendNews($opc, $msg, $button = null){

		$encode = json_encode($button, true);

		if($button != null){
			
			$parametro =[

				'chat_id'=>$opc['chat_id'],
				'parse_mode'=>'Markdown',
				'text'=>$msg,
				'reply_markup'=>$encode,
				'disable_notification'=> true,

			]; 
		}else{
 			
 			$parametro = [
 	
	 	  		'chat_id'=>$opc['chat_id'],
	 	  		'parse_mode'=>'Markdown',
	 	  		'text'=>$msg

			];
		}
		
		$this->apiRequest('sendMessage', $parametro);
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

	public function sugestion($opc){

		$caminho = 'sugest/';
		$nome = md5($opc['chat_id'].time()).'.txt';
		$fp = fopen($caminho.$nome, 'w+');
		@fwrite($fp, $opc['texto']);
		@fclose($fp);

		$this->saveEdited('', 'sugestion'.$opc['chat_id']);
		$this->sendChatAction($opc, 'typing');
	    $this->sendMessage($opc, '*Thank You.*', $this->str->falas['primeira']);

		return true;
	}

}


class Strings{

	public $falas = array(
		"primeira"=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
			)
		),

		"menu"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"\u{1F4F0}News", "callback_data"=>"news")),
				array(array("text"=>"\u{00AE}Groups and Channels", "callback_data"=>"group"), array("text"=>"\u{1F910} YOU", "callback_data"=>"parser")),
				array(array("text"=>"Projects", "callback_data"=>"log"), array("text"=>"\u{1F527}tools", "callback_data"=>"tools")),
				array(array("text"=>"\u{260E}Contact my Creator", "callback_data"=>"me")),
				array(array("text"=>"\u{2753}About", "callback_data"=>"about")),
			),
		),

		"sugestion"=>"*Leave a tip, criticism, thanks or suggestion. In case you cannot speak directly to my creator. \n To leave your opinion just write as a normal message, I am programmed to direct your opinion to the server and save it until my creator sees it (This takes a maximum of 48 hours ).*",
		//tools
		"pensador"=>array(
			'inline_keyboard'=>array(
				array(array('text'=>"Another Sentence", 'callback_data'=>'another')),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),
		"Ferramentas"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"\u{1F9EC} Proxy", "callback_data"=>"proxys")),
				array(array("text"=>"Sugestion", "callback_data"=>"project"), array("text"=>"wSociety", "callback_data"=>"wsociety")),
				array(array("text"=>"\u{1F4A1} Pensador", "callback_data"=>"frases")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),

			)
		),
		//quantidade
		"quant"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"5", "callback_data"=>"5") ,array("text"=>"10", "callback_data"=>"10")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),

		'sugestione'=>array(

			'inline_keyboard'=>array(
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array('text'=>"\u{270D}\u{2B07}\u{2B07}\u{2B07}Send Sugestion", 'callback_data'=>'hehe')),
			)
		),

		"groupal"=>"*Current Group List.\n To add your channel or group to the list contact my creator*",
		"grupos"=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"Hakspace", "url"=>"https://t.me/hakspace")),
				array(array("text"=>"Hat Security", "url"=>"https://t.me/hatsecurity")),
				array(array("text"=>"MEGA IMPÉRIO", "url"=>"https://t.me/MegaImperio")),
				array(array("text"=>"MozDevs", "url"=>"https://t.me/MozDevz")),
				array(array("text"=>"Livros em PDF", "url"=>"https://t.me/LivrosEmPdf")),
				array(array("text"=>"apks", "url"=>"https://t.me/Apks_uteis")),
				array(array("text"=>"\u{2B05} Voltar", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),

			)
		),

		"cadastro"=>array(
			"keyboard"=>array(
				array("Cadastrar"),
			),
			"resize_keyboard"=>true,
			"one_time_keyboard"=>true,
		),
		"iniciar"=>array(
			"keyboard"=>array(
				array("Start"),
			),
			"resize_keyboard"=>true,
			"one_time_keyboard"=>true,
		),

		"welcome"=>"*Bem vindo.\n Desfrute das ferramentas que eu forneço para ti.*",
		"quantidade"=>"*Choose the number of proxies you want*",
		//testar
		"testar"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"\u{1F9EC} More Proxys", "callback_data"=>"proxys")),
				array(array("text"=>"\u{1F489} Test", "callback_data"=>"test")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),
		//proxy
		"proxy"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"\u{1F535} HTTP", "callback_data"=>"http")),
				array(array("text"=>"\u{1F537} SOCKS4", "callback_data"=>"sock4")),
				array(array("text"=>"\u{1F53A} SOCKS5", "callback_data"=>"sock5")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),
		//testado
		'mais'=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"\u{1F9EC} More Proxys...", "callback_data"=>"proxys")),
				array(array("text"=>"\u{1F527} Tools", "callback_data"=>"tools")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),

		"tipo_proxy"=>"*Escolha o tipo*",
		"espere"=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"Wait...", "callback_data"=>"espera"))
			)
		),
		//Tipo_Jornal
		"Noticiario"=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"\u{1F525}Last Hour", "callback_data"=>'last')),
				array(array("text"=>"O País", "callback_data"=>'pais'), array("text"=>"Verdade", "callback_data"=>"really")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),
		//categoria
		"categorias"=>"*Choose the category of the news you want to see*",
		"Jornal"=>"*Choose the Newspaper you want read*",
		"more" => array(
			'inline_keyboard'=>array(
				array(array("text"=>'More News', "callback_data"=>'...')),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),
		//pais
		"Pais"=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"Sociedade", "callback_data"=>"sociedade"), array("text"=>"política", "callback_data"=>"politica")),
				array(array("text"=>"Economia", "callback_data"=>"economia"), array("text"=>"Internacional", "callback_data"=>"internacional")),
				array(array("text"=>"Desporto", "callback_data"=>"desporto"), array("text"=>"Cultura", "callback_data"=>"cultura")),
				array(array("text"=>"Opinião", "callback_data"=>"opiniao")),
				array(array("text"=>"\u{2B05} Voltar", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),

		'tec'=>array(
			'inline_keyboard'=>array(
				array(array("text"=>'New', "callback_data"=>"novidades"), array("text"=>"Software", "callback_data"=>'software'), array("text"=>'Segurança', "callback_data"=>'seguranca')),
				array(array("text"=>'Internet', 'callback_data'=>"internet"), array("text"=>'Produto', "callback_data"=>"Produto")),
				array(array("text"=>"Celular", "callback_data"=>'celular'), array("text"=>"Tutorial", "callback_data"=>'tutorial'), array("text"=>'Review', "callback_data"=>'review')),
				array(array('text'=>'Devices', 'callback_data'=>'dispositivos')),
				array(array("text"=>"Most Read", "callback_data"=>'popular')),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>"main")),
			)
		),

		"paginas"=>array(
			'inline_keyboard'=>array(
				array(array("text"=>"\u{0031}", "callback_data"=>"1"), array("text"=>"\u{0032}", "callback_data"=>"2"), array("text"=>"\u{0033}", "callback_data"=>"3"), array("text"=>"\u{0034}", "callback_data"=>"4"), array("text"=>"\u{0035}", "callback_data"=>"quinto")),
				array(array("text"=>"\u{0036}", "callback_data"=>"6"), array("text"=>"\u{0037}", "callback_data"=>"7"), array("text"=>"\u{0038}", "callback_data"=>"8"), array("text"=>"\u{0039}", "callback_data"=>"9")),
				array(array("text"=>"\u{2B05} Back", "callback_data"=>"back")),
				array(array("text"=>"\u{1F3E1} Menu", "callback_data"=>'main')),
			)
		),

		"wSociety"=>"*A wSociety é um grupo privado de programadores, actualmente constituido por 4 programadores: Underline(Firebox), Tomás Queface, Comentador(Bastardo) e Yale.\n É um Grupo focado essencialmente na programação em linguagens de alto nível.\n Nenhum dos membros passou por uma formação especializada em programação, sendo todos autodidatas.*",
		"Contacto"=>"*Any questions, reports, suggestions and coffee and code issues please contact @Comentered \n\n https://t.me/Comentered*",
		"sobre"=>"Creator: ̶C̶o̶m̶e̶n̶t̶a̶d̶o̶r̶ | https://t.me/Comentered.\n\n Language: PHP \nVersão: 1.0\n\n Greetings: Wsociety || Moz Developers 🇲🇿",


	);
}

