<?php 


namespace App\Http\Middleware;
use App\Model\Entity\User;
use Firebase\JWT\JWT;


class JWTAuth
{
    /**
     * Método responsavel por validar o acesso HTTP BJWT
     */
    private function auth($request) 
    {
        // Verifoca o usuario recebido
        if ($obUser = $this->getJWTAuthUser()) {
            $request->user = $obUser;
            
            return true;
        }
        // Emite o erro de senha invalida
        throw new \Exception("Acesso negado.", 403);
    }

    // Responsavel por retornar uma instancia de usuario autenticado
    private function getJWTAuthUser($request) 
    {
        // Headers 
        $headers = $request->getHeaders();

        // Token puro em JWT
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

        try {
            // Decode 
            $decode = (array)JWT::decode($jwt, $_ENV['JWT_KEY'],['HS256']);

        } catch(\Exception $e) {
            throw new \Excepton("Token inválido", 403);
        }

        $email = $decode['email'] ?? '';

        // Busca usuario pelo email
        $obUser = User::getUserByEmail($email);
        
        // Retorna o usuário
        return $obUser instanceof User ? $obUser : false;

    }
    
    public function handle($request, $next) 
    {
       // Realiza a validação do acesso via JWT
       $this->auth($request);
    
       // executa próximo nível
       return $next($request);
    }    
}