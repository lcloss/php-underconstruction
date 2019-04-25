<?php
namespace App\Http\Controllers;

use App\Controller;
use App\Mail;
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
        if ( $tb_notifiers->checkEmail($post['email']) ) {
            $this->flash->addMessage('error', 'Email já cadastrado.');
            return $response->withRedirect('/');
        }

        // Sent email to admin
        $to = getenv('ADMIN_EMAIL');
        $subject = "Novo pedido para aviso de abertura de site";
        $message = <<<'EOT'
        Olá {% admin %}!

        Um novo visitante solicitou que seja avisado quando o site estiver no ar.
        O endereço de email para envio é:

        {% email %}

        Por favor, assim que o site estiver no ar, envie um email notificando este visitante.
        Até já!

EOT;
        $message = str_replace('{% admin %}', getenv('ADMIN_NAME'), $message);
        $message = str_replace('{% email %}', $post['email'], $message);

        $success = Mail::send(getenv('FROM_EMAIL'), $to, $subject, $message, getenv('SITE_NAME'));
        if ( !$success ) {
            $error_msg = error_get_last()['message'];

            $this->flash->addMessage('error', error_get_last()['message']);
            return $response->withRedirect('/');
        }

        // Sent email to the visitor
        $to = $post['email'];
        $subject = "Solicitação de aviso de abertura do site " . getenv('SITE_NAME');
        $message = <<<'EOT'
        Olá!

        Você ou alguém visitou o site {% site_name %} ({% site_url %}) e pediu para ser avisado quando o 
        site estiver no ar.

        Não se preocupe! Nós iremos lhe avisar.

        Se por acaso não fez tal solicitação, por favor avise-nos pelo email {% notify_email %},
        ou entre em contato connosco aqui: {% site_url %}/contacto

        Obrigado e até já!
        
EOT;
        $message = str_replace('{% site_name %}', getenv('SITE_NAME'), $message);
        $message = str_replace('{% site_url %}', getenv('SITE_URL'), $message);
        $message = str_replace('{% notify_email %}', getenv('ADMIN_EMAIL'), $message);

        $success = Mail::send(getenv('FROM_EMAIL'), $to, $subject, $message, getenv('SITE_NAME'));
        if ( !$success ) {
            $this->flash->addMessage('error', error_get_last()['message']);
            return $response->withRedirect('/');
        }
        
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

        // Sent email to admin
        $to = getenv('ADMIN_EMAIL');
        $subject = $post['subject'];
        $message = $post['message'];

        $success = Mail::send($post['email'], $to, $post['subject'], $post['message'], $post['name']);
        if ( !$success ) {
            $error_msg = error_get_last()['message'];

            $this->flash->addMessage('error', error_get_last()['message']);
            return $response->withRedirect('/');
        }

        // Add to database
        $tb_contacts = New ContactsModel($this->db, $this->flash);
        $tb_contacts->addContact($post);

        $this->getMessages();

        $this->view->render($response, 'contact-thankyou.tpl.html', $this->data);
        return $response;
    }

}
