<?php
/*

*/
include "PizzaConsultar.php";
include "AltaVenta.php";
include "ConsultasVentas.php";

    class Pizza
    {
        private static $ultimo_id = 0;
        public $id;
        public $sabor;
        public $tipo;
        public $precio;
        public $cantidad;
        public $fecha;
       
       public function __construct($sabor,$tipo,$precio = 0,$cantidad = 0)    
       {
            self::$ultimo_id++;
            $this->id = self::$ultimo_id;
            $this->sabor = $sabor;
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->cantidad = $cantidad;
            $this->fecha=new DateTime($now);
       }

       public static function CargarPizzas()   
       {
            $pizzaJson = file_get_contents("pizzas.json");
            $pizzaArray = json_decode($pizzaJson);
            
            if(!is_null($pizzaArray))
            {
                foreach($pizzaArray as $pizza)
                {
                    if(Pizza::$ultimo_id < $pizza->id)
                    {
                        Pizza::$ultimo_id = $pizza->id;
                    }
                }
            }
            else
            {
                //$pizzaArray = [{}];
            }

            return $pizzaArray;
       }

       public static function CargarJSON($pizzas)
       {
            $pizzasJason = json_encode($pizzas);
            file_put_contents("pizzas.json",$pizzasJason);   
       }

       public static function GetBySabor($pizzas,$sabor)
       {
            $lista=null;
            if(!is_null($pizzas))
            {
                foreach($pizzas as $p)
                {
                    if($p->sabor == $sabor)
                    {
                        $lista = $p;
                        break;
                    }
                }
            }
            return $lista;
       }

       public static function ActualizarPizza($p, $cantidad, $precio = 0)
       {
            $p->cantidad = $p->cantidad + $cantidad;
            if($precio > 0)
            {
                $p->precio = $precio;
            }
       }

       public static function GetPizza($pizzas,$sabor,$tipo)
       {
            $lista=null;
            if(!is_null($pizzas))
            {
                foreach($pizzas as $p)
                {
                    if($p->sabor == $sabor && $p->tipo == $tipo)
                    {
                        $lista = $p;
                        break;
                    }
                }
            }
            return $lista;
       }
       
    }


    $pizzas = Pizza::CargarPizzas();
    $tickets = Ticket::CargarTickets();

    if(is_null($pizzas))
    {
        $pizzas=[];
    }

    if(is_null($tickets))
    {
        $tickets=[];
    }

    switch($_SERVER["REQUEST_METHOD"])
    {
        case 'GET':
            if(isset($_GET['sabor']) && isset($_GET['tipo']))
            {                
                $p = Pizza::GetPizza($pizzas,$_GET['sabor'],$_GET['tipo']);
                if(is_null($p))
                {
                    if(isset($_GET['precio']) && isset($_GET['cantidad']))
                    {
                        
                        $p = new Pizza($_GET['sabor'],$_GET['tipo'],$_GET['precio'],$_GET['cantidad']);
                    }
                    else
                    {
                        if(isset($_GET['precio']))
                        {
                            $p = new Pizza($_GET['sabor'],$_GET['tipo'],$_GET['precio']);
                        }
                        else
                        {
                            $p = new Pizza($_GET['sabor'],$_GET['tipo']);
                        }
                    }
                    array_push($pizzas,$p);
                    echo json_encode($pizzas);
                }
                else
                {
                    if(isset($_GET['precio']) && isset($_GET['cantidad']))
                    {
                        Pizza::ActualizarPizza($p, $_GET['cantidad'],$_GET['precio']);
                    }
                    else
                    {
                        if(isset($_GET['precio']))
                        {
                            Pizza::ActualizarPizza($p, $_GET['cantidad']);
                        }    
                    }
                    echo json_encode($p);
                }
                Pizza::CargarJSON($pizzas);
            }

            break;
        case 'POST':
            $datos = json_decode(file_get_contents('php://input'));
            $p = Pizza::GetPizza($pizzas,$datos->sabor,$datos->tipo);

            if(property_exists($datos,"email")&&property_exists($datos,"cantidad"))
            {
                if(is_null($p))
                {
                    echo "No hay";
                }
                else
                {
                    if($p->cantidad >= $datos->cantidad)
                    {
                        $t = new Ticket($datos->email,$p->sabor,$p->tipo,$p->precio,$datos->cantidad);
                        array_push($tickets,$t);
                        Ticket::CargarJSON($tickets);
                        Pizza::ActualizarPizza($p,-($datos->cantidad));
                        Pizza::CargarJSON($pizzas);

                        Ticket::CargarImagen($datos->url,$t);

                    }
                    echo json_encode($p);
                }
            }
            else
            {
                if(is_null($p))
                {
                    echo "No hay";
                }
                else
                {
                    echo json_encode($p);
                }
            }
            break;
        case 'DELETE':
            if(isset($_GET['id']))
            {
                Ticket::DeleteById($tickets,$_GET['id']);
            }

            break;
        default:
            echo "Algo";
    }
/*
    ReportVendidos($tickets);
    ReportFechas($tickets,"2023-05-12", "2023-05-15");
    ReportUser($tickets,"julian");
    ReportSabores($tickets,"anchoas");
    */
?>