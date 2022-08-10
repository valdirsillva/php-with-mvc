<?php 


namespace App\Controller\Api;

use App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

 
class Testimony extends Api 
{

    // Obtem os dados dos itens a ser renderizados
    private static function getTestimonyItems($request, &$pagination) 
    {
        $itens = [];

        // quantidade total registros
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // instancia paginacao
        $pagination = new Pagination($quantidadeTotal, $paginaAtual, 2);

        $results = EntityTestimony::getTestimonies(null, 'id DESC', $pagination->getLimit());

        // renderiza o item
        while($obTestimony = $results->fetchObject(EntityTestimony::class)) {
            $itens[] = [
                'id' => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => $obTestimony->data
            ];
        }

        return $itens;
    }


    public static function getTestimonies($request) 
    {
        return [
            'depoimentos' => self::getTestimonyItems($request, $pagination),
            'paginacao' => parent::getPagination($request, $pagination)
        ];
    }

    public static function getTestimony($request, $id) 
    {
        // Valida o id do depoimento 
        if (!is_numeric($id)) {
            throw new \Exception("O id não é válido", 400); 
        }

        // busca depoimento
        $obTestimony = EntityTestimony::getTestimonyById($id);

        // Valida se o depoimento existe
        if(!$obTestimony instanceof EntityTestimony) {
            throw new \Exception("O depoimento" .$id. " não foi encontrado", 404);
        }

        return [
            'id' => $obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];

    }
}