<?php
    include "dao.php";
    class Mesa
    {
        public $id;
        private $posicion;

        
        public function __construct($posicion, $id = -1)
        {
            $this->posicion = $posicion;

            if($id == -1)
            {
                $this->id = $this->AltaMesa();
            }
            else
            {
                $this->id = $id;
            }
            
        }


        static function MostrarMesa($m) 
        {
            echo "\nID: ",$m->id,"\n",
            "Posicion: ",$m->posicion,"\n";
        }

        public function AltaMesa()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into tables (position)  
            values ('$this->posicion')");
            $consulta->execute();
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function LeerMesa()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT DISTINCT * from tables");
			$consulta->execute();

            $users = array();
            $list = $consulta->fetchAll();
            foreach($list as $fila)
            {
                echo $fila['id']." ".$fila['position']."\n";
                $u = new Mesa($fila['position'],$fila['id']);
                array_push($users,$u); 
            }
    		
            return $users;
        }
        
        public static function BuscarUsuarioPorClaveMail($clave,$mail)
        {
            $message = "";
            foreach (Usuario::LeerUsuarios() as $u) 
            {
                if($clave == $u->clave && $mail == $u->mail){
                    $message = "Verificado";
                }
                else if ($clave != $u->clave && $mail == $u->mail)
                {
                    $message = "Error en los datos";
                }
                else
                {
                    $message = "Usuario no registrado";
                }
            }
            return $message;
        }
    }    
?>