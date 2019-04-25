<?php

namespace App;

class Controller
{
    protected $c;
    protected $view;
    protected $data = array();
    protected $csrf;
    protected $flash;
    protected $db;

    /**
     * Instantiate Controller with Dependency Injection of $view
     */
    public function __construct(\Slim\Container $c)
    {
        $this->data = [
            'title' => getenv('TITLE', 'Underconstruction'),
            'description' => getenv('DESCRIPTION', 'This website is underconstruction. Please, be patience.')
        ];

        // Load dependencies
        $this->c = $c;
        $this->view = $c->get("view");
        $this->csrf = $c->get("csrf");
        $this->flash = $c->get("flash");
        $this->db = $c->get("db");
    }

    public function setCsrf($request)
    {
        // CSRF token name and value
        $this->data['csrf_nameKey'] = $this->csrf->getTokenNameKey();
        $this->data['csrf_valueKey'] = $this->csrf->getTokenValueKey();
        $this->data['csrf_nameData'] = $request->getAttribute($this->data['csrf_nameKey']);
        $this->data['csrf_valueData'] = $request->getAttribute($this->data['csrf_valueKey']);
    }

    public function getMessages()
    {
        // Check for errors
        // Get flash messages from previous request
        $messages = $this->flash->getMessages();

        $errors = array();
        $successes = array();
        foreach ($messages as $key => $message) {
            switch($key) {
                case 'error':
                    $errors[] = ['message' => $message[0]];
                    break;

                case 'success':
                    $successes[] = ['message' => $message[0]];
                    break;
            }
        }
        $this->data['errors'] = $errors;
        $this->data['successes'] = $successes;
    }

    /*
    protected $request, $response, $args;

    public function home($request, $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }

    public function config($key)
    {
        return $this->config->get($key);
    }

    public function lang($key)
    {
        return $this->config("lang." . $key);
    }

    public function param($name)
    {
        return $this->request->getParam($name);
    }

    public function flash($type, $message)
    {
        return $this->flash->addMessage($type, $message);
    }

    public function flashNow($type, $message)
    {
        return $this->flash->addMessageNow($type, $message);
    }

    public function render($name, array $args = [])
    {
        return $this->container->view->render($this->response, $name . '.twig', $args);
    }

    public function redirect($path = null, $args = [], $params = [])
    {
        $path = $path != null ? $path : 'home';

        return $this->response->withRedirect($this->router()->pathFor($path, $args, $params));
    }

    public function notFound()
    {
        throw new NotFoundException($this->request, $this->response);
    }

    public function validator()
    {
        return new Validator($this->container);
    }

    public function auth()
    {
        return $this->auth;
    }

    public function user()
    {
        return $this->auth()->user();
    }

    protected function router()
    {
        return $this->container['router'];
    }
    */
}