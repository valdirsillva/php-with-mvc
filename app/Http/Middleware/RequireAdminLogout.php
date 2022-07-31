<?php 


namespace App\Http\Middleware;

use App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout 
{
    public function handle($request, $next) 
    {
        // Verifica se o usuario está logado
        if (SessionAdminLogin::isLogged()) {
            $request->getRouter()->redirect('/admin');
        }

        // continua a execução
        return $next($request);
    }
}