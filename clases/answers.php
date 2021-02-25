<?php

class answers {
    //Funcion que muestra el estado 200
    public $response =[
        'status' => 'ok',
        'result' => array()
    ];
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
        $this->response['result'] = array(
            'error_id' => '200',
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