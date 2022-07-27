<?php 

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

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

        // variaveis da rota
        $params['variables'] = [];

        //padrao de validacao das rotas
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable,$route,$matches)) {
            $route = preg_replace($patternVariable,'(.*?)', $route);
            $params['variables'] = $matches[1];
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

            // verifica o controlador
            if (!isset($route['controller'])) {
                throw new Exception("URL não pode ser processada", 500);
            }
            
            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            
            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $args);

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
            if (preg_match($patternRoute, $uri, $matches)) {
                
                // verifica o metodo
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);
                    
                    // variaveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request; 

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