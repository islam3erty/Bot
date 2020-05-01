<?php
  
    require "BotC.php";

    define("BOT_TOKEN", "876737706:AAEaTouyw83yoHNP7s0gfmcRvx2b-vI9YbA");
    define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
    define("WEBHOOK_URL", "https://consultador.herokuapp.com/bot.php");

    $conteudo = file_get_contents("php://input");
    $update = json_decode($conteudo, true);
    $mensagem = $update["message"];
    $opc = [];
    $opc["chat_id"] = $mensagem["chat"]["id"];
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
       
    }
    elseif($opc['texto'] === '/porra'){

        $motor->sendMessage($opc, $motor->getInfo('query'.$opc['chat_id']), $strings->falas["primeira"]);
    
    }elseif($opc['texto'] != '/start' && $sugestion != ''){

        $motor->sugestion($opc);

    }else{

        if($sugestion == ''){

            $motor->sendMessage($opc, 'Unknow Command, if the intention was to leave a suggestion please open it as tools and go to suggestions. If you are in suggestions, you can leave a new question at a time, if you want to leave another suggestion and go back and enter suggestions again for another suggestion or opinion.', $strings->falas['primeira']);
        }
        
    }
    


