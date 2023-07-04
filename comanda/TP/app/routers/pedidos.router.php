<?php
//include_once __DIR__ . '/../interfaces/IApiUsable.php';
include_once __DIR__ . '/../models/orders.php';
include_once __DIR__ . '/../models/table.php';
include_once __DIR__ . '/../utils/jwtController.php';

class routerPedidos
{
    public function NewOrder($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {
            $o = new Orden($body['idTable']);
            $o->CargarOden($body['products']);

            $maxDelay = $o->AltaOrden();

            $response->getBody()->write(json_encode($o));

            $cookies = $request->getCookieParams();
            $token = $cookies['jwt'];
            $tokenVerificado =  ControlerJWT::VerificarToken($token);

            Orden::OrderFollow($o, $tokenVerificado->id, $body['clientName'],$body['url_photo'],$maxDelay);
            Mesa::UpdateStatus($body['idTable'],"taken");//Aca faltaria confirmar si esta tomada la meza o no, se podria cambiar el state a un taken=bool
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function GetAll($request,$response,$arguments)
    {
        $orders = Orden::GetAll();
        var_dump(json_encode($orders));
        $response->getBody()->write(json_encode($orders));

        return $response;
    }

    public function GetBy($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {
            $cookies = $request->getCookieParams();
            $token = $cookies['jwt'];
            $tokenVerificado =  ControlerJWT::VerificarToken($token);

            $orders = Orden::GetBy($tokenVerificado->type);

            $response->getBody()->write(json_encode($orders));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function UpdateBy($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {
            $cookies = $request->getCookieParams();
            $token = $cookies['jwt'];
            $tokenVerificado =  ControlerJWT::VerificarToken($token);

            //var_dump($tokenVerificado->type);
            $orders = Orden::UpdateBy($tokenVerificado->type,$body['delay']);

            $response->getBody()->write(json_encode($orders));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function GetMyOrder($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {
            $orders = Orden::GetMyOrder($body['idTable'],$body['id']);

            $response->getBody()->write(json_encode($orders));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function GetAllMyOrders($request,$response,$arguments)
    {
        $orders = Orden::GetAllMyOrders();

        $response->getBody()->write(json_encode($orders));
        $response = $response->withStatus(200);

        return $response;
    }

    public function ReadyToServe($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {
            $cookies = $request->getCookieParams();
            $token = $cookies['jwt'];
            $tokenVerificado =  ControlerJWT::VerificarToken($token);

            //var_dump($tokenVerificado->type);
            $ready = Orden::ReadyToServe($tokenVerificado->type,$body['id'],$body['idProduct']);
            if($ready == "OK")
            {
                $ready = Orden::IsReady($body['id']);
            }
            $response->getBody()->write(json_encode($ready));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function IsReady($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {

            $ready = Orden::IsReady($body['id']);
            
            $response->getBody()->write(json_encode($ready));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function ListAllReady($request,$response,$arguments)
    {
        try
        {
            $orders = Orden::GetAllReady();

            $response->getBody()->write(json_encode($orders));
            $response = $response->withStatus(200);
        }catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(204);    
        }
        

        return $response;
    }

    public function Serve($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {

            $ready = Orden::IsReady($body['id']);
            
            if($ready->readyToServe == true)
            {
                $ready = Orden::Serve($body['id']);
                $response->getBody()->write(json_encode($ready));
                $response = $response->withStatus(200);
            }
            else
            {
                $response->getBody()->write("No esta listo para servir");
                $response = $response->withStatus(204);
            }

        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function Check($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {

            $ch = Orden::Check($body['id']);//esto podria combinarlo con el serve y hacer una unca funcion y que le mande al orders.php directamente el query

        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function Calificate($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {

            Orden::Calificate($body['id'],$body['tablep'],$body['waiter'],$body['cook'],$body['comments']);
            $response->getBody()->write("Calificado");
            $response = $response->withStatus(200);

        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function GetReviews($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        try
        {
            $comments = array();
            $mu = Orden::GetMostUsed();
            $bc = Orden::GetBestComment();
            $delayed = Orden::Delayed();

            $comments["Most Used Table"] = $mu;
            $comments["Best Commentary"] = $bc;
            $comments["Delayed Orders"] = $delayed;

            $response->getBody()->write(json_encode($comments));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }
    /*
    public function Delayed($request,$response,$arguments)
    {
        //$body = $request->getParsedBody();
        try
        {
            
            $comments = array();
            $mu = Orden::GetMostUsed();
            $bc = Orden::GetBestComment();
            $comments["Most Used Table"] = $mu;
            $comments["Best Commentary"] = $bc;

            $response->getBody()->write(json_encode($comments));
            $response = $response->withStatus(200);
        }
        catch(Exception $e)
        {
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }*/
    /*
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
    }*/
}