<?php
//include_once __DIR__ . '/../interfaces/IApiUsable.php';
include_once __DIR__ . '/../models/users.php';
include_once __DIR__ . '/../utils/jwtController.php';

class routerUsuarios
{
    public function Login($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        $u = new stdClass;
        try
        {
            $u = Usuario::UsersByKeyValue($body['mail'],$body['pass']);
            $token = [
                'id' => $u->id,
                'mail' => $u->mail,
                'type' => $u->type
            ];
            $jwt = ControlerJWT::CrearToken($token);
            $response = $response->withHeader('Set-Cookie', 'jwt=' . $jwt . '; path=/; HttpOnly; Secure; SameSite=Strict');
            $response->getBody()->write("Bienvenido " . $u->mail);
            $response = $response->withStatus(200);
            var_dump($u->mail);
        }
        catch(Exception $e)
        {
            $u = null;
            $response->getBody()->write($e->getMessage());
            $response = $response->withStatus(400);
        }

        return $response;
    }

    public function GetUsers($request,$response,$arguments)
    {
        $users = Usuario::LeerUsuarios();
        if(isset($users))
        {
            $response->getBody()->write(json_encode($users));
        }
        else
        {
            $response->getBody()->write("Fallo");
        }

        return $response;
    }

    public function SingUp($request,$response,$arguments)
    {
        $body = $request->getParsedBody();
        $u = new stdClass;
        try
        {

            $u = new Usuario($body['name'],$body['surname'],$body['mail'],$body['pass'],$body['type']);

            $response->getBody()->write("Creado usuario " . $u->mail);
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

    public function SendLogo($request,$response,$arguments)
    {

        $file = 'yumi.pdf';

        $response = $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="yumi.pdf"');

        $fileStream = fopen($file, 'rb');
        $stream = new \Slim\Psr7\Stream($fileStream);

        return $response->withBody($stream);
    }
    
}
