<?php 


namespace App\Controller\Admin;

use App\Utils\View;

class Alert 
{
    public static function getError(string $message): string 
    {
        return View::render('admin/alert/alert', [
            'tipo' => 'danger',
            'message' => $message
        ]);
    }

    public static function getSuccess(string $message): string 
    {
        return View::render('admin/alert/alert', [
            'tipo' => 'success',
            'message' => $message
        ]);
    }
}