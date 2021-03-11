<?php
    require_once('conexion/conexion.php');
    require_once('answers.php');

    class auth extends conexion{
        //Crea variables globales
        private $cod_user = "";
        private $token = "";
        private $name = "";
        private $lname = "";
        private $mail = "";
        private $photo = "";
        private $password = "";
        private $phone = "";
        private $bName = "";
        private $bPhone = "";
        private $bMail = "";
        private $bAddress = "";
        private $bPhoto = "";
        private $type = "";

        public function login($json){
            $_answers = new answers;
            $datos = json_decode($json,true);
            if(!isset($datos["type"]) || !isset($datos["mail"]) || !isset($datos["pass"])){//Si, los campos no existen
                return $_answers->error_401();//error con los campos
            }else{//Si, los campos existen
                if($datos["type"] == ""){//El usuario, inicio sesion con una cuenta normal
                    if($datos["mail"] == "" || $datos["pass"] == ""){//si, los campos email y password estan vacios
                        return $_answers->error_400();//error con los campos
                    }else{//si, los campos email y password estan con data
                        if(!filter_var($datos["mail"], FILTER_VALIDATE_EMAIL)){//Si el email, es invalido
                            return $_answers->error_202("The email is invalid !!!");//Mostramos una alerta, indicandole al usuario que el email es invalido
                        }else{//Si el email, es valido
                            //Verificamos que el email este en la base de datos
                            $result_email = $this->verifyEmail($datos["mail"]);
                            if($result_email == 1){//Si, la peticion se realizo correctamente
                                 $resp_cuenta = $this->searchAccount($datos["mail"], $datos["type"], $datos["pass"]);           
                                if($resp_cuenta){//Si, la peticion se realizo de forma correcta
                                    if($resp_cuenta["error_id"] == 203){
                                        return $_answers->error_203("The password is invalid !");
                                    }else if($resp_cuenta["error_id"] == 202){
                                        return $_answers->error_202("The email is invalid !!!");
                                    }else if($resp_cuenta["error_id"] == 204){
                                        return $_answers->error_204("Account is not active !");
                                    }else if($resp_cuenta["error_id"] == 500){
                                        return $_answers->error_500("Internal error. we could not save your request !");
                                    }
                                    else{
                                        $data_login = $this->convertData($resp_cuenta);
                                        if($data_login){
                                            return $data_login;
                                        }
                                    }
                                }else{//si, la peticion fue incorrecta
                                    return $_answers->error_500("Internal error. we could not save your request !");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                }
                            }else{
                                return $_answers->error_201("The email is not registered!");//El usuario no se encuentra activo
                            }
                        }
                    }
                }else if($datos["type"] == "Social"){//El usuario, inicio sesion con una cuenta afiliada a una red social
                    if($datos["mail"] == ""){//El email, esta vacio
                        return $_answers->error_400();//error con los campos
                    }else{//El email esta con dato
                        if(!filter_var($datos["mail"], FILTER_VALIDATE_EMAIL)){//Si el email, es invalido
                            return $_answers->error_202("The email is invalid !!!");//El usuario no se encuentra activo
                        }else{//Si el email, es valido
                            //Verificamos que el email este en la base de datos
                            $result_email = $this->verifyEmail($datos["mail"]);
                            if($result_email == 1){//Si, la peticion se realizo correctamente
                                $resp_cuenta2 = $this->searchAccount($datos["mail"], $datos["type"], $datos["pass"]);
                                if($resp_cuenta2){//Si, la peticion se realizo de forma correcta
                                    if($resp_cuenta2["error_id"] == 203){
                                        return $_answers->error_203("The password is invalid !");
                                    }else if($resp_cuenta2["error_id"] == 202){
                                        return $_answers->error_202("The email is invalid !!!");
                                    }else if($resp_cuenta2["error_id"] == 204){
                                        return $_answers->error_204("Account is not active !");
                                    }else if($resp_cuenta2["error_id"] == 500){
                                        return $_answers->error_500("Internal error. we could not save your request !");
                                    }else{
                                        $data_login = $this->convertData($resp_cuenta2);
                                        if($data_login){
                                            return $data_login;
                                        }
                                    }
                                }else{//si, la peticion fue incorrecta
                                    return $_answers->error_500("Internal error. we could not save your request !");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                }
                            }else{
                                return $_answers->error_201("The email is not registered!");//El usuario no se encuentra activo
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
                //return "La cuenta no existe !!";
                return 0;
            }
        }

        private function obtenerDatosUsuarios($email, $password){
            //Crea la peticion para obtener los datos del usuario
            $query = "SELECT id, password, mail, userStatus FROM Users WHERE mail= '$email'";
            $data = parent::obtenerDatos($query);//Realiza la peticion
            if(isset($data['0']['id'])){//Si, se realiza la peticion, enviamos la información
                return $data;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }

        public function updateProfile($json){//name y phone
            $_answers = new answers;
            $datos = json_decode($json,true);
            if(!isset($datos["id"]) || !isset($datos["phone"]) || !isset($datos["token"]) || !isset($datos["name"])){//Si, los campos no existen
                return $_answers->error_401();//error con los campos
            }else{
                if($datos["id"] == "" || $datos["token"] == ""){//Si, los campos estan vacios
                    if($datos["id"] == ""){
                        return $_answers->error_211("The id field is required !");
                    }else if($datos["token"] == ""){
                        return $_answers->error_213("The token field is required !");
                    }else if($datos["token"] == "" && $datos["id"] == ""){
                        return $_answers->error_400();//Mostramos un mensaje de alerta, indicandole al usuario que los campos son requeridos*/
                    }
                }else{//Si los campos tienen data
                    $this->cod_user = $datos["id"];
                    $this->token = $datos["token"];
                    $resp_verified_token = $this->searchToken();
                    if($resp_verified_token == 0){//Si, el token es invalido o no le pertenece al usuario que inicio sesion
                        return $_answers->error_212("The token is invalid or expired !");
                    }else{
                        if($datos["phone"] != "" || $datos["name"] != ""){
                            //$phone_tamaño = strlen($datos["phone"]);
                            if($datos["phone"] != ""){//Si el campo phone esta con datos
                                if(is_numeric($datos["phone"])){//Si, el campo phone solo tiene numeros
                                    $phone_tamaño = strlen($datos["phone"]);
                                    if($phone_tamaño == 9){//Si el campo phone contiene nueve digitos
                                        $this->phone = $datos["phone"];
                                        $this->name = $datos["name"];
                                        $resp_update_profile = $this->update($this->cod_user);
                                        if($resp_update_profile == 1){//Si, el usuario hizo alguna modificacion es su perfil
                                            $resp_data_create = $this->obtenerDataCreate($datos["id"]);
                                            if($resp_data_create == 0){
                                                $data_convertida = $this->convertData($resp_data_create);
                                                if($data_convertida){
                                                    return $data_convertida;
                                                }
                                                //return $resp_data_create;
                                            }else{
                                                $data_convertida = $this->convertData($resp_data_create);
                                                if($data_convertida){
                                                    return $data_convertida;
                                                }
                                                //return $resp_data_create;
                                            }
                                        }else{//Si, el usuario no hizo modificaciones en su perfil
                                            //return $resp_update_profile;
                                            $resp_data_create = $this->obtenerDataCreate($datos["id"]);
                                            if($resp_data_create == 0){
                                                $data_convertida = $this->convertData($resp_data_create);
                                                if($data_convertida){
                                                    return $data_convertida;
                                                }
                                                //return $resp_data_create;
                                            }else{
                                                $data_convertida = $this->convertData($resp_data_create);
                                                if($data_convertida){
                                                    return $data_convertida;
                                                }
                                                //return $resp_data_create;
                                            }
                                        }
                                    }else{//Si, el campo phone no tiene 9 digitos
                                        return $_answers->error_221("The phone field must contain 9 digits");
                                    }
                                }else{//Si, el campo phone tiene letras
                                    return $_answers->error_220("The phone field only accepts numbers !");
                                }
                            }else{//Si, el campo phone esta vacio
                                $this->phone = $datos["phone"];
                                $this->name = $datos["name"];
                                $resp_update_profile = $this->update($this->cod_user);
                                if($resp_update_profile == 1){//Si, el usuario hizo modificaciones en su perfil
                                    $resp_data_create = $this->obtenerDataCreate($datos["id"]);
                                    if($resp_data_create == 0){
                                        //return $resp_data_create;
                                        $data_convertida = $this->convertData($resp_data_create);
                                        if($data_convertida){
                                            return $data_convertida;
                                        }

                                    }else{
                                        //print_r($resp_data_create);
                                        $data_convertida = $this->convertData($resp_data_create);
                                        if($data_convertida){
                                            return $data_convertida;
                                        }
                                    }   
                                }else{//Si, el usuario no hizo modificaciones en su perfil
                                    //return $resp_update_profile;
                                    $resp_data_create = $this->obtenerDataCreate($datos["id"]);
                                    if($resp_data_create == 0){
                                        //print_r($resp_data_create);
                                        $data_convertida = $this->convertData($resp_data_create);
                                        if($data_convertida){
                                            return $data_convertida;
                                        }
                                    }else{
                                        //print_r($resp_data_create);
                                        $data_convertida = $this->convertData($resp_data_create);
                                        if($data_convertida){
                                            return $data_convertida;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }

        private function update($id){
            $query = "UPDATE Users SET phone='$this->phone', name='$this->name' WHERE id='$id'";
            $resp = parent::nomQuery($query);
            if($resp >=1){
                return $resp;
            }else{
                return 0;
            }
        }

        private function obtenerData($token){
            //Crea la peticion para obtener los datos del usuario
            $query = "SELECT id,name ,lname, mail, photo, type, phone, bName, bPhone, bMail, bAddress, bPhoto, userStatus, token FROM Users WHERE token= '$token'";  
            $data = parent::obtenerDatos($query);//Realiza la peticion
            if(isset($data['0']['id'])){//Si, se realiza la peticion, enviamos la información
                return $data;
            }else{//Si, no se realiza la peticion enviamos un 0
                return $data;//return 0;
            }
        }

        private function obtenerDataCreate($id){
            //Crea la peticion para obtener los datos del usuario
            $query = "SELECT id,name ,lname, mail, photo, type, phone, bName, bPhone, bMail, bAddress, bPhoto, userStatus, token FROM Users WHERE id= '$id'";
            $data = parent::obtenerDatos($query);//Realiza la peticion
            if(isset($data['0']['id'])){//Si, se realiza la peticion, enviamos la información
                return $data;
            }else{//Si, no se realiza la peticion enviamos un 0
                return $data;//return 0;  
            }
        }

        private function searchAccount($email, $type_account, $password){
            $_answers = new answers;
            if($type_account == ""){//Usuario con cuenta normal
                $resp_normal = $this->obtenerDatosUsuarios($email, $password);
                if($resp_normal){
                    if(password_verify($password, $resp_normal[0]['password'])){
                        if($resp_normal[0]['userStatus'] == 'active'){//si, la cuenta esta activa
                            $verified = $this->createToken($resp_normal[0]['mail']);//Crear el token
                            if($verified){//si, el token se creo correctamente
                                $resp = $this->obtenerData($verified);
                                if($resp){
                                    return $resp;//Mostramos el mensaje en formato JSON
                                }else{
                                    return $_answers->error_500("Internal error. we could not save your request !");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                }
                            }else{//Si, no se creo el token
                                return $_answers->error_500("Internal error. we could not save your request !");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                            }
                        }else{//Si, la cuenta no esta activa
                            return $_answers->error_204("Account is not active !");//El usuario no se encuentra activo
                        }
                    }else{//El password es invalido
                        return $_answers->error_203("The password is invalid !");
                    }
                }else{//Si la peticion se realizo de forma incorrecta
                    return $_answers->error_500();//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                }
            }else{//Usuario con cuenta social
                $resp_social = $this->obtenerDatosUsuarios($email, $password);
                if($resp_social){
                    if($resp_social[0]['userStatus'] == 'active'){//si, la cuenta esta activa
                        $verified = $this->createToken($resp_social[0]['mail']);//Crear el token
                        if($verified){//si, el token se creo correctamente
                            $resp = $this->obtenerData($verified);
                            if($resp){
                                return $resp;//Mostramos el mensaje en formato JSON
                            }else{
                                return $_answers->error_500("Internal error. we could not save your request");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                            }
                        }else{
                            return $_answers->error_500("Internal error. we could not save your request");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                        }
                    }else{
                        return $_answers->error_204("Account is not active !");//El usuario no se encuentra activo
                    }
                }else{//Si, la peticion se hizo de forma incorrecta
                    return $_answers->error_500("Internal error. we could not save your request");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                }              
            }
        }

        private function createToken($email_user){
            /* Fecha de expiración del token */
            $date = date("Y-m-d");//Genera la fecha y la hora, en la que se genera el token
            $fecha = date_create($date);
            date_add($fecha, date_interval_create_from_date_string("1 month"));
            $fecha_expiracion_token = date_format($fecha,"Y-m-d");
            $val = true;
            $token = bin2hex(openssl_random_pseudo_bytes(50,$val));//Genera el token de forma aleatoria
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
            //if(!isset($datos['token']) || !isset($datos['cod_user'])){//Si el campo token no existe
            if(!isset($datos['pass'])){//Si el campo token no existe
                return $_answers->error_401();//Mostramos un mensaje, indicando que el usuario que esta realizando la peticion no tiene la autorizacion
            }else{//Si el campo token existe
                $passwor = $this->token = $datos['pass']; //Obtenemos el token del usuario
                $pass_encrypt = password_hash($passwor, PASSWORD_DEFAULT);
                print_r($passwor);
                /*
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
                }*/
            }
        }

        private function searchToken(){
            //Crea la peticion para buscar el token
            $query = "SELECT id, name, mail from Users WHERE token = '$this->token' and tokenStatus='active' and id='$this->cod_user'";
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

        public function createUser($json){
            $_answers = new answers;
            $datos = json_decode($json,true);
            if(!isset($datos["name"]) || !isset($datos["lname"]) || !isset($datos["mail"]) || !isset($datos["pass"]) ){//Si el campo tipo de email existe
                return $_answers->error_401();//error con los campos
            }else{
                if($datos["name"] == "" || $datos["lname"] == "" || $datos["mail"] == "" || $datos["pass"] == ""){//Si los campos estan vacios
                    if($datos["name"] == ""){
                        return $_answers->error_207("The name field is required !");
                    }else if($datos["lname"] == ""){
                        return $_answers->error_208("The lname field is required !");
                    }else if($datos["mail"] == ""){
                        return $_answers->error_209("The mail field is required !");
                    }else if($datos["pass"] == ""){
                        return $_answers->error_210("The password field is required !");
                    }else{
                        return $_answers->error_400();//Mostramos un mensaje de alerta, indicandole al usuario que los campos son requeridos*/
                    }       
                }else{//Si, los campos fueron llenados
                    if(!filter_var($datos["mail"], FILTER_VALIDATE_EMAIL)){//Si el email, es invalido
                        return $_answers->error_202("The email is invalid !!!");//Mostramos una alerta, indicandole al usuario que el email es invalido
                    }else{
                        //Verificamos que el email este en la base de datos
                        $result_email = $this->verifyEmail($datos["mail"]);
                        if($result_email == 1){//Si, el email existe
                            return $_answers->error_205("The email is already registered, enter another email !");//El usuario no se encuentra activo
                        }else{//Si, el email no existe
                            if(ctype_alpha($datos["pass"])){//El password solo tiene letras
                                return $_answers->error_206("Invalid password, the password must contain a special character !");//El password es invalido
                            }else{//El password si tiene caracteres especiales
                                $this->name = $datos['name'];
                                $this->lname = $datos['lname'];
                                $this->mail = $datos['mail'];
                                $this->password = $datos['pass'];
                                $res_create = $this->postUser();
                                if($res_create){
                                    $res_create_user = $this->obtenerDataCreate($res_create);
                                    if($res_create_user){
                                        $data_create_user = $this->convertData($res_create_user);
                                        if($data_create_user){
                                            return $data_create_user;
                                        }
                                    }else{
                                        return $_answers->error_500("Internal error. we could not save your request !");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                    }
                                }else{
                                    return $_answers->error_500("Internal error. we could not save your request !");//Mostramos un mensaje de alerta, indicando que hubo un error por parte del servidor
                                }
                               
                            }
                        }
                    }
                }
            }
        }

        private function postUser(){
            $date = date("Y-m-d");//Genera la fecha y la hora, en la que se genera el token
            $fecha = date_create($date);
            date_add($fecha, date_interval_create_from_date_string("1 month"));
            $fecha_expiracion_token = date_format($fecha,"Y-m-d");

            $pass_encrypt = password_hash($this->password, PASSWORD_DEFAULT);
            $val = true;
            $token_generate = bin2hex(openssl_random_pseudo_bytes(50,$val));
            //Crea la peticion para crear una cuenta
            $query = "INSERT INTO Users (name, lname, mail, password, userStatus, token, tokenStatus, tokenCreationDate, tokenExpirationDate) values ('$this->name', '$this->lname', '$this->mail', '$pass_encrypt', 'active', '$token_generate', 'active', '$date', '$fecha_expiracion_token')";
            $resp = parent::nomQueryId($query);//Ejecuta la peticion 
            if($resp){//Si se realiza la peticion, enviamos la informacion
                return $resp;
            }else{//Si, no se realiza la peticion enviamos un 0
                return 0;
            }
        }

        private function convertData($res){
            $information['id'][0] = $res[0]["id"];
            $information['name'][0] = $res[0]["name"];
            $information['lname'][0] = $res[0]["lname"];
            $information['mail'][0] = $res[0]["mail"];
            $information['photo'][0] = $res[0]["photo"];
            $information['type'][0] = $res[0]["type"];
            $information['phone'][0] = $res[0]["phone"];
            $information['bName'][0] = $res[0]["bName"];
            $information['bPhone'][0] = $res[0]["bPhone"];
            $information['bPhoto'][0] = $res[0]["bPhoto"];
            $information['bMail'][0] = $res[0]["bMail"];
            $information['bAddress'][0] = $res[0]["bAddress"];
            $information['userStatus'][0] = $res[0]["userStatus"];
            $information['token'][0] = $res[0]["token"];

            return $information;
        }

    }

?>