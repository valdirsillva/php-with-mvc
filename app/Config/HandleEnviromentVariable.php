<?php 


namespace App\Config;

use Dotenv;


class HandleEnviromentVariable 
{
    public static function get() 
    {
        $path = $_SERVER['DOCUMENT_ROOT'] .'/php-with-mvc/';
        $dotenv = Dotenv\Dotenv::createImmutable($path);
        $variable = $dotenv->load();

        return $variable;
    }
}



