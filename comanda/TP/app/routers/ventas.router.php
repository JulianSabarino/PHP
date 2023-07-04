<?php
//include_once __DIR__ . '/../interfaces/IApiUsable.php';
include_once __DIR__ . '/../models/sells.php';
include_once __DIR__ . '/../utils/jwtController.php';
include_once __DIR__ . '/../models/weapons.php';

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

    public function GetBy($request,$response,$arguments)
    {
        $queryParams = $request->getQueryParams();
        $n = $queryParams['nationality'] ?? null;
        $selectQuery="";
        if(isset($n))
        {
            $selectQuery = "nationality='".$n."'";
        }
        else
        {
            $id = $queryParams['name'];
            $selectQuery = "name=".$id;
        }
        $weapons = Arma::GetBy($selectQuery);
        //$response = new ResponseMW();

        $ventas = array();

        if(isset($n))
        {
            foreach($weapons as $w)
            {
                $selectQuery = "idWeapon='".$w->id."'";
                $v = Ventas::GetBy($selectQuery);
                foreach($v as $vbyId)
                {
                    array_push($ventas,$vbyId); 
                }    
            }
            //$ventas = Ventas::FilterByDates($ventas); Descomentar esta linea si se requiere filtrar por fechas
            
        }
        else
        {
            $selectQuery = "idWeapon='".$weapons[0]->id."'";
            $ventas = Ventas::GetBy($selectQuery);
        }



        $response->getBody()->write(json_encode($ventas));

        return $response;
    }
}