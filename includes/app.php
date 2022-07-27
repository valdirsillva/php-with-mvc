<?php 

require __DIR__ . "/../vendor/autoload.php";

use App\Utils\View;
use App\Config\HandleEnviromentVariable;
use \WilliamCosta\DatabaseManager\Database;

// carrega variaveis de ambiente
HandleEnviromentVariable::get();

// define as config de banco dados
Database::config(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_PORT']
);

define('URL',$_ENV['URL']);

View::init([
    'URL' => URL
]);
