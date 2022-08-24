<?php 


namespace App\Controller\Api;

use App\Model\Entity\User;
use Firebase\JWT\JWT;

class Auth extends Api 
{
    /**
     * Método responsável por gerar o token JWT
     * @param Request $Request
     * @return array
     */
    public static function generateToken($request) 
    {
        // PostVars 
        $postVars = $request->getPostVars();

        // Valida os campos obrigatórios
        if(!isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new \Exception("Os campos Email e senha são obrigatórios", 400);
        }

        // Busca usuário pelo email
        $obUser = User::getUserByEmail($postVars['email']);

        if(!$obUser instanceof User ){
            throw new \Exception("O Usuário ou senha são inválidos.", 400);
        }

        // Valida a senha do usuário
        if (!password_verify($postVars['senha'], $obUser->senha)) {
            throw new \Exception("O Usuário ou senha são inválidos.", 400);
        }

        // Payload
        $payload = [
            'email' => $obUser->email
        ];

        // Retorna o token gerado
        return [
            'token' => JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256')
        ];
    }
}