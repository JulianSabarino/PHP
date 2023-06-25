<?php
    include "dao.php";
    class Usuario
    {
        public $id;
        private $name;
        private $surname;
        private $pass;
        private $mail;
        private $date;
        private $type;

        
        public function __construct($name,$surname,$mail,$pass,$type,$date = -1, $id = -1)
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


        static function MostrarUsuario($objUsuario) 
        {
            echo "\nID: ",$objUsuario->id,"\n",
            "Nombre: ",$objUsuario->name,"\n",  
            "Clave: ",$objUsuario->pass,"\n",    
            "Mail: ",$objUsuario->mail,"\n",
            "Fecha de Registro: ",$objUsuario->date,"\n",
            "Tipo de Usuario: ",$objUsuario->type,"\n\n";  
        }

        public function AltaUsuario()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into users (name,surname,mail,pass,date,type)  
            values ('$this->name','$this->surname','$this->mail','$this->pass','$this->date','$this->type')");
            $consulta->execute();
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function LeerUsuarios()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT DISTINCT * from users");
			$consulta->execute();

            $users = array();
            $list = $consulta->fetchAll();
            foreach($list as $fila)
            {
                echo $fila['id']." ".$fila['name']." ".$fila['date']."\n";
                $u = new Usuario($fila['name'],$fila['surname'],$fila['mail'],$fila['pass'],$fila['type'],$fila['date'],$fila['id']);
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