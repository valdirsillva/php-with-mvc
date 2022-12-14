<?php


namespace App\Controller\Admin;

use App\Utils\View;

class Page 
{
    // Módulos disponiveis no painel
    private static $modules = [
      'home' => [
        'label' => 'Home',
        'link' =>  URL .'/admin'
      ],
      'testimonies' => [
        'label' => 'Depoimentos',
        'link' => URL .'/admin/testimonies'
      ],
      'users' => [
        'label' => 'Usuarios',
        'link' => URL .'/admin/users'
      ]
    ];


     /**
     * Método responsável por renderizar a view do painel
     */
    public static function getPanel($title, $content, $currentModule)
    {
        // Renderiza a view do painel
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        // Retorna a pagina renderizada
        return self::getPage($title, $contentPanel);
    }

    /**
     * Método responsável por retornar o conteúdo da (view) da estrutura genérica de pagina do painel
     */
    public static function getPage($title, $content): string 
    {
        return View::render('admin/page', [
            'title' => $title,
            'content' => $content
        ]);
    }

    private static function getMenu($currentModule)
    {
        // links do menu 
        $links = '';

        // Itera os modulos
        foreach(self::$modules as $hash=>$module) {
            $links .= View::render('admin/menu/link', [
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $hash == $currentModule ? 'text-danger' : ''
            ]);
        }

        // retorna a renderização do menu
        return View::render('admin/menu/box', [
            'links' => $links
        ]);
    }
    
    
    public static function getPagination($request, $pagination) 
    {
        $pages = $pagination->getPages();
    
        if (count($pages) <= 1) return '';
        
        $links = '';

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->getQueryParams();

        // renderiza os links
        foreach ($pages as $page) {
            # altera pagina
            $queryParams['page'] = $page['page'];

            // link da pagina
            $link = $url .'?'. http_build_query($queryParams);

            // VIEW
            $links .=  View::render('admin/paginations/link', [
                "page" => $page['page'],
                "link" => $link,
                "active" => $page['current'] ? 'active' : ''
            ]);
        }

        return View::render('admin/paginations/box', [
            "links" => $links,
        ]);


    }

 
}