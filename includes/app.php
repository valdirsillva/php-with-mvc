<?php 

require __DIR__ . "/../vendor/autoload.php";

use App\Utils\View;
use App\Config\HandleEnviromentVariable;
use \WilliamCosta\DatabaseManager\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

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

// define o mapeamento de middlewares
MiddlewareQueue::setMap([
    'maintenance' => App\Http\Middleware\Maintenance::class,
    'require-admin-logout' => App\Http\Middleware\RequireAdminLogout::class,
    'require-admin-login' => App\Http\Middleware\RequireAdminLogin::class
]);


// define o mapeamento de middlewares padrões executados em todas as rotas
MiddlewareQueue::setDefault([
    'maintenance'
]);
