<?php

include_once __DIR__ . '/../utils/jwtController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as ResponseMW;
use Firebase\JWT\JWT;

class Keys
{
    public static function ValidateWeapons($request, $handler)
    {
        $queryParams = $request->getQueryParams();
        $nationality = $queryParams['nationality'] ?? null;
        $id = $queryParams['id'] ?? null;
        $response = new ResponseMW();
        if(isset($id)||isset($nationality))
        {
            $response = $handler->handle($request);
        }
        else
        {
            $response->getBody()->write("Valores Incorrectos");
        }
        return $response;
    }
}