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

$obRouter->get('/depoimentos', [
    function() {
        return new Response(200, Pages\Testimony::getTestimonies());
    }
]);

// rota de depoimento (insert)
$obRouter->post('/depoimentos', [
    function($request) {
        // echo '<pre>';
        // print_r($request);
        // echo '</pre>';
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);



// $obRouter->get('/pagina/{idPagina}/{acao}', [
//     function($idPagina, $acao) {
//         return new Response(200, 'Página'.$idPagina. '-' . $acao);
//     }
// ]);

