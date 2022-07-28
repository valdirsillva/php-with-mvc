<?php 


namespace App\Http\Middleware;


class Maintenance 
{
    /**
     * Método responsavel por executar o middleware
     */
    public function handle($request, $next) 
    {
        // Verifica estado de manutenção
       if ($_ENV['MAINTENANCE'] == 'true') {
          throw new \Exception('Página em manutenção', 200);
       } 

       // executa próximo nível
       return $next($request);
    }    
}