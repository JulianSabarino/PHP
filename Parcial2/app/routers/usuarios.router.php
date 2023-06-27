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
    /*
    public function CargarUno($req, $res, $args)
    {

        $body = $req->getParsedBody();
        $new = new Usuario();
        $new->email = $body['email'];
        $new->password = $body['password'];
        $new->nombre = $body['nombre'];
        $new->fecha_nacimiento = new DateTime($body['fecha_nacimiento']);
        $new->sector = $body['sector'];

        $newId = $new->crearUsuario();
        if (isset($newId)) {
            $res->getBody()->write('nuevo usuario creado, id: ' . $newId);
            $res = $res->withStatus(200);
        } else {
            $res->getBody()->write('no se pudo crear el usuario');
            $res = $res->withStatus(404);
        }
        return $res;
    }

    public function TraerUno($req, $res, $args)
    {
        $body = $req->getParsedBody();

        $usuario = Usuario::validarUsuario($body['email'], $body['password']);
        //var_dump($usuario);
        if (isset($usuario) && $usuario->estado) {
            $token = [
                'id' => $usuario->id,
                'sector' => $usuario->sector,
                'email' => $usuario->email
            ];
            $jwt = ControlerJWT::CrearToken($token);
            $res = $res->withHeader('Set-Cookie', 'jwt=' . $jwt . '; path=/; HttpOnly; Secure; SameSite=Strict');
            $res->getBody()->write("Bienvenido " . $usuario->nombre);
            $res = $res->withStatus(200);
        } else {
            $res = $res->withStatus(400);
            $res->getBody()->write('usuario o password incorrectos');
        }
        return $res;
    }


    public function TraerTodos($req, $res, $args)
    {
        $usuarios = Usuario::obtenerTodos();

        $res->getBody()->write(json_encode($usuarios));
        return $res;
    }

    public function ModificarUno($req, $res, $args)
    {
        $body = $req->getParsedBody();
        $new = new Usuario();

        $new->email = $body['email'];
        $new->password = $body['password'];
        $new->nombre = $body['nombre'];
        $new->fecha_nacimiento = new DateTime($body['fecha_nacimiento']);
        $new->sector = $body['sector'];
        $new->estado = $body['estado'];

        $idProduct = Usuario::modificarUsuario($new);
        $res->getBody()->write('usuario modificado con exito id: ' . $idProduct);
        return $res;
    }

    public function BorrarUno($req, $res, $args)
    {
        $body = $req->getParsedBody();
        $eliminado = Usuario::borrarUsuario($body['email']);
        $res->getBody()->write($eliminado ? 'usuario eliminado' : 'no se pudo eliminar');
        return $res;
    }
    public function logout($req, $res, $args)
    {
        try {
            $res = $res->withHeader('Set-Cookie', 'jwt=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; HttpOnly; Secure; SameSite=Strict');
            $res->getBody()->write('Usuario deslogado');
            $res = $res->withStatus(200);
        } catch (Exception $e) {
            $res->getBody()->write('Error: ' . $e->getMessage());
            $res = $res->withStatus(500);
        }
        return $res;
    }*/
}
