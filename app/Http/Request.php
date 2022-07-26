<?php 


namespace App\Http;


class Request 
{
    private string $httpMethod;

    private string $uri;

    private $queryParams = [];

    private $postVars = [];

    private $headers = [];


    public function __construct() 
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? [];
        $this->uri = $_SERVER['REQUEST_URI'] ?? [];
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