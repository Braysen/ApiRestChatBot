<?php

require_once('clases/auth.class.php');
require_once('clases/answers.php');
    $_auth = new auth;
    $_answers = new answers;
    //Si la peticion es POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //recibir los datos
        $post_body = file_get_contents("php://input");
        //Enviamos los datos al manejador
        $data = $_auth->login($post_body);
        //Devolvemos una respuesta
        header('Content-type: application/json');
        //Si, existe algun error en la petición
        if(isset($data['result']['error_id'])){
            //Obtenemos el codigo del error
            $responseCode = $data['result']['error_id'];
            //Enviamos el codigo del error http
            http_response_code($responseCode);
        }else{
            //Enviamos el codigo http 200
            http_response_code(200);
        }
        //Muestra el mensaje en formato JSON
        echo json_encode($data);
    }else{//Si, la peticion no es POST
        //Devolvemos una respuesta
        header('Content-type: application/json');
        //Mostramos el error 405, indicando que la peticion no esta permitida
        $data = $_answers->error_405();
        //Muestra el mensaje en formato JSON
        echo json_encode($data);
    }

?>