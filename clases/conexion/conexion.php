<?php

class conexion {

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    //Crea un constructor
    function __construct(){
        //Obetenemos la informacion para conectarnos con la base de datos
        $listadatos = $this->datosConexion();
        foreach ($listadatos as $key => $value) {
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        //Realiza la conexion con la base de datos
        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        //Enviamos un mensaje de error, si los datos no son correctos
        if($this->conexion->connect_errno){
            echo "algo va mal con la conexion";
            die();
        }

    }
    //Obtenemos los datos que nos permitiran conectarnos con la base de datos
    private function datosConexion(){
        //Define el directorio para obtener la informacion
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config");
        //Retornamos la informacion en formato JSON
        return json_decode($jsondata, true);
    }
    //Convierte los caracteres en UTF8
    private function convertirUTF8($array){
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });
        //Retornamos el valor en un arreglo
        return $array;
    }
    //Funcion que sirve para obtener datos de la base de datos
    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach ($results as $key) {
            $resultArray[] = $key;
        }
        //Retorna los valores en UTF8
        return $this->convertirUTF8($resultArray);
    }
    //Funcion que sirve para realizar una consulta en la base de datos
    public function nomQuery($sqlstr){
        $results = $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }
    //Funcion que sirve para realizar una consulta a traves de un identificador
    public function nomQueryId($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $filas = $this->conexion->affected_rows;

        if($filas >=1){
            return $this->conexion->insert_id;
        }else{
            return 0;
        }
    }
    //Funcion que sirve para encriptar el password
    public function encrypt($value){
        return md5($value);
    }
}

?>