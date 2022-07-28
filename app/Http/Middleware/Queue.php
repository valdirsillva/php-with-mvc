<?php


namespace App\Http\Middleware;


class Queue 
{
    // mapeamento de middlewares
    private static $map = [];
    
    private static $default = [];

    // fila de middlewares  a serem executados
    private $middlewares = [];

    private $controller;
    
    /**
     * Argumentos da função do controlador
     */
    private $controllerArgs = [];


    public function __construct($middlewares, $controller, $controllerArgs) 
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    public static function setMap($map) 
    {
        self::$map = $map;
    }

    public static function setDefault($default) 
    {
        self::$default = $default;
    }
    
    // executa proximo nivel da fila de middleware
    public function next($request) 
    {
       // verifica se a fila está vazia
       if (empty($this->middlewares)) 
            return call_user_func_array($this->controller, $this->controllerArgs);

       // middleware
       $middleware = array_shift($this->middlewares);

       // Verifica o mapeamento
       if (!isset(self::$map[$middleware])) {
         throw new \Exception('Problemas ao processar o middleware', 500);
       }

       // Next
       $queue = $this;
       $next = function($request) use($queue) {
         return $queue->next($request);
       };
       
       // executa o middleware
       return (new self::$map[$middleware])->handle($request, $next);
    }


}