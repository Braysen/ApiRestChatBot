<?php

require_once('clases/auth.class.php');
require_once('clases/answers.php');
    $_auth = new auth;
    $_answers = new answers;
    //Si la peticion es POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $post_body = file_get_contents("php://input");//recibir los datos
        $datos = json_decode($post_body,true);
        if(isset($datos['pass'])){
            if(isset($datos['name'])){//Create user
                $data = $_auth->createUser($post_body);//Enviamos los datos al manejador
                header('Content-type: application/json');//Devolvemos una respuesta
                if(isset($data['result']['error_id'])){//Si, existe algun error en la petición
                    $responseCode = $data['result']['error_id'];//Obtenemos el codigo del error
                    http_response_code($responseCode);//Enviamos el codigo del error http
                }else{
                    http_response_code(200);//Enviamos el codigo http 200
                }
                echo json_encode($data);//Muestra el mensaje en formato JSON
            }
            
            /*else if(isset($datos['photo'])){
                $data = $_auth->updateProfile($post_body);//Enviamos los datos al manejador
                header('Content-type: application/json');//Devolvemos una respuesta
                if(isset($data['result']['error_id'])){//Si, existe algun error en la petición
                    $responseCode = $data['result']['error_id'];//Obtenemos el codigo del error
                    http_response_code($responseCode);//Enviamos el codigo del error http
                }else{
                    http_response_code(200);//Enviamos el codigo http 200
                }
                echo json_encode($data);//Muestra el mensaje en formato JSON
            }*/
            else{//Login
                $data = $_auth->login($post_body);//Enviamos los datos al manejador
                header('Content-type: application/json');//Devolvemos una respuesta
                if(isset($data['result']['error_id'])){//Si, existe algun error en la petición
                    $responseCode = $data['result']['error_id'];//Obtenemos el codigo del error
                    http_response_code($responseCode);//Enviamos el codigo del error http
                }else{
                    http_response_code(200);//Enviamos el codigo http 200
                }
                echo json_encode($data);//Muestra el mensaje en formato JSON
            }
        }else if(isset($datos['id'])){
            $data = $_auth->updateProfile($post_body);//Enviamos los datos al manejador
                header('Content-type: application/json');//Devolvemos una respuesta
                if(isset($data['result']['error_id'])){//Si, existe algun error en la petición
                    $responseCode = $data['result']['error_id'];//Obtenemos el codigo del error
                    http_response_code($responseCode);//Enviamos el codigo del error http
                }else{
                    http_response_code(200);//Enviamos el codigo http 200
                }
                echo json_encode($data);//Muestra el mensaje en formato JSON
        }else{
            $data = $_auth->logout($post_body);//Enviamos los datos al manejador
            header('Content-type: application/json');//Devolvemos una respuesta
            if(isset($data['result']['error_id'])){//Si, existe algun error en la petición
                $responseCode = $data['result']['error_id'];//Obtenemos el codigo del error
                http_response_code($responseCode);//Enviamos el codigo del error http
            }else{
                http_response_code(200);//Enviamos el codigo http 200
            }
            echo json_encode($data);//Muestra el mensaje en formato JSON
        }
    }else{//Si, la peticion no es POST
        //Devolvemos una respuesta
        header('Content-type: application/json');
        //Mostramos el error 405, indicando que la peticion no esta permitida
        $data = $_answers->error_405();
        //Muestra el mensaje en formato JSON
        echo json_encode($data);
    }

?>