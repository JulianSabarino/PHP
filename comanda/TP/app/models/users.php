<?php
    include_once "pdo.php";
    class Usuario
    {
        public $id;
        public $name;
        public $surname;
        private $pass;
        public $mail;
        public $date;
        public $type;
        
        public function __construct($name,$surname,$mail,$pass,$type,$date = -1,$id = -1)
        {
            $this->name = $name;
            $this->surname = $surname;
            $this->mail = $mail;
            $this->pass = $pass;
            $this->type = $type;

            if($date == -1)
            {
                $this->date = date("Y-m-d");  
            }
            else
            {
                $this->date = $date;
            }

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
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_USERS']. "(name,surname,mail,pass,date,type) values (:name,:surname,:mail,:pass,:date,:type)");
            
            $consulta->bindValue(':name',$this->name);
            $consulta->bindValue(':surname',$this->surname);
            $consulta->bindValue(':mail',$this->mail);
            $consulta->bindValue(':pass',$this->pass);
            $consulta->bindValue(':date',$this->date);
            $consulta->bindValue(':type',$this->type);

            $consulta->execute();

            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function LeerUsuarios()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_USERS']);
			$consulta->execute();

            $users = array();
            $list = $consulta->fetchAll();
            if($list != false)
            {
                foreach($list as $fila)
                {
                    $u = new Usuario($fila['name'],$fila['surname'],$fila['mail'],$fila['pass'],$fila['type'],$fila['date'],$fila['id']);
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
                $u = new Usuario($fila->name,$fila->surname,$fila->mail,$fila->pass,$fila->type,$fila->date,$fila->id);
            }
            else
            {
                throw new Exception("Users not Found");
            }
    		
            return $u;
        }

        
        
        /*
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
        }*/
    }    
?>