<?php

require_once 'clases/answers.php';
require_once 'clases/keywords.class.php';

    $_answers = new answers;
    $_keywords = new keywords;
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //Si en la ruta hay una variable que se llame type_keyword, realiza la siguiente accion
        if(isset($_GET['type_keyword'])){
            $type_keyword = $_GET['type_keyword'];
            $data = $_keywords->getKeywordByType($type_keyword);
            //Devolvemos una respuesta
            header('Content-type: application/json');
            echo json_encode($data);
            http_response_code(200);
        }else{
            $keywordsList = $_keywords->keywordsList();
            //Devolvemos una respuesta
            header('Content-type: application/json');
            echo json_encode($keywordsList);
            http_response_code(200);
        }
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //Obtenemos los datos del frontend
        $post_body = file_get_contents("php://input");
        //Enviamos los datos al manejador
        $data = $_keywords->post($post_body);
        //Devolvemos una respuesta
        header('Content-type: application/json');
        if(isset($data['result']['error_id'])){
            $responseCode = $data['result']['error_id'];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        //Mostramos la informacion en formato JSON
        echo json_encode($data);
    }else if($_SERVER['REQUEST_METHOD'] == 'PUT'){
        //Obtenemos los datos del front-end
        $post_body = file_get_contents("php://input");
        //Enviamos datos al manejador
        $data = $_keywords->put($post_body);
        //Devolvemos una respuesta
        header('Content-type: application/json');
        if(isset($data['result']['error_id'])){
            $responseCode = $data['result']['error_id'];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($data);
    }else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        //Obtenemos los datos del front-end
        $post_body = file_get_contents("php://input");
        //Enviamos datos al manejador
        $data = $_keywords->delete($post_body);
        //Devolvemos una respuesta
        header('Content-type: application/json');
        if(isset($data['result']['error_id'])){
            $responseCode = $data['result']['error_id'];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($data);
    }else{
        //Devolvemos una respuesta
        header('Content-type: application/json');
        //Mostramos el error 405, indicando que la peticion no esta permitida
        $data = $_answers->error_405();
        //Muestra el mensaje en formato JSON
        echo json_encode($data);
    }
?>