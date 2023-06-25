<?php
/*
Aplicación No 20 BIS (Registro CSV)
Archivo: registro.php
método:POST
Recibe los datos del usuario(nombre, clave,mail )por POST ,
crear un objeto y utilizar sus métodos para poder hacer el alta,
guardando los datos en usuarios.csv.
retorna si se pudo agregar o no.
Cada usuario se agrega en un renglón diferente al anterior.
Hacer los métodos necesarios en la clase usuario

Aplicación No 21 ( Listado CSV y array de usuarios)
Archivo: listado.php
método:GET
Recibe qué listado va a retornar(ej:usuarios,productos,vehículos,...etc),por ahora solo tenemos
usuarios).
En el caso de usuarios carga los datos del archivo usuarios.csv.
se deben cargar los datos en un array de usuarios.
Retorna los datos que contiene ese array en una lista

NOTA: se modifico para hacer una peticion a mis usuarios y buscar por tipo, para practicar sobrecarga de ffunciones tambien
*/
    class Usuario
    {
        private static $ultimo_id = 0;
        public $id;
        public $nombre;
        public $clave;
        public $mail;
        public $fecha_registro;
       
       public function __construct($nombre,$clave = "",$mail = "")    
       {
            self::$ultimo_id++;
            $this->id = self::$ultimo_id;
            $this->nombre = $nombre;
            $this->clave = $clave;
            $this->mail = $mail;
            $this->fecha_registro = date("Y-m-d H:i:s");           
       }
       
       public function AltaUsuarios($usuarios)   
       {
          
            array_push($usuarios,$this);
       }

       public static function CargarUsuarios()   
       {
            $usuarioJson = file_get_contents("usuarios.json");
            $usuarioArray = json_decode($usuarioJson);
                       
            foreach($usuarioArray as $usuario)
            {
                if(Usuario::$ultimo_id < $usuario->id)
                {
                    Usuario::$ultimo_id = $usuario->id;
                }
            }

            return $usuarioArray;
       }

       public static function CargarJSON($usuarios)
       {
            $usuarioJson = json_encode($usuarios);
            file_put_contents("usuarios.json",$usuarioJson);   
       }

       public static function GetById($usuarios, $id = 0)
       {
            $lista;
            if($id == 0)
            {
                $lista = json_encode($usuarios);
            }
            else
            {
                foreach($usuarios as $u)
                {
                    if($u->id == $id)
                    {
                        $lista = json_encode($u);
                        break;
                    }
                }
            }
            return $lista;
       }
    }


    $usuarios = Usuario::CargarUsuarios();
    //echo json_encode($usuarios);

    switch($_SERVER["REQUEST_METHOD"])
    {
        case 'GET':
            if(isset($_GET['id']))
            {
                echo Usuario::GetById($usuarios, $_GET['id']);
            }
            else
            {
                echo Usuario::GetById($usuarios);
            }
            break;

        case 'POST':
            $datos = json_decode(file_get_contents('php://input'));
            $usuario = new Usuario($datos->nombre,$datos->clave, $datos->mail);
            //echo $usuario->fecha_registro;
            //echo json_encode($usuario);
            array_push($usuarios,$usuario);
            //$usuario->AltaUsuarios($usuarios);
            echo json_encode($usuarios);
            Usuario::CargarJSON($usuarios);
            break;
        default:
            echo "Algo";
    }
?>