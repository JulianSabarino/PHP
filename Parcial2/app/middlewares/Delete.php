<?php

include_once __DIR__ . '/../utils/jwtController.php';
include_once __DIR__ . '/../models/pdo.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as ResponseMW;
use Firebase\JWT\JWT;

class Delete
{
    public static function DeleteWeaponsLog($request, $handler)
    {
        $queryParams = $request->getQueryParams();
        $id = $queryParams['id'] ?? null;
        $cookies = $request->getCookieParams();
        $token = $cookies['jwt'] ?? null;

        $tokenVerificado =  ControlerJWT::VerificarToken($token);

        $response = new ResponseMW();
        if(isset($id)&&isset($tokenVerificado))
        {
            try{
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_LOGS']." (idUser, idWeapon, action, date) values (:idUser,:idWeapon,:action,:date)");
                
                $consulta->bindValue(':idUser',$tokenVerificado->id);
                $consulta->bindValue(':idWeapon',$id);
                $consulta->bindValue(':action',"deleted");
                $consulta->bindValue(':date',date("Y-m-d H:i:s"));

                $consulta->execute();

                $response = $handler->handle($request);
            }catch(Exception $e)
            {
                $response->getBody()->write($e->getMessage());
            }
        }
        else
        {
            $response->getBody()->write("Valores Incorrectos");
        }
        return $response;
    }
}