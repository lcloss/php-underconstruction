<?php

namespace App;

use Slim\Http\Request;
use Slim\Http\Response;

class Middleware
{
    /**
     * @param \Psr\Http\Message\ServerRequestInterface
     * @param \Psr\Http\Message\ResponseInterface
     * @param callable
     */
    public function __invoke($request, $response, $next): \Slim\Http\Response
    {
        // $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        // $response->getBody()->write('AFTER');

        return $response;
    }
}