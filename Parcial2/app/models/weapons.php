<?php
    include_once "pdo.php";
    class Arma
    {
        public $id;
        public $price;
        public $name;
        public $url_photo;
        public $nationality;
        
        public function __construct($price,$name,$url_photo,$nationality,$id = -1)
        {
            $this->price = $price;
            $this->name = $name;
            $this->url_photo = $url_photo;
            $this->nationality = $nationality;

            if($id == -1)
            {
                $this->id = $this->AltaArma();
            }
            else
            {
                $this->id = $id;
            }
            
        }

        public function AltaArma()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_WEAPONS']." (price, name, url_photo, nationality) values (:price,:name,:url,:nationality)");
            
            $consulta->bindValue(':price',$this->price);
            $consulta->bindValue(':name',$this->name);
            $consulta->bindValue(':url',$this->url_photo);
            $consulta->bindValue(':nationality',$this->nationality);
            try
            {
                $consulta->execute();
                return $objetoAccesoDato->RetornarUltimoIdInsertado();
            }
            catch(Exception $e)
            {
                throw new Exception("Imposible de agregar");
            }
            
        }
        public static function GetAll()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_WEAPONS']);
            
            try
            {
                $consulta->execute();

                $weapons = array();
                $list = $consulta->fetchAll();
                if($list != false)
                {
                    foreach($list as $fila)
                    {
                        $w = new Arma($fila['price'],$fila['name'],$fila['url_photo'],$fila['nationality'],$fila['id']);
                        array_push($weapons,$w); 
                    }
                    return $weapons;
                }
                else
                {
                    throw new Exception("No Weapons");
                }
                
            }
            catch(Exception $e)
            {
                throw new Exception("Imposible de agregar");
            }
        }

        public static function GetBy($arguments)
        {
            //var_dump("SELECT * from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);           
            try
            {
                $consulta->execute();

                $weapons = array();
                $list = $consulta->fetchAll();
                if($list != false)
                {
                    foreach($list as $fila)
                    {
                        $w = new Arma($fila['price'],$fila['name'],$fila['url_photo'],$fila['nationality'],$fila['id']);
                        array_push($weapons,$w); 
                    }
                    return $weapons;
                }
                else
                {
                    throw new Exception("No Weapons");
                }
                
            }
            catch(Exception $e)
            {
                throw new Exception("Imposible de agregar");
            }
        }
/*

        static function MostrarUsuario($u) 
        {
            echo "\nID: ",$u->id,"\n";
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
                //var_dump($fila);
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
        }*/
    }    
?>