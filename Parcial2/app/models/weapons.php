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

        public static function DeleteBy($arguments)
        {
            //var_dump("SELECT * from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            //$consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);           
//Descomentar esta linea cuando se quiera eliminar
            $consulta = $objetoAccesoDato->RetornarConsulta("DELETE from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);        
            try
            {
                $consulta->execute();
                /*
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
                */
                return "OK";
                
            }
            catch(Exception $e)
            {
                throw new Exception("Imposible de agregar");
            }
        }

        public static function ModifyBy($arguments)
        {
            //var_dump("SELECT * from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            var_dump($arguments);
            $consulta = $objetoAccesoDato->RetornarConsulta($arguments);           
            try
            {
                $consulta->execute();

                return "OK";
                
            }
            catch(Exception $e)
            {
                throw new Exception("Imposible de agregar");
            }
        }
    }    
?>