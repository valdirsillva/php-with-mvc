<?php 


namespace App\Http;


class Request 
{
    private string $httpMethod;

    private string $uri;

    private $queryParams = [];

    private $postVars = [];

    private $headers = [];

    private $router;


    public function __construct($router) 
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? [];
        
        $this->setUri(); 
        $this->setPostVars(); 
    }

    // Método responsavel poe definir as variaveis do POST
    private function setPostVars() 
    {
        // Verifica o metodo da requisição
        if ($this->httpMethod == 'GET') return false;

        // POST padrão
        $this->postVars = $_POST ?? [];

        // POST JSON
        $inputRaw = file_get_contents('php://input');

        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;

    }

    private function setUri() 
    {
        // uri completa com GETS
        $this->uri = $_SERVER['REQUEST_URI'] ?? [];

        // remove gets da uri
        $xUri = explode('?',$this->uri);

        $this->uri = $xUri[0];
    }

    public function getRouter() 
    {
        return $this->router;
    }

    // metodo responsável por retornar o método HTTP da requisição
    public function getHttpMethod() 
    {
        return $this->httpMethod;
    }

    public function getUri() 
    {
        return $this->uri;
    }

    public function getHeaders() 
    {
        return $this->headers;
    }

    public function getQueryParams() 
    {
        return $this->queryParams;
    }

    public function getPostVars() 
    {
        return $this->postVars;
    }

}