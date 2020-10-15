<?php
namespace MF\Model;
use App\Connection;

class Container{

    public static function getModel($model){
        $class = "\\App\\Models\\".ucfirst($model);

        // returnar o modelo selecionado já instanciado e conectado
        $conn = Connection::getDB();
        return new $class($conn);
    }

}

?>