<?php 


namespace App\Http\Middleware;
use App\Model\Entity\User;

class UserBasicAuth
{


    /**
     * Método responsavel por validar o acesso HTTP BASIC AUTH
     */
    private function basicAuth($request) 
    {
        // Verifoca o usuario recebido
        if ($obUser = $this->getBasicAuthUser()) {
            $request->user = $obUser;
            
            return true;
        }
        // Emite o erro fe senha invalida
        throw new \Exception("Usuário ou senha invalidos", 403);
    }

    // Responsavel por retornar uma instancia de usuario autenticado
    private function getBasicAuthUser() 
    {
        // Verifica a existencia  dos dados de acesso
        if (!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }
        
        // Busca usuario pelo email
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        
        // Verifica a instancia
        if(!$obUser instanceof User) {
            return false;
        }

        // Valida senha e retorna usuário
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
    }
    
    public function handle($request, $next) 
    {
       // Realiza a validação do acesso via Basic Auth
       $this->basicAuth($request);
    
       // executa próximo nível
       return $next($request);
    }    
}