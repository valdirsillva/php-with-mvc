<?php 

require __DIR__ . "/vendor/autoload.php";

use App\Http\Router;
use App\Utils\View;

use App\Config\HandleEnviromentVariable;

// carrega variaveis de ambiente
HandleEnviromentVariable::get();


define('URL',$_ENV['URL']);

View::init([
    'URL' => URL
]);

// initia o router
$obRouter = new Router(URL);

include __DIR__ . '/routes/pages.php';

// imprime o response da rota
$obRouter->run()->sendResponse();