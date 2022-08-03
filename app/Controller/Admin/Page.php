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
        'link' => URL .'/testimonies'
      ],
      'users' => [
        'label' => 'Usuarios',
        'link' => URL .'/user'
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
 
}