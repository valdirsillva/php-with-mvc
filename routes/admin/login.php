<?php 


use App\Http\Response;
use App\Controller\Admin;


// rota de login
$obRouter->get('/admin/login', [
    'middlewares' => [
        'require-admin-logout'
    ],
    function($request) {
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

$obRouter->post('/admin/login',  [
    'middlewares' => [
        'require-admin-logout'
    ],

    function($request) {
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

$obRouter->get('/admin/logout', [
    function($request) {
        return new Response(200, Admin\Login::setLogout($request));
    }
]);