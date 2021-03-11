<?php

class answers {
    //Funcion que muestra el estado 200
    public $response;
    //Funcion que muestra el error 405
    public function error_405(){
        //Crea la informacion del error
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'error_id' => '405',
            'error_msg' => 'Method not allowed'
        );
        //Retorna el mensaje de error
        return $this->response;
    }
    //Funcion que muestra el error 200
    public function error_200($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response['status'] = 'error';
        $this->response = array(
            'error_id' => '200',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }
    //Email no esta registrado
    public function error_201($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '201',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }
    //Email invalido
    public function error_202($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '202',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //El password es invalido
    public function error_203($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '203',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //El usuario no esta activo
    public function error_204($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '204',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }
    //El email ya esta registrado
    public function error_205($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '205',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //El password es invalido, debe de contener un caracter
    public function error_206($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '206',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo name esta vacio
    public function error_207($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '207',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo lname esta vacio
    public function error_208($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '208',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo mail esta vacio
    public function error_209($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '209',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo password esta vacio
    public function error_210($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '210',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo id esta vacio
    public function error_211($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '211',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el token es invalido
    public function error_212($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '212',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo token esta vacio
    public function error_213($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '213',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo type esta vacio
    public function error_214($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '214',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el keyword tiene el estado inactivo
    public function error_215($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '215',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el keyword no existe
    public function error_216($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '216',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo keyword esta vacio
    public function error_217($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '217',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el keyword existe
    public function error_218($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '218',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo phone esta vacio
    public function error_219($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '219',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Cuando el campo phone tiene letras
    public function error_220($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '220',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //El tamaño del campo phone es incorrecto
    public function error_221($value = "Incorrect data"){
        //Crea la informacion del error
        $this->response = array(
            'status' => 'Error',
            'error_id' => '221',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }

    //Funcion que muestra el error 400
    public function error_400(){
        //Crea la informacion del error
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'error_id' => '400',
            'error_msg' => 'Incomplete data'
        );
        //Retorna el mensaje de error
        return $this->response;
    }
    //Funcion que muestra el error 500
    public function error_500($value = "Internal Server Error"){
        //Crea la informacion del error
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'error_id' => '500',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }
    //Funcion que muestra el error 401
    public function error_401($value = "Not authorized"){
        //Crea la informacion del error
        $this->response['status'] = 'error';
        $this->response['result'] = array(
            'error_id' => '401',
            'error_msg' => $value
        );
        //Retorna el mensaje de error
        return $this->response;
    }
}
?>