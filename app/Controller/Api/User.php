<?php 


namespace App\Controller\Api;

use App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

 
class User extends Api 
{

    // Obtem os dados dos itens de usuarios a ser rebderizados
    private static function getUserItems($request, &$pagination) 
    {
        $itens = [];

        // quantidade total registros
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // instancia paginacao
        $pagination = new Pagination($quantidadeTotal, $paginaAtual, 2);

        $results = EntityUser::getUsers(null, 'id ASC', $pagination->getLimit());

        // renderiza o item
        while($obUser = $results->fetchObject(EntityUser::class)) {
            $itens[] = [
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ];
        }

        return $itens;
    }


    public static function getUsers($request) 
    {
        return [
            'usuarios' => self::getUserItems($request, $pagination),
            'paginacao' => parent::getPagination($request, $pagination)
        ];
    }

    public static function getUser($request, $id) 
    {
        // Valida o id do usuário
        if (!is_numeric($id)) {
            throw new \Exception("O id não é válido", 400); 
        }

        // busca usuario
        $obUser = EntityUser::getUserById($id);

        // Valida se o usuario existe
        if(!$obUser instanceof EntityUser) {
            throw new \Exception("O Usuário" .$id. " não foi encontrado", 404);
        }

        return [
            'id' => $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];

    }

    public static function getCurrentUser($request) 
    {
        // Usuário atual
        $obUser = $request->user;

        return [
            'id' => $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }
    
    // Método responsável por cadastrar um novo Usuario
    public static function setNewUser($request) 
    {
        // PostVars
        $postVars = $request->getPostVars();
        
        // Valida os campos obrigatorios
        if (!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new \Exception('Os campos nome, email e senha são obrigatorios', 400);
        }

        // Valida a duplicação de email
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser) {
            throw new \Exception(" O E-mail'".$postVars['email']."' já está em uso", 400);
        }
         
        // Novo usuario
        $obUser = new EntityUser;
        $obUser->nome = $postVars['nome'];
        $obUser->email = $postVars['email'];
        $obUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obUser->add();

        // Retorna os detalhes do usuario cadastrado
        return [
            'id' => $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];  
    }
   
    public static function setEditUser($request, $id) 
    {// PostVars
        $postVars = $request->getPostVars();
        
        // Valida os campos obrigatorios
        if (!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])) {
            throw new \Exception('Os campos nome, email e senha são obrigatorios', 400);
        }

        // busca usuario
        $obUser = EntityUser::getUserById($id);

        // Valida se o usuario existe
        if(!$obUser instanceof EntityUser) {
            throw new \Exception("O Usuário" .$id. " não foi encontrado", 404);
        }

        // Valida a duplicação de email
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser && $obTestimony->id != $obUser->id) {
            throw new \Exception(" O E-mail'".$postVars['email']."' já está em uso", 400);
        }
         
        // Atualiza usuario
        $obUser->nome = $postVars['nome'];
        $obUser->email = $postVars['email'];
        $obUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obUser->setUpdate();

        // Retorna os detalhes do usuario cadastrado
        return [
            'id' => $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];  
    }

    // Metodo responsavel por excluir um usuário
    public static function setDeleteUser($request, $id) 
    {
        // BUsca o usuario no banco
        $obUser = EntityUser::getUserById($id);

        // Valida a instancia de usuário
        if (!$obUser instanceof EntityUser) {
            throw new \Exception("O Usuário" .$id. " não foi encontrado", 404);
        }

        // Impede a exclusão do próprio cadastro
        if ($obUser->id == $request->user->id) {
            throw new \Exceptio("Não é possível excluir o cadastro atualmente conectado", 400);
        }

        // Exclui o usuário
        $obUser->setDelete();

        // Retorna sucesso da exclusão
        return [
            'id' => true
        ];
    }
}