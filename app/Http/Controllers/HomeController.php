<?php
namespace App\Http\Controllers;

use App\Controller;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        $data = [
            'title' => getenv('TITLE', 'Underconstruction'),
            'description' => getenv('DESCRIPTION', 'This website is underconstruction. Please, be patience.')
        ];
        $this->view->render($response, 'index.tpl.html', $data);
        return $response;
    }

    public function sentemail($request, $response, $args)
    {
        $this->view->render($response, 'email-thankyou.tpl.html');
        return $response;
    }

    public function contact($request, $response, $args)
    {
        $this->view->render($response, 'contact.tpl.html');
        return $response;
    }

    public function sentcontact($request, $response, $args)
    {
        $this->view->render($response, 'contact-thankyou.tpl.html');
        return $response;
    }

}
