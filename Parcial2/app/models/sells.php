<?php
    include_once "pdo.php";
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
    }