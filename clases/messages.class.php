<?php
    require_once 'conexion/conexion.php';
    require_once 'answers.php';

class messages extends conexion{
    //Crea variables globales
    private $type_keyword = "";
    private $cod_user = "";
    private $desc_message = "";
    private $cod_addressee = "";
    private $stat_message = "";
    private $cod_keyword = "";
    private $token = "";
    //Permite al usuario enviar un mensaje
    public function postMessage($json){
        $_answers = new answers;
        $datos = json_decode($json,true);//Obtenemos la información del front-end
        if(!isset($datos['token']) || !isset($datos['desc_message']) || !isset($datos['cod_user']) || !isset($datos['cod_keyword']) || !isset($datos['cod_addressee'])){//Si, los campos no existen
            return $_answers->error_401();//Mostramos un mensaje, indicando que el usuario que esta realizando la peticion no tiene la autorizacion
        }else{//Si los campos existen
            if($datos['token'] == "" || $datos['desc_message'] == "" || $datos['cod_user'] == "" || $datos['cod_keyword'] == "" || $datos['cod_addressee'] == ""){//Si, los campos estan vacios
                return $_answers->error_400(); //Mostramos un mensaje de alerta, indicando que los campos son requeridos
            }else{
                $this->token = $datos['token']; //Obtenemos el token del usuario
                $this->desc_message = $datos['desc_message']; //Obtenemos el token del usuario
                $this->cod_user = $datos['cod_user']; //Obtenemos el token del usuario
                $this->cod_keyword = $datos['cod_keyword']; //Obtenemos el token del usuario
                $this->cod_addressee = $datos['cod_addressee']; //Obtenemos el token del usuario
                $arrayToken = $this->searchToken();//Verificamos el token
                if($arrayToken){//Si, existe el token y el usuario
                    $this->peticion = "SendMessage";//Definimos el tipo de peticion que se esta realizando
                    $resp = $this->datavalidation($this->peticion);
                    if($resp){//Si, la peticion se realizo de forma correcta
                        return $resp;//Mostramos la respuesta
                    }else{//Si la peticion se realizo de forma incorrecta
                        return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                    }
                }else{//Si no, existe el token con el usuario
                    return $_answers->error_401("The token I send is invalid or has expired !"); //Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                }
            }
        }
    }
    //Permite al boot enviar un mensaje
    public function sendBotResponse($json){
        $_answers = new answers;
        $datos = json_decode($json,true);//Obtenemos la información del front-end
        if(!isset($datos['cod_user']) || !isset($datos['cod_keyword'])){//Si los campos no existen
            return $_answers->error_401();//Mostramos un mensaje, indicando que el usuario que esta realizando la peticion no tiene la autorizacion
        }else{//Si los campos existen
            if($datos['cod_user'] == "" || $datos['cod_keyword'] == ""){//Si, los campos estan vacios
                return $_answers->error_400();//Mostramos un mensaje de alerta, indicando que los campos son requeridos
            }else{
                $this->cod_user = $datos['cod_user']; //Obtenemos el token del usuario
                $this->cod_keyword = $datos['cod_keyword']; //Obtenemos el token del usuario
                $this->peticion = "BotSendMessage";//Definimos el tipo de peticion que se esta realizando
                $resp = $this->datavalidation($this->peticion); 
                
                if($resp){//Si, la peticion se realizo de forma correcta
                    return $resp;//Mostramos la respuesta
                }else{//Si la peticion se realizo de forma incorrecta
                    return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                }
            }
        }
    }
    //C!@WwPK&Y3O3xKqx8NYgifZv PublicaComunicaciones

    private function botResponse(){//Quien hizo la peticion y el mensaje
        //Crea la peticion para buscar el token
        $query = "SELECT desc_keyword FROM keywords WHERE cod_keyword = '$this->cod_keyword'";
        $resp = parent::obtenerDatos($query);//Ejecuta la peticion
        if($resp){//Si se realiza la peticion, enviamos la informacion
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    //Peticion que sirve para que el usuario envie un mensaje
    private function sendMessage(){
        //Crea la peticion obetener los Keyword's por tipo
        $hora = date("H:i");
        $fecha = date("Y-m-d");
        $query = "INSERT INTO messages (desc_message, stat_message, cod_sender, code_keyword, cod_addressee, hora, fecha) values ('$this->desc_message', 'sent', '$this->cod_user', '$this->cod_keyword', '$this->cod_addressee', '$hora', '$fecha')";
        return parent::nomQueryId($query);//Ejecuta la peticion*/
    }
    
    private function datavalidation($pet){
        $_answers = new answers;
        if($pet === "SendMessage"){
            $resp = $this->sendMessage();
            if($resp){
                $respuesta = $_answers->response;
                $respuesta['result'] = array(
                    "msg" => "Message sent successfully !!"
                );
                return $respuesta;
            }else{
                return $_answers->error_500();
            }
        }else if($pet === "BotSendMessage"){
            $resp = $this->botResponse();
            if($resp){
                $respuesta = $_answers->response;
                $respuesta['result'] = array(
                    "response" => $resp
                );
                return $respuesta;
            }else{
                return $_answers->error_500();
            }
        }
    }
    //Peticion que sirve para verificar que el token le pertenezca a un determinado usuario
    private function searchToken(){
        //Crea la peticion para buscar el token
        $query = "SELECT name_user, stat_token from users WHERE token = '$this->token' and stat_token='active' and cod_user='$this->cod_user'";
        $resp = parent::obtenerDatos($query);//Ejecutamos la peticion
        if($resp){//Si se realiza la peticion, enviamos la informacion
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }


    /*
        dato inconcluso, dato post
    
    */

}

?>