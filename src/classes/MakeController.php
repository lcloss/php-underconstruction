<?php

namespace App;

class MakeController
{
    protected $controllername = '';
    protected $controller_item = <<<'EOT'
    $container['{% controllername %}Controller'] = function($c) {
        $view = $c->get("view");  // retrieve the 'view' from the container
        return new \App\Http\Controllers\{% controllername %}Controller($view);
    };    


EOT;

    public function __construct($controllername)
    {
        $this->controllername = $controllername;
    }

    public function create()
    {
        // Controller Class
        $make_tpl = file_get_contents('src/commands/templates/make-controller.tpl');
        $make_tpl = str_replace('{% controllername %}', $this->controllername, $make_tpl);
        $make_tpl = str_replace('{% controllername_sc %}', strtolower($this->controllername), $make_tpl);
        file_put_contents('app/Http/Controllers/' . $this->controllername . 'Controller.php', $make_tpl);

        // Controller Item
        $this->controller_item = str_replace('{% controllername %}', $this->controllername, $this->controller_item);
        $controller_file = file_get_contents('src/config/controllers.php') . $this->controller_item;
        file_put_contents('src/config/controllers.php', $controller_file);
    }

    public function drop()
    {
        // Controller Class
        unlink("app/Http/Controllers/" . $this->controllername . "Controller.php");

        // Controller Item
        $controller_file = file_get_contents('src/config/controllers.php');
        $this->controller_item = str_replace('{% controllername %}', $this->controllername, $this->controller_item);
        $controller_file = str_replace($this->controller_item, "", $controller_file);
        file_put_contents('src/config/controllers.php', $controller_file);
    }
}