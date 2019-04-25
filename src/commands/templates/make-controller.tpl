<?php
namespace App\Http\Controllers;

use App\Controller;

class {% controllername %}Controller extends Controller
{
    public function index($request, $response, $args)
    {
        $this->view->render($response, '{% controllername_sc %}/index.tpl.html');
        return $response;
    }

}
