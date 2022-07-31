<?php 

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User 
{ 
    public int $id;

    public string $name;

    public string $email;

    public string $password;


    /**
     * Método responsável por retornar um usuário com base em seu e-mail.
     */
    public static function getUserByEmail(string $email) 
    {
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }
}