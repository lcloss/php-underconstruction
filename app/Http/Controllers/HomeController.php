<?php
namespace App\Http\Controllers;

use App\Controller;
use App\Models\NotifierListModel;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        // CSRF token name and value
        $this->data['csrf_nameKey'] = $this->csrf->getTokenNameKey();
        $this->data['csrf_valueKey'] = $this->csrf->getTokenValueKey();
        $this->data['csrf_nameData'] = $request->getAttribute($this->data['csrf_nameKey']);
        $this->data['csrf_valueData'] = $request->getAttribute($this->data['csrf_valueKey']);

        $this->getMessages();

        $this->view->render($response, 'index.tpl.html', $this->data);
        return $response;
    }

    public function sentemail($request, $response, $args)
    {
        $post = $request->getParsedBody();
        if ( empty($post['email']) ) {
            $this->flash->addMessage('error', 'Email não pode estar vazio');
            return $response->withRedirect('/');
        }

        $tb_notifiers = New NotifierListModel($this->db, $this->flash);
        $tb_notifiers->addNotifier($post['email']);

        $this->getMessages();

        $this->view->render($response, 'email-thankyou.tpl.html', $this->data);
        return $response;
    }

    public function contact($request, $response, $args)
    {
        $this->view->render($response, 'contact.tpl.html', $this->data);
        return $response;
    }

    public function sentcontact($request, $response, $args)
    {
        $this->view->render($response, 'contact-thankyou.tpl.html', $this->data);
        return $response;
    }

}
