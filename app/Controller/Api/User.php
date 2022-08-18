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

        // busca depoimento
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
   
    public static function setEditTestimony($request, $id) 
    {
        // PostVars
        $postVars = $request->getPostVars();
        
        // Valida os campos obrigatorios
        if (!isset($postVars['nome']) or !isset($postVars['mensagem'])) {
            throw new \Exception('Os campos nome e mensagem são obrigatorios', 400);
        }

        // BUsca o depoimento no banco
        $obTestimony = EntityUser::getTestimonyById($id);

        // Valida a instancia
        if (!$obTestimony instanceof EntityUser) {
            throw new \Exception("O depoimento" .$id. " não foi encontrado", 404);
        }

        // Atualiza o depoimento
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->setUpdate();

        // Retorna os detalhes do depoimento atualizado
        return [
            'id' => $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    // Metodo responsavel
    public static function setDeleteTestimony($request, $id) 
    {
        // BUsca o depoimento no banco
        $obTestimony = EntityUser::getTestimonyById($id);

        // Valida a instancia
        if (!$obTestimony instanceof EntityUser) {
            throw new \Exception("O depoimento" .$id. " não foi encontrado", 404);
        }

        // Exclui o depoimento
        $obTestimony->setDelete();

        // Retorna sucesso da exclusão
        return [
            'id' => true
        ];
    }
}