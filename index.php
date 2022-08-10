<?php 

require __DIR__ . '/includes/app.php';
use App\Http\Router;

// initia o router
$obRouter = new Router(URL);

include __DIR__ . '/routes/pages.php';

// inclui rotas do Admin
include __DIR__ . '/routes/admin.php';


// inclui rotas da Api
include __DIR__ . '/routes/api.php';


// imprime o response da rota
$obRouter->run()->sendResponse();