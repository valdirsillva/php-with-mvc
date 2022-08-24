<?php 

use App\Http\Response;
use App\Controller\Api;

// Rota de listagem de usuários
$obRouter->get('/api/v1/users', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request) {
        return new Response(200, Api\User::getUsers($request), 'application/json');
    }
]);

// Rota de consulta do usuário atual
$obRouter->get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request) {
        return new Response(200, Api\User::getCurrentUser($request), 'application/json');
    }
]);


// Rota para pegar um usuário
$obRouter->get('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth',
        'jwt-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::getUser($request, $id), 'application/json');
    }
]);

// Rota de cadastro de usuário
$obRouter->post('/api/v1/users', [
    'middlewares' => [
        'api',
        'user-basic-auth',
        'jwt-auth'
    ],
    function($request) {
        return new Response(201, Api\User::setNewUser($request), 'application/json');
    }
]);

// Rota de alteração de usuário
$obRouter->put('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth',
        'jwt-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::setEditUser($request, $id), 'application/json');
    }
]);

// Rota de exclusão de usuário
$obRouter->delete('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request, $id) {
        return new Response(200, Api\User::setDeleteUser($request, $id), 'application/json');
    }
]);