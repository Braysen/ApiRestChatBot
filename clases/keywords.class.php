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
    private $peticion = "";
    private $type_user = "";
    //private $mensaje = "";
    private $cod_user = "";
    
    public function keywordsList(){//Verificado
        //Crea la peticion obetener los Keyword's por tipo
        /*$query = "SELECT cod_keyword, desc_keyword, type_keyword FROM keywords WHERE stat_keyword='active' and status_keyword='active'";*/
        $query = "SELECT * FROM Keywords";
        $data = parent::obtenerDatos($query);//Crea una variable, a traves de esta obtenemos la lista de Keywords
        return $data;//Mostramos la informacion 
    }

    public function keywordByType($json){
        $_answers = new answers;
        $datos = json_decode($json,true);//Obtenemos la información del front-end
        if(!isset($datos['type']) || !isset($datos['token']) || !isset($datos['cod_user'])){//Si el campo token no existe
            return $_answers->error_401();//Mostramos un mensaje, indicando que el usuario que esta realizando la peticion no tiene la autorizacion
        }else{
            if($datos['token'] == "" || $datos['type'] == "" || $datos['cod_user'] == ""){//Si, los campos estan vacios
                return $_answers->error_400(); //Mostramos un mensaje de alerta, indicando que los campos son requeridos
            }else{
                $this->token = $datos['token']; //Obtenemos el token del usuario
                $this->cod_user = $datos['cod_user']; //Obtenemos el codigo del usuario
                $arrayToken = $this->searchToken();//Verificamos el token
                if($arrayToken){//Si, existe el token y el usuario
                    $this->type = $datos['type']; //Obtenemos el  tipo de keyword que proporciona el usuario
                    $this->peticion = "TypeKeyword";//Definimos el tipo de peticion que se esta realizando
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

    private function getKeywordByType(){
        //Crea la peticion obetener los Keyword's por tipo
        $query = "select * from keywords where type_keyword = '$this->type' and stat_keyword='active' and status_keyword='active'";
        return parent::obtenerDatos($query);//Ejecuta la peticion
    }

    public function post($json){//Validado
        $_answers = new answers;
        $datos = json_decode($json,true);//Obtenemos la información del front-end
        if(!isset($datos['token']) || !isset($datos['keyword']) || !isset($datos['type']) || !isset($datos['cod_user'])){//Si el campo token no existe
            return $_answers->error_401();//Mostramos un mensaje, indicando que el usuario que esta realizando la peticion no tiene la autorizacion
        }else{//Si los campos existen
            if($datos['token'] == "" || $datos['keyword'] == "" || $datos['type'] == "" || $datos['cod_user'] == ""){//Si, los campos estan vacios
                return $_answers->error_400(); //Mostramos un mensaje de alerta, indicando que los campos son requeridos
            }else{
                $this->token = $datos['token']; //Obtenemos el token del usuario
                $this->keyword = $datos['keyword']; //Obtenemos el token del usuario
                $this->type = $datos['type']; //Obtenemos el token del usuario
                $this->cod_user = $datos['cod_user']; //Obtenemos el token del usuario
                $this->stat_keyword = "active";
                $this->status_keyword = "active";
                $arrayToken = $this->searchToken();//Verificamos el token
                if($arrayToken){//Si, existe el token y el usuario
                    $this->peticion = "Create";//Definimos el tipo de peticion que se esta realizando
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

    private function datavalidation($pet){
        $_answers = new answers;
        $resp_type_user = $this->searchUserRole(); //Verificamos que el usuario tenga los permisos para realizar la peticion
        if($resp_type_user[0]["type"] === "Administrator"){//Si, el usuario es de tipo Administrator, podra realizar la peticion
            if($pet === "Delete"){
                $resp = $this->deleteKeyword();
                if($resp){
                    $respuesta = $_answers->response;
                    $respuesta['result'] = array(
                        "cod_keyword" => $resp,
                        "msg" => "Keyword successfully removed !!"
                    );
                    return $respuesta;
                }else{
                    return $_answers->error_500();
                }
            }else if($pet === "Create"){
                $resp = $this->createKeyword();
                if($resp){
                    $respuesta = $_answers->response;
                    $respuesta['result'] = array(
                        "cod_keyword" => $resp,
                        "msg" => "Keyword created correctly !!"
                    );
                    return $respuesta;
                }else{
                    return $_answers->error_500();
                }
            }else if($pet === "Update"){
                $resp = $this->updateKeyword();
                if($resp){
                    $respuesta = $_answers->response;
                    $respuesta['result'] = array(
                        "cod_keyword" => $resp,
                        "msg" => "Keyword updated correctly !!"
                    );
                    return $respuesta;
                }else{
                    return $_answers->error_500();
                }
            }else if($pet === "TypeKeyword"){
                $resp = $this->getKeywordByType();
                if($resp){
                    $respuesta = $_answers->response;
                    $respuesta['result'] = array(
                        $resp
                    );
                    return $respuesta;
                }else{
                    return $_answers->error_500();
                }
            }
        }else{//Si, el usuario que realizo la peticion no es de tipo Administrator
            return $_answers->error_401();
        }
    }

    private function createKeyword(){//Validado
        //Crea la peticion para crear un Keyword
        $query = "insert into keywords (desc_keyword, type_keyword, stat_keyword, status_keyword) values('$this->keyword', '$this->type', '$this->stat_keyword', '$this->status_keyword')";
        $resp = parent::nomQueryId($query);//Ejecuta la peticion 
        if($resp){//Si se realiza la peticion, enviamos la informacion
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    public function put($json){//Validado
        $_answers = new answers;
        $datos = json_decode($json,true);
        if(!isset($datos['token']) || !isset($datos['cod_keyword']) || !isset($datos['keyword']) || !isset($datos['type']) || !isset($datos['stat_keyword']) || !isset($datos['cod_user'])){//Si los campos no existen
            return $_answers->error_401();
        }else{//Si el campo token existe
            //Obtenemos la informacion, que va a proporcionar el usuario
            $this->cod_keyword = $datos['cod_keyword'];
            $this->keyword = $datos['keyword'];
            $this->type = $datos['type'];
            $this->stat_keyword = $datos['stat_keyword'];
            $this->cod_user = $datos['cod_user'];
            $this->token = $datos['token'];
            if($this->cod_keyword == "" || $this->keyword == "" || $this->type == "" || $this->stat_keyword == "" || $this->cod_user  == "" || $this->token  == ""){
                return $_answers->error_400();//Mostramos un mensaje de alerta, indicando que los campos son requeridos
            }else{
                $this->token = $datos['token'];
                $this->cod_user = $datos['cod_user'];
                $arrayToken = $this->searchToken();//Verificamos el token
                if($arrayToken){//Si existe el token
                    $this->peticion = "Update";//Definimos el tipo de peticion que se va a realizar
                    $resp = $this->datavalidation($this->peticion);
                    if($resp){//Si, la peticion se realizo correctamente
                        return $resp;
                    }else{//Si, la peticion no se realizo correctamente
                        return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                    }
                }else{//Si el token es invalido 
                    return $_answers->error_401("The token I send is invalid or has expired !");//Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                }
            }
        }
    }

    private function updateKeyword(){//Validado
        //Crea la peticion para actualizar el keyword
        $query = "UPDATE keywords SET desc_keyword = '$this->keyword', type_keyword = '$this->type', stat_keyword = '$this->stat_keyword' WHERE cod_keyword = '$this->cod_keyword'";
        $resp = parent::nomQuery($query);//Ejecuta la peticion
        if($resp >= 1){//Si se realiza la peticion, enviamos la informacion
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

    public function delete($json){//Validado
        $_answers = new answers;
        $datos = json_decode($json,true);
        if(!isset($datos['token']) || !isset($datos['cod_keyword']) || !isset($datos['cod_user'])){//Si los campos no existen
            return $_answers->error_401();
        }else{//Si los campos existen
            if($datos['token'] == "" || $datos['cod_keyword'] == "" || $datos['cod_user'] == ""){//Si, los campos estan vacios
                return $_answers->error_400();//Mostramos un mensaje de alerta, indicando que los campos son requeridos
            }else{//Si hay datos en los campos
                $this->token = $datos['token'];//Obtenemos el token del usuario
                $this->cod_user = $datos['cod_user'];//Obtenemos el token del usuario
                $this->cod_keyword = $datos['cod_keyword'];//Obtenemos el token del usuario
                $arrayToken = $this->searchToken();//Verificamos el token
                if($arrayToken){//Si existe el token
                    $this->peticion = "Delete";//Definimos el tipo de peticion que realiza el usuario
                    $resp = $this->datavalidation($this->peticion); 
                    if($resp){//Si, la peticion se realizo correctamente
                        return $resp;
                    }else{//Si, la peticion no se realizo correctamente
                        return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                    }
                }else{
                    return $_answers->error_401("The token I send is invalid or has expired !");//Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                }
            }
        }
    }

    private function deleteKeyword(){//Validado
        //Crea la peticion para actualizar el estato del Keyword
        $query = "UPDATE keywords SET status_keyword='eliminado' WHERE cod_keyword = '$this->cod_keyword'";
        $resp = parent::nomQuery($query);//Se ejecuta la peticion
        if($resp >= 1){//Si se realiza la peticion, enviamos la informacion
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }

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
        $query = "SELECT type from users WHERE cod_user='$this->cod_user'";
        $resp = parent::obtenerDatos($query);//Ejecuta la peticion
        if($resp){//Si se realiza la peticion, enviamos la informacion
            return $resp;
        }else{//Si, no se realiza la peticion enviamos un 0
            return 0;
        }
    }
}
?>