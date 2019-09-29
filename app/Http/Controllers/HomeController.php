<?php
namespace App\Http\Controllers;

use App\Controller;
use App\Mail;
use App\Models\NotifierListModel;
use App\Models\ContactsModel;
use App\Models\SpammersModel;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        $this->setCsrf($request);
        $this->getMessages();

        $this->data['google_recaptcha'] = getenv('GOOGLE_RECAPTCHA');

        $this->view->render($response, 'index.tpl.html', $this->data);
        return $response;
    }

    public function sentemail($request, $response, $args)
    {
        $post = $request->getParsedBody();

        // Validations
        $tb_spammers = New SpammersModel($this->db, $this->flash);

        // Anti-spam!!!
        if ( !empty($post['email'])) {
            if ( !$tb_spammers->checkSpammer() ) {
                $tb_spammers->addSpammer();
            }

            return $response->withRedirect('/');
        }

        // Check ip address
        if ( $tb_spammers->checkSpammer() ) {
            return $response->withRedirect('/');
        }

        if ( empty($post['name']) ) {
            $this->flash->addMessage('error', 'Nome não pode estar vazio.');
            return $response->withRedirect('/');
        }

        if ( empty($post['e_mail']) ) {
            $this->flash->addMessage('error', 'Email não pode estar vazio.');
            return $response->withRedirect('/');
        }

        if ( !filter_var($post['e_mail'], FILTER_VALIDATE_EMAIL) ) {
            $this->flash->addMessage('error', 'Email não tem formato válido.');
            return $response->withRedirect('/');
        }

        $tb_notifiers = New NotifierListModel($this->db, $this->flash);
        if ( $tb_notifiers->checkEmail($post['e_mail']) ) {
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
        
        <a href="mailto:{% email %}">{% email %}</a>
        
        Por favor, assim que o site estiver no ar, envie um email notificando este visitante.
        Até já!
        
EOT;
        $message = str_replace('{% admin %}', getenv('ADMIN_NAME'), $message);
        $message = str_replace('{% email %}', $post['e_mail'], $message);

        $success = Mail::send(getenv('FROM_EMAIL'), $to, $subject, nl2br($message), getenv('SITE_NAME'));
        if ( !$success ) {
            $error_msg = error_get_last()['message'];

            $this->flash->addMessage('error', error_get_last()['message']);
            return $response->withRedirect('/');
        }

        // Sent email to the visitor
        $to = $post['e_mail'];
        $subject = "Solicitação de aviso de abertura do site " . getenv('SITE_NAME');
        $message = <<<'EOT'
        Olá!
        
        Você ou alguém visitou o site <a href="{% site_url %}">{% site_name %}</a> (<a href="{% site_url %}">{% site_url %}</a>) e pediu para ser avisado quando o 
        site estiver no ar.
        
        Não se preocupe! Nós iremos lhe avisar.
        
        Se por acaso não fez tal solicitação, por favor avise-nos pelo email <a href="mailto:{% notify_email %}">{% notify_email %}</a>,
        ou entre em contato connosco aqui: <a href="{% site_url %}/contacto">{% site_url %}/contacto</a>
        
        Obrigado e até já!
        
EOT;
        $message = str_replace('{% site_name %}', getenv('SITE_NAME'), $message);
        $message = str_replace('{% site_url %}', getenv('SITE_URL'), $message);
        $message = str_replace('{% notify_email %}', getenv('ADMIN_EMAIL'), $message);

        $success = Mail::send(getenv('FROM_EMAIL'), $to, $subject, nl2br($message), getenv('SITE_NAME'));
        if ( !$success ) {
            $this->flash->addMessage('error', error_get_last()['message']);
            return $response->withRedirect('/');
        }
        
        $tb_notifiers->addNotifier($post);

        $this->getMessages();

        $this->view->render($response, 'email-thankyou.tpl.html', $this->data);
        return $response;
    }

    public function contact($request, $response, $args)
    {
        $this->setCsrf($request);
        $this->getMessages();

        $this->data['google_recaptcha'] = getenv('GOOGLE_RECAPTCHA');

        $this->view->render($response, 'contact.tpl.html', $this->data);
        return $response;
    }

    public function sentcontact($request, $response, $args)
    {
        $post = $request->getParsedBody();
        $is_error = False;

        // Validations
        $tb_spammers = New SpammersModel($this->db, $this->flash);

        // Anti-spam!!!
        if ( !empty($post['email'])) {
            if ( !$tb_spammers->checkSpammer() ) {
                $tb_spammers->addSpammer();
            }

            return $response->withRedirect('/');
        }

        // Check ip address
        if ( $tb_spammers->checkSpammer() ) {
            return $response->withRedirect('/');
        }

        if ( empty($post['name']) ) {
            $this->flash->addMessage('error', 'Nome não pode estar vazio.');
            $is_error = True;
        }

        if ( empty($post['e_mail']) ) {
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

        if ( !filter_var($post['e_mail'], FILTER_VALIDATE_EMAIL) ) {
            $this->flash->addMessage('error', 'Email não tem formato válido.');
            return $response->withRedirect('/contacto');
        }

        // Sent email to admin
        $to = getenv('ADMIN_EMAIL');
        $subject = $post['subject'];
        $message = $post['message'];

        $success = Mail::send($post['e_mail'], $to, $subject, nl2br($message), $post['name']);
        if ( !$success ) {
            $this->flash->addMessage('error', error_get_last()['message']);
            return $response->withRedirect('/contacto');
        }

        // Add to database
        $tb_contacts = New ContactsModel($this->db, $this->flash);
        $tb_contacts->addContact($post);

        $this->getMessages();

        $this->view->render($response, 'contact-thankyou.tpl.html', $this->data);
        return $response;
    }

}
