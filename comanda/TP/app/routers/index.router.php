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

include_once __DIR__.'/armas.router.php';
*/

include_once __DIR__.'/usuarios.router.php';
include_once __DIR__.'/productos.router.php';
include_once __DIR__.'/pedidos.router.php';
include_once __DIR__.'/mesas.router.php';

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
        //Paso numero uno, logear el usuario
        $group->post('/login[/]', \routerUsuarios::class . ':Login');

        $group->get('/getusers[/]', \routerUsuarios::class . ':GetUsers');
        $group->post('/singup[/]', \routerUsuarios::class . ':SingUp');


        //Paso numero 14, enviar un PDF
        $group->get('/logo[/]', \routerUsuarios::class . ':SendLogo');
        
    });

    $app->group('/products', function ($group)
    {
        $group->get('[/]', \routerProducto::class . ':GetAll');
        $group->post('[/]', \routerProducto::class . ':NewProduct');
        
    });

    $app->group('/tables', function ($group)
    {
        //Paso numero siete, un socio pide una lista de las mesas y sus estados
        $group->get('/list[/]', \routerMesas::class . ':GetTables')
        ->add(\Logger::validarRoles(['admin','socio']))
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero nueve, el mozo cierra la mesa para que otra persona pueda usarla
        $group->post('/close[/]',\routerMesas::class . ':CloseTable')
        ->add(\Logger::validarRoles(['admin','socio']))
        ->add(\Logger::class.':ValidateUserJWT');
        
    });

    $app->group('/orders', function ($group)
    {
        $group->get('[/]', \routerPedidos::class . ':GetAll');
        
        //Paso numero dos, crear pedido
        $group->post('[/]', \routerPedidos::class . ':NewOrder')
        ->add(\Logger::validarRoles(['mozo']))
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero tres, revisar los pedidos de cada sector
        $group->get('/sector[/]', \routerPedidos::class . ':GetBy')
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero cuatro, cambiar los productos a Ready to Serve. Si todos los productos estan ready, cambia el pedido a ready
        $group->post('/readytoserv[/]',\routerPedidos::class . ':ReadyToServe')
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero 5, el usuario se fija el estado
        $group->get('/myOrder[/]', \routerPedidos::class . ':GetMyOrder');
        
        //Paso numero 5.5, algun socio se fija la lista de pedidos
        $group->get('/stateall[/]', \routerPedidos::class . ':GetAllMyOrders')
        ->add(\Logger::validarRoles(['admin','socio']))
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero 6, el mozo sirve
        $group->post('/serve[/]',\routerPedidos::class . ':Serve')
        ->add(\Logger::validarRoles(['mozo']))
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero 8, el mozo hace un checkout
        $group->post('/check[/]',\routerPedidos::class . ':Check') // tengo que cambiar la DB porque actualiza solo el estado del finish
        ->add(\Logger::validarRoles(['mozo']))
        ->add(\Logger::class.':ValidateUserJWT');

        //Paso numero 10, el usuario hace una review de que tan rico estaba
        $group->post('/calificate[/]',\routerPedidos::class . ':Calificate');

        //Paso numero 11-12-13, obtengo resumen de la mesa mas usada, el comentario con mayor puntuacion y una lista de los que no se sirvieron a tiempo
        $group->get('/calificate[/]',\routerPedidos::class . ':GetReviews')
        ->add(\Logger::validarRoles(['admin','socio']))
        ->add(\Logger::class.':ValidateUserJWT');


        $group->post('/sector[/]', \routerPedidos::class . ':UpdateBy');
        
        $group->get('/readytoserv[/]',\routerPedidos::class . ':IsReady');

        $group->get('/serve[/]',\routerPedidos::class . ':ListAllReady');
        


        

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