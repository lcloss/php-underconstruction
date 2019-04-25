<?php
namespace App\Http\Controllers;

use App\Controller;
use App\Models\NotifierListModel;
use App\Models\ContactsModel;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        $this->setCsrf($request);
        $this->getMessages();

        $this->view->render($response, 'index.tpl.html', $this->data);
        return $response;
    }

    public function sentemail($request, $response, $args)
    {
        $post = $request->getParsedBody();

        // Validations
        if ( empty($post['email']) ) {
            $this->flash->addMessage('error', 'Email não pode estar vazio.');
            return $response->withRedirect('/');
        }

        if ( !filter_var($post['email'], FILTER_VALIDATE_EMAIL) ) {
            $this->flash->addMessage('error', 'Email não tem formato válido.');
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
        $this->setCsrf($request);
        $this->getMessages();

        $this->view->render($response, 'contact.tpl.html', $this->data);
        return $response;
    }

    public function sentcontact($request, $response, $args)
    {
        $post = $request->getParsedBody();
        $is_error = False;

        // Validations
        if ( empty($post['name']) ) {
            $this->flash->addMessage('error', 'Nome não pode estar vazio.');
            $is_error = True;
        }

        if ( empty($post['email']) ) {
            $this->flash->addMessage('error', 'Email não pode estar vazio.');
            $is_error = True;
        }

        if ( empty($post['subject']) ) {
            $this->flash->addMessage('error', 'Assunto não pode estar vazio.');
            $is_error = True;
        }

        if ( empty($post['message']) ) {
            $this->flash->addMessage('error', 'Texto da mensagem não pode estar vazio.');
            $is_error = True;
        }

        if ( $is_error == True ) {
            return $response->withRedirect('/contacto');
        }

        if ( !filter_var($post['email'], FILTER_VALIDATE_EMAIL) ) {
            $this->flash->addMessage('error', 'Email não tem formato válido.');
            return $response->withRedirect('/contacto');
        }

        $tb_contacts = New ContactsModel($this->db, $this->flash);
        $tb_contacts->addContact($post);

        $this->getMessages();

        $this->view->render($response, 'contact-thankyou.tpl.html', $this->data);
        return $response;
    }

}
