<?php
//include_once __DIR__ . '/../interfaces/IApiUsable.php';
include_once __DIR__ . '/../models/table.php';
include_once __DIR__ . '/../utils/jwtController.php';

class routerMesas
{
    public function CloseTable($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        
        $r = Mesa::UpdateStatus($body['idTable'],"free");

        $response->getBody()->write($r);
        $response->withStatus(200);

        return $response;
    }

    public function GetTables($request,$response,$arguments)
    {
        $r = Mesa::LeerMesa();

        $response->getBody()->write(json_encode($r));
        $response->withStatus(200);

        return $response;
    }
}