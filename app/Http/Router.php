<?php 

namespace App\Http;

use \Closure;
use \Exception;

class Router 
{
    private string $url = '';

    private string $prefix = '';

    private $routes = [];

    private $request;

    public function __construct($url = '') 
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    private function setPrefix() 
    {
        // informações da url atual
        $parseUrl = parse_url($this->url);

        // define o prefixo 
        $this->prefix = $parseUrl['path'] ?? '';
    }

    private function addRoute($method, $route, $params = []) 
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';
        // add rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;        
    }

    public function get($route, $params = []) 
    {
        return $this->addRoute('GET', $route, $params);
    }

    public function post($route, $params = []) 
    {
        return $this->addRoute('POST', $route, $params);
    }

    public function put($route, $params = []) 
    {
        return $this->addRoute('PUT', $route, $params);
    }

    public function delete($route, $params = []) 
    {
        return $this->addRoute('DELETE', $route, $params);
    }


    // Executa a rota atual
    public function run() 
    {
        try {
            // obtém a rota atual
            $route = $this->getRoute();

        } catch(Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    private function getRoute()
    {
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();

        foreach($this->routes as $patternRoute => $methods) {
            // verifica se a uri bate com o padrao
            if (preg_match($patternRoute, $uri)) {
                
                // verifica o metodo
                if (isset($methods[$httpMethod])) {
                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
        }

        // url não encontrada
        throw new Exception("URL não encontrada", 404);

    }

    private function getUri()
    {
        $uri = $this->request->getUri();

        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        return end($xUri);
    }

}