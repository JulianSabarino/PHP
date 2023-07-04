<?php
    include_once "pdo.php";
    include_once "weapons.php";
    class Ventas
    {
        public $id;
        public $idWeapon;
        public $idUser;
        public $qWeapon;
        public $dateSell;
        public $url;
        public function __construct($idWeapon,$idUser,$qWeapon,$dateSell,$url,$id = -1)
        {
            $this->idWeapon = $idWeapon;
            $this->idUser = $idUser;
            $this->qWeapon = $qWeapon;
            $this->dateSell = $dateSell;
            $this->url = $url;

            if($id == -1)
            {
                $this->id = $this->AltaVenta();
            }
            else
            {
                $this->id = $id;
            }
        }

        public function AltaVenta()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_SELLS']." (idWeapon, idUser, qWeapon, dateSell, url_photo) values (:idWeapon,:idUser,:qWeapon,:dateSell,:urlSell)");
            
            $consulta->bindValue(':idWeapon',$this->idWeapon);
            $consulta->bindValue(':idUser',$this->idUser);
            $consulta->bindValue(':qWeapon',$this->qWeapon);
            $consulta->bindValue(':dateSell',$this->dateSell);
            $consulta->bindValue(':urlSell',$this->url);

            try
            {
                $consulta->execute();
            }
            catch(Exception $e)
            {
                throw new Exception($e);
            }

            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function GetBy($arguments)
        {
            //var_dump("SELECT * from ".$_ENV['T_WEAPONS']." WHERE ".$arguments);
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_SELLS']." WHERE ".$arguments);           
            try
            {
                $consulta->execute();

                $weapons = array();
                $list = $consulta->fetchAll(PDO::FETCH_CLASS);
                if($list != false)
                {
                    return $list;
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

        public static function FilterByDates($ventas)
        {
            $startDate = '2023-06-25 00:00:00';
            $endDate = '2023-06-26 23:59:59';

            $filteredObjects = array_filter($ventas, function($venta) use ($startDate, $endDate) {
                $datetime = $venta->dateSell;
                return ($datetime >= $startDate && $datetime <= $endDate);
            });

            return $filteredObjects;
        }
    }