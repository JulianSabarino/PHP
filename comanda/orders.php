<?php
    include "dao.php";
    class Orden
    {
        public $id;
        private $idTable;
        private $arrayProducts;
        
        public function __construct($idTable, $id = -1)
        {
            $this->idTable = $idTable;
            $this->arrayProducts = array();
            if($id == -1)
            {
                $datetime = new DateTime();
                $timestamp = $datetime->getTimestamp();
                $intId = preg_replace("/[^0-9]/", "", $timestamp);
                $this->id = $intId;
            }
            else
            {
                $this->id = $id;
            }
        }


        static function MostrarOrden($o) 
        {
            echo "\nID: ",$o->id,"\n";
            foreach($o->arrayProducts as $idProduct => $cantProduct)
            {
                echo "\nProd: ",$idProduct,"___$cantProduct\n";
            }

        }

        public function CargarOden($products)
        {
            foreach($products as $idProduct=>$cantProduct)
            {
                $this->arrayProducts[$idProduct] = $cantProduct;
            }
        }

        public function AltaOrden()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            foreach($this->arrayProducts as $idProduct => $cantProduct) {
                $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into orders (idTable,idProduct,cantProduct)  
                values ('$this->idTable','$idProduct','$cantProduct')");
                $consulta->execute();
            }
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function LeerOrden()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from orders");
			$consulta->execute();

            $orders = array();
            $list = $consulta->fetchAll();
            $firstLine = true;
            $o = new stdClass;
            foreach($list as $fila)
            {

                if($firstLine || (isset($o->id) &&($o->id != $fila['id'])))
                {
                    $o = new Orden($fila['idTable'],$fila['id']);
                    $o->arrayProducts[$fila['idProduct']] = $fila['cantProduct'];
                    $firstLine = false;
                }
                else
                {
                    $o->arrayProducts[$fila['idProduct']] = $fila['cantProduct'];
                }   
                array_push($orders,$o); 
            }
    		
            return $orders;
        }
    }    
?>