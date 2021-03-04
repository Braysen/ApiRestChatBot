<?php

require_once 'clases/answers.php';
require_once 'clases/messages.class.php';

    $_answers = new answers;
    $_messages = new messages;
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $post_body = file_get_contents("php://input");//recibir los datos
        $datos = json_decode($post_body,true);
        if(isset($datos["token"])){
            $keywordsList = $_messages->postMessage($post_body);
            //Devolvemos una respuesta
            header('Content-type: application/json');
            echo json_encode($keywordsList);
            http_response_code(200);
        }else{
            $sendMessage = $_messages->sendBotResponse($post_body);
            header('Content-type: application/json');
            echo json_encode($sendMessage);
            http_response_code(200);
        }
    }

?>