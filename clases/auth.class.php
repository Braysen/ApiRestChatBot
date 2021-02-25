<?php
    require_once('conexion/conexion.php');
    require_once('answers.php');

    class auth extends conexion{

        public function login($json){
      
            $_answers = new answers;
            $datos = json_decode($json,true);
            //Si el campo email y password no existen
            if(!isset($datos['email']) || !isset($datos["password"])){
                //error con los campos
                return $_answers->error_400();
            }else{
                //Los datos fueron ingresados
                $email = $datos['email'];
                $password = $datos['password'];
                //Si los datos estan vacios
                if($email == "" || $password == ""){
                    //error con los campos
                    return $_answers->error_400();
                }else{//Si los datos fueron ingresados
                    //$password = parent::encrypt($password);
                    //Obtenemos los datos del usuario desde el fron-end
                    $datos = $this->obtenerDatosUsuarios($email);
                    if($datos){
                        //Verificamos el password del usuario
                        if($password == $datos[0]['password_user']){
                            if($datos[0]['status'] == 'active'){
                                //Crear el token
                                $verified = $this->createToken($datos[0]['cod_user']);
                                //Verificamos que el token se haya creado correctamente
                                if($verified){
                                    //Se genero el token
                                    $result = $_answers->response;
                                    $result['result'] = array(
                                        "token" => $verified
                                    );
                                    //Mostramos el mensaje en formato JSON
                                    return $result;
                                }else{//error al generar el token
                                    //Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                    return $_answers->error_500("Error interno. no hemos podido guardar su solicitud");
                                }
                            }else{
                                //El usuario no se encuentra activo
                                return $_answers->error_200("El usuario no se encuentra activo");
                            }
                        }else{//El password es invalido
                            //Mostramos un mensaje de alerta, indicando que el password es invalido
                            return $_answers->error_200("El password es invalido");
                        }
                    }else{//no existe el usuario
                        //Mostramos un mensaje de alerta, indicando que el usuario no existe
                        return $_answers->error_200("El usuario $email  no existe ");
                    }
                }
            }
        }

        private function obtenerDatosUsuarios($email){
            //Crea la peticion para obtener los datos del usuario
            $query = "select cod_user, password_user, status from users where email_user= '$email'";
            //Realiza la peticion
            $data = parent::obtenerDatos($query);
            //Si, se realiza la peticion, enviamos la información
            if(isset($data['0']['cod_user'])){
                return $data;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }

        private function createToken($code_user){
            $val = true;
            //Genera el token de forma aleatoria
            $token = bin2hex(openssl_random_pseudo_bytes(50,$val));
            //Genera la fecha y la hora, en la que se genera el token
            $date = date("Y-m-d H:i");
            //Definimos el estado del token
            $status = "active";
            //Crea la peticion para actualizar el token
            $query = "UPDATE users SET token = '$token', fecha='$date' WHERE cod_user = '$code_user'";
            //Ejecuta la peticion
            $verified= parent::nomQuery($query);
            //Si, se realiza la peticion, enviamos el token
            if($verified){
                return $token;
            }else{//Si, no se realiza la peticion, enviamos un 0
                return 0;
            }
        }


    }

?>