<?php 

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User 
{ 
    public int $id;

    public string $name;

    public string $email;

    public string $password;

    public function add() 
    {
        // insere usuario no banco de dados
        $this->id = ( new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);

        return true;
    }

    public function setUpdate() 
    {
        // atualiza dados do usuario
        return( new Database('usuarios'))->update('id = '.$this->id, [
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);
    }

    public function setDelete() 
    {
       // deleta usuario do banco de dados
       return( new Database('usuarios'))->delete('id = '.$this->id );  
    }

    public static function getUserById($id) 
    {
        return self::getUsers('id = '. $id)->fetchObject(self::class);   
    }

    /**
     * Método responsável por retornar um usuário com base em seu e-mail.
     */
    public static function getUserByEmail(string $email) 
    {
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }

    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*') 
    {
        return ( new Database('usuarios'))->select($where, $order, $limit, $fields);
    }

    
}