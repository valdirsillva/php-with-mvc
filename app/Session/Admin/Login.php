<?php


namespace App\Session\Admin;


class Login 
{
    // Método responsável por iniciar a sessão
    private static function init() 
    {
        if (session_status() != PHP_SESSION_ACTIVE ) {
            session_start();
        }
    }

    public static function login($user): bool
    {
        // inicia a sessão
        self::init();
        
        // Define a sessão do usuário 
        $_SESSION['admin']['usuario'] = [
            'id' => $user->id,
            'nome' => $user->nome,
            'email' => $user->email
        ];

        return true; // sucesso
    }

    // Método responsável por verificar se o usuario está logado
    public static function isLogged() 
    {
        self::init();

        // se existir um usuario logado retorne o id  
        return isset($_SESSION['admin']['usuario']['id']);
    }

    public static function logout() 
    {
        self::init();
        
        // Desloga usuario
        unset($_SESSION['admin']['usuario']);

        return true;
   }
}