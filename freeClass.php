<?php


class Bot{

	private $ch;
	function __construct(){
		set_time_limit(0);
		$this->ch = curl_init();
	}

	public function api_Request($metodo, $parametro){

		curl_setopt($this->ch, CURLOPT_URL, API_URL.$metodo."?");
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
			"Content_Type" => "multipart/form-data"
		]);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($parametro));
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		$resultado = curl_exec($this->ch);
		$info = curl_getinfo($this->ch);
		curl_close($this->ch);
	}

	public function editMessage($opc, $msg, $button=null){

		$message = intval($opc["message_id"]);
		if($button == null){
			$parametro = [
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"message_id"=>$message,
				"text"=>$msg,
			];
		}else{
			$button = json_encode($button, true);
			$parametro = [
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"message_id"=>$message,
				"text"=>$msg,
				"reply_markup"=>$button,
			];
		}

		$this->api_Request("editMessageText", $parametro);
	}

	public function sendMessage($opc, $msg, $button=null){


		if($button == null){
			$parametro = [
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"text"=>$msg,
			];
		}else{
			$button = json_encode($button, true);
			$parametro = [
				"chat_id"=>$opc["chat_id"],
				"parse_mode"=>"Markdown",
				"text"=>$msg,
				"reply_markup"=>$button,
			];
		}

		$this->api_Request("sendMessage", $parametro);
	}

	public function callbackQuery($callback){
		
		$cb_chat_id = $callback["message"]["chat"]["id"];
		$cb_message_id = $callback["message"]["id"];
		$cb_id = $callback["id"];
		$cb_data = $callback["data"];

		$opc = [
			"chat_id"=>$cb_chat_id,
		];
	}

	public function answerCallbackQuery($callback_id, $alert, $time, $text){
		$parametro = [
			"callback_query_id"=>$callback_id,
			"show_alert"=>$alert,
			"cache_time"=>$time,
			"text"=>$text
		];

		$this->api_Request("answerCallbackQuery", $parametro);
	}

	public function editReply_Markup($opc, $button){


		$parametro = [
			"chat_id"=>$opc["chat_id"],
			"message_id"=>$opc["message_id"],
			"reply_markup"=>json_encode($button, true),
		];

		$this->api_Request("editMessageReplyMarkup", $parametro);
	}

	public function deleteMessage($opc){
		/*$parametro = [
			"chat_id"=>$opc["chat_id"],
			"message_id"=>$opc["message_id"],
		];
		$this->api_Request("deleteMessage", $parametro);*/

		$url = trim("https://api.telegram.org/bot".BOT_TOKEN."/deleteMessage?chat_id=".$opc["chat_id"]."&message_id=".$opc["message_id"]);

		$go = file_get_contents($url);
	}

	public function sendFile($opc, $file, $type, $button){

		$button = json_encode($button, true);
		$parametro = [
			"chat_id"=>$opc["chat_id"],
			$type=>$file,
			"caption"=>$opc["caption"],
			"parse_mode"=>"Markdown",
			"reply_markup"=>$button,
		];

		$metodo = false;
		switch ($type) {
			case "video":
				$metodo = "sendVideo";
				break;
			case "audio":
				$metodo = "sendAudio";
				break;
			case "document":
				$metodo = "sendDocument";
				break;
			case "animation":
				$metodo = "sendAnimation";
				break;
			case 'Voice':
				$metodo = "sendVoice";
				break;
			case "photo":
				$metodo = "sendPhoto";
				break;	
		}

		$this->api_Request($metodo, $parametro);
	}

	public function editStrings($texto, $url){

		$str = new Strings();
		$mt = false;

		$sql = $str->pdo->prepare("UPDATE bot SET nome=:callum, texto=:dd, url=:link WHERE id=1");
		$sql->bindValue(":callum", "Callum");
		$sql->bindValue(":dd", $texto);
		$sql->bindValue(":link", $url);
		$sql->execute();
	}

}



class Strings{

	public $falas;
	public $pdo;
	public $text;
	public $url;

	function __construct(){

		try{
			$this->pdo = new PDO("mysql:dbname=sendtomyemail;host=db4free.net", "comentador", "humdados123456");

			$sql = $this->pdo->prepare("SELECT * FROM bot WHERE id=:aid");
			$sql->bindValue(":aid", 1);
			$sql->execute();

			$arr = array();

			if($sql->rowCount() > 0){
				$arr = $sql->fetch();
				$this->text = $arr["text"];
				$this->url = $arr["url"];
			}

		}catch(exception $e){
			echo $e->getMessage();
		}

		$array = [
			"contact" => array(array("text"=>$this->text, "url"=>$this->url)),
			
			"inline" => array(
				"inline_keyboard"=>array(
					array(array("text"=>"Like", "callback_data"=>"like")),
				)
			),

			"back"=>array(
				"inline_keyboard"=>array(
					array(array("text"=>"viadagem", "callback_data"=>"How")),
				)
			),

			"sintaxe" => "*Sintax Error. Please follow the rules of the below sintax: \n ```/change Contact https://t.me/ContactBetuosoBot*",
		];

		$this->falas = $array;
	}

}



?>