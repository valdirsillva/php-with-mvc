<?php 


namespace App\Http\Middleware;


class Api 
{
    /**
     * Método responsavel por executar o middleware
     */
    public function handle($request, $next) 
    {
    
       // Altera contentType para JSON
       $request->getRouter()->setContentType('application/json');

       // executa próximo nível
       return $next($request);
    }    
}