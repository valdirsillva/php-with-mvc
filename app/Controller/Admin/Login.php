<?php


namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page 
{
    public static function getLogin($request, $errorMessage = null) 
    {
        // status
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';


        // conteudo da página de login
        $content = View::render('admin/login', [
            'status' => $status
        ]);

        return parent::getPage('Login > Sistema php', $content);
    }

    /**
     * Método responsável por definir o login do usuário
     */
    public static function setLogin($request) 
    {
        // post vars
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        // busca usuario pelo e-mail
        $user = User::getUserByEmail($email);

        if (!$user instanceof User ) {
            return self::getLogin($request, 'E-mail ou senha inválidos!');
        }

        // Verifica a senha do usuário
        if (!password_verify($password, $user->senha)) {
            return self::getLogin($request, 'E-mail ou senha inválidos!');
        }

        // Cria a sessão de login
        SessionAdminLogin::login($user);

        // redireciona usuário para a home do Admin
        $request->getRouter()->redirect('/admin');
    }


    public static function setLogout($request) 
    {
        // Destroy a sessão de login
        SessionAdminLogin::logout($user);

        // redireciona usuário para a tela de login
        $request->getRouter()->redirect('/admin/login');
    }
}