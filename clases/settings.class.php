<?php
    require_once('conexion/conexion.php');
    require_once('answers.php');

    class auth extends conexion{
        private $idUsuario = "";
        private $latePayments = "";
        private $newMaintenance = "";
        private $expiringLeases = "";
        private $newMessage = "";
        private $reminders = "";

        public function createSetting ($json){
            $_answers = new answers;
            $datos = json_decode($json,true);
            if(!isset($datos["id"]) || !isset($datos["token"])){//Si, los campos no existen
                return $_answers->error_401();//error con los campos
            }else{//Si, los campos existen
                if($datos["id"] == "" || $datos["token"] == ""){
                    if($datos["id"] == ""){//Si, el campo id esta vacio
                        return $_answers->error_211("The id field is required !");
                    }else if($datos["token"] == ""){//Si, el campo token esta vacio
                        return $_answers->error_213("The token field is required !");
                    }else{//Si los campos estan vacios
                        return $_answers->error_401();//error con los campos
                    }
                }else{//Si, los campos tienen data
                    print_r("Bienvenido");
                }
            }
        }

        private function create (){
            //Crea la peticion para crear una cuenta
            $query = "INSERT INTO Settings (idUsuario, latePayments, newMaintenance, expiringLeases, newMessage, reminders) values ('$this->name', '$this->lname', '$this->mail', '$pass_encrypt', 'active', '$token_generate', 'active', '$date', '$fecha_expiracion_token')";
            $resp = parent::nomQueryId($query);//Ejecuta la peticion 
            if($resp){//Si se realiza la peticion, enviamos la informacion
                return $resp;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }
        
    }

?>