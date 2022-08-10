<?php 

use App\Http\Response;
use App\Controller\Api;

// Rota de listagem de depoimentos
$obRouter->get('/api/v1/testimonies', [
    function($request) {
        return new Response(200, Api\Testimony::getTestimonies($request), 'application/json');
    }
]);