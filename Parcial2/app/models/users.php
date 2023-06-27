<?php
    include_once "pdo.php";
    class Usuario
    {
        public $id;
        public $mail;
        public $type;
        private $pass;
        
        public function __construct($mail,$type,$pass,$id = -1)
        {
            $this->mail = $mail;
            $this->type = $type;
            $this->pass = $pass;

            if($id == -1)
            {
                $this->id = $this->AltaUsuario();
            }
            else
            {
                $this->id = $id;
            }
            
        }


        static function MostrarUsuario($u) 
        {
            echo "\nID: ",$u->id,"\n";
        }

        public function AltaUsuario()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_USERS']." values (:mail,:type,:pass)");
            
            $consulta->bindValue(':mail',$this->mail);
            $consulta->bindValue(':type',$this->type);
            $consulta->bindValue(':pass',$this->pass);

            $consulta->execute();

            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function LeerUsuarios()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from".$_ENV['T_USERS']);
			$consulta->execute();

            $users = array();
            $list = $consulta->fetchAll();
            if($list != false)
            {
                foreach($list as $fila)
                {
                    $u = new Usuario($fila['mail'],$fila['type'],$fila['pass'],$fila['id']);
                    array_push($users,$u); 
                }
            }
            else
            {
                throw new Exception("No Users");
            }
    		
            return $users;
        }
        
        public static function UsersByKeyValue($mail,$pass)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_USERS']." WHERE mail = :mail AND pass = :pass");
            
            $consulta->bindValue(':mail',$mail);
            $consulta->bindValue(':pass',$pass);
            
			$consulta->execute();

            $u = new stdClass;
            $fila = $consulta->fetchObject();
            if($fila != false)
            {
                $u = new Usuario($fila->mail,$fila->type,$fila->pass,$fila->id);
            }
            else
            {
                throw new Exception("Users not Found");
            }
    		
            return $u;
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