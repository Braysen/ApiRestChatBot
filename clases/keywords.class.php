<?php

require_once 'conexion/conexion.php';
require_once 'answers.php';

class keywords extends conexion{
    //Crea variables globales
    private $stat_keyword = "";
    private $keyword = "";
    private $cod_keyword = "";
    private $type = "";
    private $status_keyword = "";
    private $token = "";
    private $email_user = "";
    
    public function keywordsList(){//Verificado
        //Crea la peticion obetener los Keyword's por tipo
        $query = "SELECT cod_keyword, desc_keyword, type_keyword FROM keywords WHERE stat_keyword='active' and status_keyword='active'";
        //Crea una variable, a traves de esta obtenemos la lista de Keywords
        $data = parent::obtenerDatos($query);
        //Mostramos la informacion
        return $data;
    }

    public function getKeywordByType($type){//Verificado
        //Crea la peticion obetener los Keyword's por tipo
        $query = "select * from keywords where type_keyword = '$type' and stat_keyword='active' and status_keyword='active'";
        //Ejecuta la peticion
        return parent::obtenerDatos($query);
    }

    public function post($json){//Verificado
        $_answers = new answers;
        $datos = json_decode($json,true);
        //Si el campo token no existe
        if(!isset($datos['token'])){
            return $_answers->error_401();
        }else{//Si el campo token existe
            //Obtenemos el token del usuario
            $this->token = $datos['token'];
            //Verificamos el token
            $arrayToken = $this->searchToken();
            //Si existe el token
            if($arrayToken){
                //Verificamos que los campos existan
                if(!isset($datos['keyword']) || !isset($datos['type']) || !isset($datos['email_user'])){
                    //Mostramos un mensaje de alerta, indicando que los campos son requeridos
                    return $_answers->error_400();
                }else{//Si, los campos existen
                    //Obtenemos la informacion, que va a proporcionar el usuario
                    $this->keyword = $datos['keyword'];
                    $this->type = $datos['type'];
                    $this->stat_keyword = "active";
                    $this->status_keyword = "active";
                    $this->email_user = $datos['email_user'];
                    //Si, la informacion que proporciono el usuario esta vacio
                    if($this->keyword == "" || $this->type == "" || $this->email_user == ""){
                        //Mostramos un mensaje de alerta, indicando que los campos son requeridos
                        return $_answers->error_400();
                    }else{//Si la informacion que proporciona el usuario, no esta vacio
                        //Verificamos que el usuario tenga los permisos para realizar la peticion
                        $resp_type_user = $this->searchUserRole();
                        //Si, el usuario es de tipo Administrator, podra realizar la peticion
                        if($resp_type_user[0]["type"] === "Administrator"){
                            //Crea el keyword
                            $resp = $this->createKeyword();
                            //Si la peticion se realizo de forma correcta 
                            if($resp){
                                $respuesta = $_answers->response;
                                $respuesta['result'] = array(
                                    "cod_keyword" => $resp
                                );
                                //Mostramos un mensaje de alerta, indicando que la solicitud fue realizada de forma correcta
                                return $respuesta;
                            }else{//Si, la solicitud, no fue realizada de forma correcta
                                //Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                return $respuesta->error_500();
                            }
                        }else{//Si, el usuario que realizo la peticion no es de tipo Administrator
                            //Mostramos un mensaje de alerta, indicandole que no tiene los permisos para realizar la peticion
                            return $_answers->error_401();
                        }
                    }
                }           
            }else{//Si, no existe el token
                //Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                return $_answers->error_401("The token I send is invalid or has expired !");
            }
        }
    }

    private function createKeyword(){//Verificado
        //Crea la peticion para crear un Keyword
        $query = "insert into keywords (desc_keyword, type_keyword, stat_keyword, status_keyword) values('$this->keyword', '$this->type', '$this->stat_keyword', '$this->status_keyword')";
        //Ejecuta la peticion
        $resp = parent::nomQueryId($query);
        //Si se realiza la peticion, enviamos la informacion
        if($resp){
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    public function put($json){//Verificado
        $_answers = new answers;
        $datos = json_decode($json,true);

        //Si el campo token no existe
        if(!isset($datos['token'])){
            return $_answers->error_401();
        }else{//Si el campo token existe
            //Obtenemos el token del usuario
            $this->token = $datos['token'];
            //Verificamos el token
            $arrayToken = $this->searchToken();
            //Si existe el token
            if($arrayToken){
                //Verificamos que los campos existan
                if(!isset($datos['cod_keyword']) || !isset($datos['keyword']) || !isset($datos['type']) || !isset($datos['stat_keyword']) || !isset($datos['email_user'])){
                    //Mostramos un mensaje de alerta, indicando que los campos son requeridos
                    return $_answers->error_400();
                }else{//Si, los campos existen
                    //Obtenemos la informacion, que va a proporcionar el usuario
                    $this->cod_keyword = $datos['cod_keyword'];
                    $this->keyword = $datos['keyword'];
                    $this->type = $datos['type'];
                    $this->stat_keyword = $datos['stat_keyword'];
                    $this->email_user = $datos['email_user'];
                    //Si, la informacion que proporciono el usuario esta vacio
                    if($this->cod_keyword == "" || $this->keyword == "" || $this->type == "" || $this->stat_keyword == "" || $this->email_user  == ""){
                        //Mostramos un mensaje de alerta, indicando que los campos son requeridos
                        return $_answers->error_400();
                    }else{//Si la informacion que proporciona el usuario, no esta vacio
                        //Verificamos que el usuario tenga los permisos para realizar la peticion
                        $resp_type_user = $this->searchUserRole();
                        //Si, el usuario es de tipo Administrator, podra realizar la peticion
                        if($resp_type_user[0]["type"] === "Administrator"){
                            //Actualizamos el Keyword
                            $resp = $this->updateKeyword();
                            //Si la peticion se realizo de forma correcta
                            if($resp){
                                $respuesta = $_answers->response;
                                $respuesta['result'] = array(
                                    "cod_keyword" => $resp
                                );
                                //Mostramos un mensaje de alerta, indicando que la solicitud fue realizada de forma correcta
                                return $respuesta;
                            }else{//Si, la solicitud, no fue realizada de forma correcta
                                //Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                return $_answers->error_500();
                            }
                        }else{//Si, el usuario que realizo la peticion no es de tipo Administrator
                            //Mostramos un mensaje de alerta, indicandole que 
                            return $_answers->error_401();
                        }
                    }
                }
            }else{//Si el token es invalido
                //Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                return $_answers->error_401("The token I send is invalid or has expired !");
            } 
        }
    }

    private function updateKeyword(){
        //Crea la peticion para actualizar el keyword
        $query = "UPDATE keywords SET desc_keyword = '$this->keyword', type_keyword = '$this->type', stat_keyword = '$this->stat_keyword' WHERE cod_keyword = '$this->cod_keyword'";
        //Ejecuta la peticion
        $resp = parent::nomQuery($query);
        //Si se realiza la peticion, enviamos la informacion
        if($resp >= 1){
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    public function delete($json){
        $_answers = new answers;
        $datos = json_decode($json,true);
        //Si el campo token no existe
        if(!isset($datos['token'])){
            return $_answers->error_401();
        }else{//Si el campo token existe
            //Obtenemos el token del usuario
            $this->token = $datos['token'];
            //Verificamos el token
            $arrayToken = $this->searchToken();
            //Si existe el token
            if($arrayToken){
                //Verificamos que los campos existan
                if(!isset($datos['cod_keyword']) || !isset($datos['email_user'])){
                    //Mostramos un mensaje de alerta, indicando que los campos son requeridos
                    return $_answers->error_400();
                }else{//Si, los campos existen
                    //Obtenemos la información del front-end
                    $this->cod_keyword = $datos['cod_keyword'];
                    $this->email_user = $datos['email_user'];
                    //Realizamos la siguiente accion si los campos estan vacios
                    if($this->cod_keyword == "" || $this->email_user == ""){
                        //Mostramos un mensaje de alerta, indicando que los campos son requeridos
                        return $_answers->error_400();
                    }else{//Si la informacion que proporciona el usuario, no esta vacio
                        //Verificamos que el usuario tenga los permisos para realizar la peticion
                        $resp_type_user = $this->searchUserRole();
                        //Si, el usuario es de tipo Administrator, podra realizar la peticion
                        if($resp_type_user[0]["type"] === "Administrator"){
                            //Eliminamos el Keyword
                            $resp = $this->deleteKeyword();
                            //Si la peticion se realizo de forma correcta
                            if($resp){
                                $respuesta = $_answers->response;
                                $respuesta['result'] = array(
                                    "cod_keyword" => $resp,
                                    "msg" => "The keyword $this->cod_keyword was successfully removed"
                                );
                                //Mostramos un mensaje de alerta, indicando que la solicitud fue realizada de forma correcta
                                return $respuesta;
                            }else{//Si, la solicitud, no fue realizada de forma correcta
                                //Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                return $_answers->error_500();
                            }
                        }else{//Si, el usuario que realizo la peticion no es de tipo Administrator
                            //Mostramos un mensaje de alerta, indicandole que no tiene los permisos para realizar la peticion
                            return $_answers->error_401();
                        }
                    }
                }
            }else{//Si el token es invalido
                //Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                return $_answers->error_401("The token I send is invalid or has expired !");
            }
            /* ----------- */
        }
        

    }

    private function deleteKeyword(){
        //Crea la peticion para actualizar el estato del Keyword
        $query = "UPDATE keywords SET status_keyword='eliminado' WHERE cod_keyword = '$this->cod_keyword'";
        //Se ejecuta la peticion
        $resp = parent::nomQuery($query);
        //Si se realiza la peticion, enviamos la informacion
        if($resp >= 1){
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    private function searchToken(){
        //Crea la peticion para buscar el token
        $query = "SELECT name_user, stat_token from users WHERE token = '$this->token' and stat_token='active'";
        //Ejecutamos la peticion
        $resp = parent::obtenerDatos($query);
        //Si se realiza la peticion, enviamos la informacion
        if($resp){
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    private function updateToken($cod_user){
        $date = date("Y-m-d H:i");
        $query = "UPDATE users SET fecha='$date' WHERE cod_user='$cod_user'";
        $resp = parent::nomQuery($query);
        if($resp >=1){
            return $resp;
        }else{
            return 0;
        }
    }

    private function searchUserRole(){
        //Crea la peticion para obtener el tipo de usuario que esta realizando la accion
        $query = "SELECT type from users WHERE email_user='$this->email_user'";
        //Ejecuta la peticion
        $resp = parent::obtenerDatos($query);
        //Si se realiza la peticion, enviamos la informacion
        if($resp){
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

}

?>