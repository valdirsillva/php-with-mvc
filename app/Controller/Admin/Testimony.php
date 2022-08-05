<?php


namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;


class Testimony extends Page 
{
       // Obtem os dados dos itens a ser renderizados
       private static function getTestimonyItems($request, &$pagination) 
       {
           $itens = '';
   
           // quantidade total registros
           $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
           $paginaAtual = $request->getQueryParams();
           $paginaAtual = $queryParams['page'] ?? 1;
   
           // instancia paginacao
           $pagination = new Pagination($quantidadeTotal, $paginaAtual, 2);
   
           $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());
   
           // renderiza o item
           while($obTestimony = $results->fetchObject(EntityTestimony::class)) {
               $itens .= View::render('admin/modules/testimonies/item', [
                   'id' => $obTestimony->id,
                   'nome' => $obTestimony->nome,
                   'mensagem' => $obTestimony->mensagem,
                   'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
               ]);
           }
   
           return $itens;
       }

       
    /**
     * Método resppnsável por renderizar a view de listagem de depoimentos
     */
    public static function getTestimonies($request) 
    {
        $content = View::render('admin/modules/testimonies/index', [
            'itens' => self::getTestimonyItems($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination),
            'status' => self::getStatus($request)
        ]);

        // retorna a pagina completa
        return parent::getPanel('Depoimentos > Sistema', $content, 'testimonies');
    }

    public static function getNewTestimony($request) 
    {
        // Conteúdo do formulário
        $content = View::render('admin/modules/testimonies/form', [
          'title' => 'Cadastrar Depoimento',
          'nome' => '',
          'mensagem' => '',
          'status' => self::getStatus($request)
        ]);

        // retorna a pagina completa
        return parent::getPanel('Cadastrar Depoimento > Sistema', $content, 'testimonies');
    }

    private static function getStatus($request) 
    {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams['status'])) return '';
        
        // Mensagens de status
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluído com sucesso!');
                break;    
        }

    }

    // Cadastra depoimento no bancpo de dados
    public static function setNewTestimony($request) 
    {
        $postVars = $request->getPostVars();

        // Instancia de Depoimento
        $newTestimony = new EntityTestimony;

        $newTestimony->nome = $postVars['nome'] ?? '';
        $newTestimony->mensagem = $postVars['mensagem'] ?? '';
        $newTestimony->add();

        // redireciona o usuario
        $request->getRouter()->redirect('/admin/testimonies/'.$newTestimony->id .'/edit?status=created');
    }

 

    public static function getEditTestimony($request, $id) 
    {
        // Obtem o depoimento do Banco de Dados
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // Valida a instancia
        if(!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        // Conteúdo do formulário
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Editar Depoimento',
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'status' => self::getStatus($request)
        ]);
  
        // retorna a pagina completa
        return parent::getPanel('Editar Depoimento > Sistema', $content, 'testimonies');   
    }

    
    public static function getDeleteTestimony($request, $id) 
    {
        // Obtem o depoimento do Banco de Dados
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // Valida a instancia
        if(!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        // Conteúdo do formulário
        $content = View::render('admin/modules/testimonies/delete', [
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem
        ]);
  
        // retorna a pagina completa
        return parent::getPanel('Deletar Depoimento > Sistema', $content, 'testimonies');   
    }

     /**
     * Método responsável por excluir um depoimento
     */
    public static function setDeleteTestimony($request, $id) 
    {
        // Obtem o depoimento do Banco de Dados
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // Valida a instancia
        if(!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        // Exclui o depoimento 
        $obTestimony->setDelete();

        // redireciona o usuario
        $request->getRouter()->redirect('/admin/testimonies/'. $id .'/delete?status=deleted');   
    }

    /**
     * Método responsável por gravar uma atualização de um depoimento
     */
    public static function setEditTestimony($request, $id) 
    {
        // Obtem o depoimento do Banco de Dados
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // Valida a instancia
        if(!$obTestimony instanceof EntityTestimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }
        
        // Pega variaveis do postVars
        $postVars = $request->getPostVars();

        // Atualiza a instancia
        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->setUpdate();

        // redireciona o usuario
        $request->getRouter()->redirect('/admin/testimonies/'. $id .'/edit?status=updated');   
    }
}