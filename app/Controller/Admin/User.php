<?php


namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;


class User extends Page 
{
       // Obtem os dados dos itens a ser renderizados
    private static function getUserItems($request, &$pagination) 
    {
           $itens = '';
   
           // quantidade total registros
           $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
           
           $paginaAtual = $request->getQueryParams();
           $paginaAtual = $queryParams['page'] ?? 1;
   
           // instancia paginacao
           $pagination = new Pagination($quantidadeTotal, $paginaAtual, 2);
   
           $results = EntityUser::getUsers(null, 'id DESC', $pagination->getLimit());
   
           // renderiza o item
           while($obUser = $results->fetchObject(EntityUser::class)) {
            
               $itens .= View::render('admin/modules/users/item', [
                   'id' => $obUser->id,
                   'nome' => $obUser->nome,
                   'email' => $obUser->email
               ]);
           }
   
           return $itens;
    }

       
    /**
     * Método responsável por renderizar a view de listagem de usuarios
     */
    public static function getUsers($request) 
    {
        $content = View::render('admin/modules/users/index', [
            'itens' => self::getUserItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination),
            'status' => self::getStatus($request)
        ]);

        // retorna a pagina completa
        return parent::getPanel('Usuarios > Sistema', $content, 'users');
    }

    public static function getNewUser($request) 
    {
        // Conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
          'title' => 'Cadastrar Usuário',
          'nome' => '',
          'email' => '',
          'status' => self::getStatus($request)
        ]);

        // retorna a pagina completa
        return parent::getPanel('Cadastrar usuario > Sistema', $content, 'users');
    }

    
    // Cadastra um usuário no bancpo de dados
    public static function setNewUser($request) 
    {
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        // Valida o e-mail do usuario
        $obUser = EntityUser::getUserByEmail($email);
        if($obUser instanceof EntityUser) {
           // Redireciona usuário
           $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }
        
        // Instancia de Depoimento
        $newUser = new EntityUser;
        $newUser->nome = $nome;
        $newUser->email = $email;
        $newUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $newUser->add();

        // redireciona o usuario
        $request->getRouter()->redirect('/admin/users/'.$newUser->id .'/new?status=created');
    }

    private static function getStatus($request) 
    {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['status'])) return '';
        
        // Mensagens de status
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O E-mail digitado já está sendo usado por outro usuário.');
                break;           
        }

    }
 
    public static function getEditUser($request, $id) 
    {
        // Obtem o depoimento do Banco de Dados
        $obUser = EntityUser::getUserById($id);

        // Valida a instancia
        if(!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // Conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
            'title' => 'Editar usuario',
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'status' => self::getStatus($request)
        ]);
  
        // retorna a pagina completa
        return parent::getPanel('Editar usuario > Sistema', $content, 'users');   
    }
    
    /**
     * Método responsável por gravar uma atualização de um usuario
     */
    public static function setEditUser($request, $id) 
    {
        // Obtem o usuario do Banco de Dados
        $obUser = EntityUser::getUserById($id);

        // Pega variaveis do postVars
        $postVars = $request->getPostVars();

        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        // Valida o e-mail do usuario
        $obUserEmail = EntityUser::getUserByEmail($email);

        // Valida a instancia
        if(!$obUserEmail instanceof EntityUser &&  $obUserEmail->id != $id) {
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }
        
        // Atualiza a instancia
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->setUpdate();

        // redireciona o usuario
        $request->getRouter()->redirect('/admin/users/'. $id .'/edit?status=updated');   
    }

    
    public static function getDeleteUser($request, $id) 
    {
        // Obtem o usuario do Banco de Dados
       $obUser = EntityUser::getUserById($id);

        // Valida a instancia
        if(!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }

        // Conteúdo do formulário
        $content = View::render('admin/modules/users/delete', [
            'nome' =>$obUser->nome,
            'email' =>$obUser->email,
            'status' => self::getStatus($request)
        ]);
  
        // retorna a pagina completa
        return parent::getPanel('Deletar usuário > Sistema', $content, 'users');   
    }

     /**
     * Método responsável por excluir um usuario
     */
    public static function setDeleteUser($request, $id) 
    {
        // Obtem o depoimento do Banco de Dados
        $obUser = EntityUser::getUserById($id);

        // Valida a instancia
        if(!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users');
        }
        
        // Exclui o usuario
        $obUser->setDelete();

        // redireciona o usuario
        $request->getRouter()->redirect('/admin/users/'. $id .'/delete?status=deleted');   
    }

}