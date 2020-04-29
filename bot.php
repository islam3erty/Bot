<?php
  
    require "BotC.php";

    define("BOT_TOKEN", "876737706:AAEaTouyw83yoHNP7s0gfmcRvx2b-vI9YbA");
    define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
    define("WEBHOOK_URL", "https://consultador.herokuapp.com/bot.php");

    $conteudo = file_get_contents("php://input");
    $update = json_decode($conteudo, true);
    $mensagem = $update["message"];
    $opc;
    if(isset($mensagem["chat"]["type"]) && !empty($mensagem["chat"]["type"]){
      $opc["chat_id"] = $mensagem["chat"]["username"];
    }else{
     $opc["chat_id"]=$mensagem["chat"]["id"]; 
    }
    $opc["texto"]=$mensagem["text"];
    $opc["message_id"]=$mensagem["message_id"];
    $opc["user_id"]= $mensagem["from"]["id"];
    $opc["first_name"]=$mensagem["from"]["first_name"];
    $opc["last_name"] = $mensagem["from"]["last_name"];
    $opc["user"] = $mensagem["from"]["username"];
    $opc["user_lang"]=$mensagem["from"]["language_code"];
    $opc["chat_type"] = $mensagem["chat"]["type"];

    $motor = new Divulga();
    $strings = new Strings();
    
    @$sugestion = $motor->getInfo('sugestion'.$opc['chat_id']);
    if(isset($update["callback_query"])){
        $motor->callback($update["callback_query"]);
    }

    if($opc['texto'] === "/start"){

        $motor->sendMessage($opc, $strings->falas["welcome"], $strings->falas["menu"]);
        $motor->sendMessage($opc, $opc["chat_id"]);
    }
    elseif($opc['texto'] === '/porra'){

        $motor->sendMessage($opc, $strings->falas['categorias']);
    
    }elseif($opc['texto'] != '/start' && $sugestion != ''){

        $motor->sugestion($opc);

    }else{

        if($sugestion == ''){

            $motor->sendMessage($opc, 'Comando Desconhecido, se a intenção foi deixar uma sugestão por favor abra as ferramentas e vá em sugestões. Caso ja esteja em sugestões lembro-lhe que só é possível deixar uma segestão de cada vez, se quiser deixar outra sugestão terá que voltar e entrar novamente em sugestões para dar outra sugestão ou opinião.', $strings->falas['primeira']);
        }
        
    }
    


