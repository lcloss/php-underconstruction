<?php
namespace App\Http\Controllers;

use App\Controller;

class AdminController extends Controller 
{
    public function index($request, $response, $args)
    {
        $this->view->render($response, 'admin/index.tpl.html');
        return $response;
    }
}