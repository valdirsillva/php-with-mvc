<?php 

require __DIR__ . "/vendor/autoload.php";

use App\Http\Router;
use App\Http\Response;

use App\Controller\Pages\Home;

define('URL','http://localhost/php-with-mvc');

$obRouter = new Router(URL);

// rota home 
$obRouter->get('/', [
    function() {
        return new Response(200, Home::getHome());
    }
]);

// imprime o response da rota
$obRouter->run()->sendResponse();