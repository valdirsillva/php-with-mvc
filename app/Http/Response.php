<?php 



namespace App\Http;

class Response 
{
    private int $httpCode = 200;

    private $headers = [];

    private $contentType = 'text/html';

    private $content;

    public function __construct($httpCode, $content, $contentType = 'text/html') 
    {
        $this->httpCode = $httpCode;
        $this->content = $content;

        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o contentType do response
     */
    public function setContentType($contentType) 
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Add um novo registro no cabecalho do response
     */
    public function addHeader($key, $value) 
    {
        $this->headers[$key] = $value;
    }

    private function sendHeaders() 
    {
        // status de retorno do app
        http_response_code($this->httpCode);

        // enviar headers 
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    public function sendResponse() 
    {
        // envia os  headers
        $this->sendHeaders();
        
        /**
         * Imprime o conteúdo
         */
        switch($this->contentType) {
            case 'text/html';
            echo $this->content;
            exit;     
        }       
    }
}