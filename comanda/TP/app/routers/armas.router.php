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

    public function DeleteBy($request,$response,$arguments)
    {
        $queryParams = $request->getQueryParams();
        $id = $queryParams['id'];
        $selectQuery = "id=".$id;
        $weapons = Arma::DeleteBy($selectQuery);
        $response->getBody()->write($weapons);
        //$response->getBody()->write(json_encode($weapons));

        return $response;
    }

    public function Modify($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        $updateQuery = "UPDATE ".$_ENV['T_WEAPONS']." SET ";
        $primera = true;
        $w=new stdClass;

        if(isset($body['price']))
        {
            $updateQuery .= "price=".$body['price'];
            $primera = false;
        }

        if(isset($body['name']))
        {
            if(!$primera)
            {
                $updateQuery .=",";
            }
            $updateQuery .= "name='".$body['name']."'";
            $primera = false;
        }
        
        if(isset($body['url']))
        {
            
            if(!$primera)
            {
                $updateQuery .=",";
            }
            $updateQuery .= "url_photo='".$body['url']."'";
            $primera = false;
            
            $selectQuery = "id=".$body['id'];
            $weapons = Arma::GetBy($selectQuery);  
            $w = $weapons[0];
        }

        $updateQuery .=" WHERE id=".$body['id'];

        $value = Arma::ModifyBy($updateQuery);

        if($value = "OK" && isset($w->name))
        {
            $destination ='Backup_2023/'.$w->name.".jpg";
            $image = file_get_contents($w->url_photo);
            file_put_contents($destination, $image);

        }
        //$response = new ResponseMW();
        $response->getBody()->write(json_encode($value));

        return $response;
    }

    public function GetCSV($request,$response,$arguments)
    {
        $weapons = Arma::GetAll();
        $fpHandler = fopen('weapons.csv', 'w');
        foreach($weapons as $w)
        {
            $row = array($w->id,$w->price,$w->name,$w->url_photo,$w->nationality);
            fputcsv($fpHandler, $row);
        }
        var_dump(json_encode($weapons));
        $response->getBody()->write(json_encode($weapons));

        return $response;
    }
}