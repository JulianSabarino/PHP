<?php
//include_once __DIR__ . '/../interfaces/IApiUsable.php';
include_once __DIR__ . '/../models/weapons.php';
include_once __DIR__ . '/../utils/jwtController.php';

class routerArmas
{
    public function NewWeapon($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        $w = new stdClass;
        try
        {

            $w = new Arma($body['price'],$body['name'],$body['url'],$body['nationality']);

            $response->getBody()->write("Creada Arma " . $w->name);
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

    public function GetAll($request,$response,$arguments)
    {
        $weapons = Arma::GetAll();
        var_dump(json_encode($weapons));
        $response->getBody()->write(json_encode($weapons));

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
            $id = $queryParams['id'];
            $selectQuery = "id=".$id;
        }
        $weapons = Arma::GetBy($selectQuery);
        //$response = new ResponseMW();
        $response->getBody()->write(json_encode($weapons));

        return $response;
    }
}