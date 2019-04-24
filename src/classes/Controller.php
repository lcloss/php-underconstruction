<?php

namespace App;

class Controller
{
    protected $view;

    /**
     * Instantiate Controller with Dependency Injection of $view
     */
    public function __construct(\Slim\Views\Twig $view)
    {
        $this->view = $view;
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