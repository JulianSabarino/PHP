<?php

include_once __DIR__ . '/../utils/jwtController.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as ResponseMW;
use Firebase\JWT\JWT;

class Logger
{
    public static function ValidateUserJWT($request, $handler)
    {

        $cookies = $request->getCookieParams();
        $token = $cookies['jwt'] ?? null; //$token = isset($cookies['jwt']) ? $cookies['jwt'] : null;
        $error = null;
        $statusError = 500;
        $response = new ResponseMW();

        if (!$token) {
            $error = 'no hay un jwt guardado';
            $statusError = 401;
        }
        else
        {
            try {
                
                $tokenVerificado =  ControlerJWT::VerificarToken($token);
                $request = $request->withAttribute('jwt', $tokenVerificado);
                $response = $handler->handle($request);//esto es lo que le da el ok hacia arriba
            } catch (Exception $e) {
    
                $error = $e->getMessage();
                $statusError = 401;
            }
        }
        if(isset($error))
        {
            $response->getBody()->write($error);
        }
        
        return $response;
    }

    public static function validarRoles($rolesPermitidos)
    {
        return function ($request, $handler) use ($rolesPermitidos) {
            $dataJwt = $request->getAttribute('jwt');
            $error = null;
            $statusError = 500;
            $response = new ResponseMW();

            if (!$dataJwt) {
                $error = 'no hay un jwt guardado';
                $statusError = 401;
            }
            else
            {
                if (!in_array($dataJwt->type, $rolesPermitidos)) {
                    $error = 'no cuenta con permisos para ingresar';
                    $statusError = 404;
                }
                else
                {
                    $response = $handler->handle($request);
                }
            }

            if (isset($error)) {
                $response->getBody()->write($error);
                $response->withStatus($statusError);
            }
            return $response;
        };
    }

}
