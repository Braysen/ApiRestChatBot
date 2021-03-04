<?php
    require_once('conexion/conexion.php');
    require_once('answers.php');

    class auth extends conexion{
        //Crea variables globales
        private $cod_user = "";
        private $token = "";

        public function login($json){
            $_answers = new answers;
            $datos = json_decode($json,true);
            if(!isset($datos["type_email"]) || !isset($datos["email"]) || !isset($datos["password"])){//Si el campo tipo de email existe
                return $_answers->error_401();//error con los campos
            }else{
                if($datos["type_email"] == ""){//El usuario, inicio sesion con una cuenta normal
                    if($datos["email"] == "" || $datos["password"] == ""){//si, los campos email y password estan vacios
                        return $_answers->error_400();//error con los campos
                    }else{//si, los campos email y password estan con data
                        if(!filter_var($datos["email"], FILTER_VALIDATE_EMAIL)){//Si el email, es invalido
                            return $_answers->error_200("The email is invalid !!!");//El usuario no se encuentra activo
                        }else{//Si el email, es valido
                            //Verificamos que el email este en la base de datos
                            $result_email = $this->verifyEmail($datos["email"]);
                            if($result_email){//Si, la peticion se realizo correctamente
                                if($result_email == 1){//Si el email se encuentra en la base de datos
                                    $resp = $this->searchAccount($datos["email"], $datos["type_email"], $datos["password"]);
                                    if($resp){//Si, la peticion se realizo de forma correcta
                                        return $resp;//Mostramos el mensaje en formato JSON
                                    }else{//si, la peticion fue incorrecta
                                        return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                    }
                                }else{//Si el email no, se encuentra en la base de datos
                                    return $_answers->error_200("The email is not registered !");//El usuario no se encuentra activo
                                }
                            }
                        }
                    }
                }else{//El usuario, inicio sesion con una cuenta afiliada a una red social
                    if($datos["email"] == ""){//El email, esta vacio
                        return $_answers->error_400();//error con los campos
                    }else{//El email esta con dato
                        if(!filter_var($datos["email"], FILTER_VALIDATE_EMAIL)){//Si el email, es invalido
                            return $_answers->error_200("The email is invalid !!!");//El usuario no se encuentra activo
                        }else{//Si el email, es valido
                            //Verificamos que el email este en la base de datos
                            $result_email = $this->verifyEmail($datos["email"]);
                            if($result_email == 1 ){//Si el email se encuentra en la base de datos
                                $resp = $this->searchAccount($datos["email"], $datos["type_email"], $datos["password"]);
                                if($resp){//Si, la peticion se realizo de forma correcta
                                    return $resp;
                                }else{//si, la peticion fue incorrecta
                                    return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                }
                            }else{//Si el email no, se encuentra en la base de datos
                                return $_answers->error_200("The email is not registered !");//El usuario no se encuentra activo
                            }
                        }
                    }
                }
            }
        }

        private function verifyEmail($email_user){
            //Crea la peticion para buscar el token
            $query = "SELECT mail FROM Users where mail='$email_user'";
            $resp = parent::nomQuery($query);//Ejecutamos la peticion
            if($resp>=1){//Si se realiza la peticion, enviamos la informacion
                return $resp;
            }else{//Si, no se realiza la peticion enviamos un 0
                return "La cuenta no existe !!";
            }
        }

        private function obtenerDatosUsuarios($email, $password){
            //Crea la peticion para obtener los datos del usuario
            $query = "SELECT id, password, mail, status FROM Users WHERE mail= '$email'";   
            $data = parent::obtenerDatos($query);//Realiza la peticion
            if(isset($data['0']['cod_user'])){//Si, se realiza la peticion, enviamos la información
                return $data;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }

        private function obtenerData($token){
            //Crea la peticion para obtener los datos del usuario
            $query = "SELECT id,name, password ,lname, mail, photo, phone, bName, bPhone, bMail, userStatus, token FROM Users WHERE token= '$token'";  
            $data = parent::obtenerDatos($query);//Realiza la peticion
            if(isset($data['0']['id'])){//Si, se realiza la peticion, enviamos la información
                return $data;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }

        private function searchAccount($email, $type_account, $password){
            $_answers = new answers;
            if($type_account == ""){//Usuario con cuenta normal
                $resp_normal = $this->obtenerDatosUsuarios($email, $password);
                if($resp_normal){
                    if($password == $resp_normal[0]['password_user']){//el password es valido
                        if($resp_normal[0]['status'] == 'active'){//si, la cuenta esta activa
                            $verified = $this->createToken($resp_normal[0]['email_user']);//Crear el token
                            if($verified){//si, el token se creo correctamente
                                $resp = $this->obtenerData($verified);
                                if($resp){
                                    $result = $_answers->response;//Se genero el token
                                    $result['result'] = $resp;
                                    return $result;//Mostramos el mensaje en formato JSON
                                }else{
                                    return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                }
                            }else{//Si, no se creo el token
                                return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                            }
                        }else{//Si, la cuenta no esta activa
                            return $_answers->error_200("El usuario no se encuentra activo");//El usuario no se encuentra activo
                        }
                    }else{//El password es invalido
                        return $_answers->error_200("The password is invalid !");//El usuario no se encuentra activo
                    }
                }else{//Si la peticion se realizo de forma incorrecta
                    return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                }
            }else{//Usuario con cuenta social
                $resp_social = $this->obtenerDatosUsuarios($email, $password);
                if($resp_social){
                    if($resp_social[0]['status'] == 'active'){//si, la cuenta esta activa
                        $verified = $this->createToken($resp_social[0]['email_user']);//Crear el token
                        if($verified){//si, el token se creo correctamente
                            $resp = $this->obtenerData($verified);
                            if($resp){
                                $result = $_answers->response;//Se genero el token
                                $result['result'] = $resp;
                                return $result;//Mostramos el mensaje en formato JSON
                            }else{
                                return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                            }
                        }else{
                            return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                        }
                    }else{
                        return $_answers->error_200("El usuario no se encuentra activo");//El usuario no se encuentra activo
                    }
                }else{//Si, la peticion se hizo de forma incorrecta
                    return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                }
            }
        }

        private function createToken($email_user){
            /* Fecha de expiración del token */
            $date = date("Y-m-d");//Genera la fecha y la hora, en la que se genera el token
            $fecha = date_create($date);
            date_add($fecha, date_interval_create_from_date_string("1 month"));
            $fecha_expiracion_token = date_format($fecha,"Y-m-d");
            /*------------------------------*/
            $val = true;
            $token = bin2hex(openssl_random_pseudo_bytes(50,$val));//Genera el token de forma aleatoria
            $status = "active";//Definimos el estado del token
            $query = "UPDATE Users SET token = '$token', tokenCreationDate='$date', tokenStatus='active', tokenExpirationDate = '$fecha_expiracion_token' WHERE mail = '$email_user'";//Crea la peticion para actualizar el token
            $verified= parent::nomQuery($query);//Ejecuta la peticion
            if($verified){//Si, se realiza la peticion, enviamos el token
                return $token;
            }else{//Si, no se realiza la peticion, enviamos un 0
                return 0;
            }
        }

        public function logout($json){
            $_answers = new answers;
            $datos = json_decode($json,true);
            if(!isset($datos['token']) || !isset($datos['cod_user'])){//Si el campo token no existe
                return $_answers->error_401();//Mostramos un mensaje, indicando que el usuario que esta realizando la peticion no tiene la autorizacion
            }else{//Si el campo token existe
                $this->token = $datos['token']; //Obtenemos el token del usuario
                $this->cod_user = $datos['cod_user'];
                $arrayToken = $this->searchToken();//Verificamos el token
                if($arrayToken){//Si existe el token
                    $arrayLogout = $this->updateToken($this->cod_user);
                    if($arrayLogout){//Si, la peticion se realizo de forma correcta
                        $result = $_answers->response;//Se genero el token
                        $result['result'] = array(
                            "Token" => ''
                        );
                        return $result;//Mostramos el mensaje en formato JSON
                    }else{
                        return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                    }
                }else{//Si, no existe el token
                    return $_answers->error_401("The token I send is invalid or has expired !"); //Mostramos un mensaje de alerta, indicando que el token no existe o es invalido
                }
            }
        }

        private function searchToken(){
            //Crea la peticion para buscar el token
            $query = "SELECT id, name, mail from Users WHERE token = '$this->token' and tokenStatus='active' and id='$this->cod_user'";
            //print_r($query);
            $resp = parent::obtenerDatos($query);//Ejecutamos la peticion
            if($resp){//Si se realiza la peticion, enviamos la informacion
                return $resp;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }

        private function updateToken($cod_user){
            $date = date("Y-m-d");
            $query = "UPDATE Users SET tokenCreationDate='$date', token='', tokenStatus='inactive' WHERE id='$cod_user'";
            $resp = parent::nomQuery($query);
            if($resp >=1){
                return $resp;
            }else{
                return 0;
            }
        }
    }

?>