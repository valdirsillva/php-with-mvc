<?php 


use App\Http\Response;
use App\Controller\Admin;


// rota de listagem de depoimento
$obRouter->get('/admin/testimonies', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

// Rota de cadastro de depoimento
$obRouter->get('/admin/testimonies/new', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);

// Rota de edição de um depoimento
$obRouter->get('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
    }
]);

$obRouter->post('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
    }
]);

// Deletar depoimemto
$obRouter->get('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
    }
]);

// Deletar depoimemto (POST)
$obRouter->post('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
    }
]);

// Rota de cadastro de depoimento (POST)
$obRouter->post('/admin/testimonies/new', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Testimony::setNewTestimony($request));
    }
]);

