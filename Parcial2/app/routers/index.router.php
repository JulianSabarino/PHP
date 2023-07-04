<?php
/*
include_once __DIR__.'/mesas.router.php';
include_once __DIR__.'/pedidos.router.php';
include_once __DIR__.'/productos.router.php';
include_once __DIR__.'/usuarios.router.php';
include_once __DIR__.'/views.router.php';
include_once __DIR__.'/../models/Producto.php';

include_once __DIR__.'/../middlewares/validarFormato.php';
include_once __DIR__.'/../middlewares/Logger.php';
*/

include_once __DIR__.'/usuarios.router.php';
include_once __DIR__.'/armas.router.php';
include_once __DIR__.'/ventas.router.php';
include_once __DIR__.'/../middlewares/Logger.php';
include_once __DIR__.'/../middlewares/Keys.php';
include_once __DIR__.'/../middlewares/Delete.php';

class indexRouter{
    function __invoke($app) {
    // Aquí se definen las rutas dentro del grupo
    $app->group('[/]', function ($group)//se dirige aca tenga o no la ultima barra
    {
        
    });

    $app->group('/users', function ($group)
    {
        //$group->get();
        $group->post('/login[/]', \routerUsuarios::class . ':Login');
    });

    $app->group('/armas', function ($group){
        $group->post('/insertar[/]',\routerArmas::class . ':NewWeapon')//crea una nueva arma
        ->add(\Logger::validarRoles(['admin'])) //le agrego un middleware de validar roles revisar el codigo de comanda
        ->add(\Logger::class.':ValidateUserJWT'); //valida que el jwt sea de un admin (osea que el usuario logueado sea admin)

        $group->get('/all',\routerArmas::class . ':GetAll');

        $group->get('/search/nationality[/]',\routerArmas::class . ':GetBy')
        ->add(\Keys::class.':ValidateWeapons');

        $group->get('/search[/]',\routerArmas::class . ':GetBy')
        ->add(\Keys::class.':ValidateWeapons')
        ->add(\Logger::class.':ValidateUserJWT');

        //$group->delete('/delete/{id}',\routerArmas::class . ':DeleteBy'); Recordar que a nivel conceptual es mejor esto

        $group->delete('/delete[/]',\routerArmas::class . ':DeleteBy')
        ->add(\Delete::class.':DeleteWeaponsLog')
        ->add(\Keys::class.':ValidateWeapons')
        ->add(\Logger::validarRoles(['admin']))
        ->add(\Logger::class.':ValidateUserJWT');

        $group->put('/modify[/]',\routerArmas::class . ':Modify')
        ->add(\Logger::validarRoles(['admin']))
        ->add(\Logger::class.':ValidateUserJWT');

        $group->get('/csv[/]',routerArmas::class . ':GetCSV');//transmitir archivo como csv, esto es descargar no transferir
     });

     $app->group('/ventas', function($group)
     {
        $group->post('[/]',\routerVentas::class . ':NewSell')
        ->add(\Logger::class.':ValidateUserJWT');

        $group->get('/search/nationality[/]',\routerVentas::class . ':GetBy')
        ->add(\Logger::validarRoles(['admin']))
        ->add(\Logger::class.':ValidateUserJWT');

        $group->get('/search[/]',\routerVentas::class . ':GetBy')
        ->add(\Logger::validarRoles(['admin']))
        ->add(\Logger::class.':ValidateUserJWT');
     });

}
}