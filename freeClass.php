<?php


class Bot {

	public $str;
	private $ch;
	function __construct(){
		$this->str = new Strings();
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
}


class Strings{

	public $falas = [
		"contact" => array(array("text"=>"Contact Me", "url"=>"t.me/Comentered")),
		"inline" => array(
			"inline_keyboard"=>array(
				array(array("text"=>"Like", "callback_data"=>"like")),
			)
		)
	];
}



?>
