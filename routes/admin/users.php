<?php 


use App\Http\Response;
use App\Controller\Admin;


// rota de listagem de usuarios
$obRouter->get('/admin/users', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\User::getUsers($request));
    }
]);

// Rota de cadastro de usuarios
$obRouter->get('/admin/users/new', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\User::getNewUser($request));
    }
]);


$obRouter->post('/admin/users/new', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\User::setNewUser($request, $id));
    }
]);


// Rota de edição de um usuario
$obRouter->get('/admin/users/{id}/edit', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\User::getEditUser($request, $id));
    }
]);

$obRouter->post('/admin/users/{id}/edit', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\User::setEditUser($request, $id));
    }
]);

// Deletar depoimemto
$obRouter->get('/admin/users/{id}/delete', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\User::getDeleteUser($request, $id));
    }
]);

// Deletar depoimemto (POST)
$obRouter->post('/admin/users/{id}/delete', [
    'middlewares' => [
        'require-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Admin\User::setDeleteUser($request, $id));
    }
]);

// // Rota de cadastro de usuarios (POST)
// $obRouter->post('/admin/users/new', [
//     'middlewares' => [
//         'require-admin-login'
//     ],
//     function($request) {
//         return new Response(200, Admin\User::setNewTestimony($request));
//     }
// ]);

