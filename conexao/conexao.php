<?php

    require_once 'Config.php';

    class Conexao{

        private static $mongo;

        public static function getInstance(){
            if(!isset(self::$mongo)){
                try {
                    self::$mongo = new MongoDB\Driver\Manager('mongodb://' . DB_HOST . ':' . DB_PORT . '/' . DB_NAME);     
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    $filename = basename(__FILE__);
                    echo "Erro no arquivo", $filename, "\n";
                    echo "Exception:", $e->getMessage(), "\n";
                    echo "Arquivo:", $e->getFile(), "\n";
                    echo "Linha:", $e->getLine(), "\n";    
                }
            }    

            return self::$mongo;
        }
    }
?>
