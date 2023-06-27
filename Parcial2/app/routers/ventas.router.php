<?php
//include_once __DIR__ . '/../interfaces/IApiUsable.php';
include_once __DIR__ . '/../models/sells.php';
include_once __DIR__ . '/../utils/jwtController.php';

class routerVentas
{
    public function NewSell($request,$response,$arguments)
    {
        $body = $request->getParsedBody();

        $cookies = $request->getCookieParams();
        $token = $cookies['jwt'] ?? null;
        $dataJwt = ControlerJWT::VerificarToken($token);

        $s = new stdClass;
        try
        {   
            $s = new Ventas($body['idWeapon'],$dataJwt->id,$body['qWeapon'],date("Y-m-d H:i:s"),$body['url']);

            $response->getBody()->write("Venta " . $dataJwt->mail);
            $email = $dataJwt->mail;
            $username = substr($email, 0, strpos($email, '@'));

            $destination ='FotosArma2023/'.$username.date("Y-m-d").".jpg";
            $image = file_get_contents($body['url']);

            file_put_contents($destination, $image);
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $u = null;
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }
        return $response;
    }
}