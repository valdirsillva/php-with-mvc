<?php 

use App\Http\Response;

use App\Controller\Pages;

// rota home 
$obRouter->get('/', [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->get('/sobre', [
    function() {
        return new Response(200, Pages\About::getHome());
    }
]);

$obRouter->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao) {
        return new Response(200, 'Página'.$idPagina. '-' . $acao);
    }
]);

