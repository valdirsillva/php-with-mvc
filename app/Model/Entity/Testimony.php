<?php 

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony 
{
    public int $id;

    public string $nome;

    public string $mensagem;

    public $data;

    public function add() 
    {
        $this->data = date('Y-m-d H:i:s');
        
        // insere depoimento no banco de dados
        $this->id = ( new Database('depoimentos'))->insert([
            'nome' => $this->nome,
            'mensagem' => $this->mensagem,
            'data' => $this->data
        ]);

        return true;
    }
}