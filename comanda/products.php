<?php
    include "dao.php";
    class Producto
    {
        public $id;
        private $name;
        private $crafter;
        private $type;
        private $mType;
        private $mSize;
        private $stock;

        
        public function __construct($name,$crafter,$type,$mType,$mSize,$stock = -1, $id = -1)
        {
            $this->name = $name;
            $this->crafter = $crafter;
            $this->type = $type;
            $this->mType = $mType;
            $this->mSize = $mSize;
            if($stock == -1)
            {
                $this->stock = 0;
            }
            else
            {
                $this->stock = $stock;
            }

            if($id == -1)
            {
                $this->id = $this->AltaProducto();
            }
            else
            {
                $this->id = $id;
            }
            
        }


        static function MostrarProducto($p) 
        {
            echo "\nID: ",$p->id,"\n",
            "Nombre: ",$p->name,"\n",  
            "Manufacturador: ",$p->crafter,"\n",    
            "Tipo: ",$p->type,"\n",
            "Medida: ",$p->mType,"\n",
            "Stock: ",$p->stock,"\n\n";  
        }

        public function AltaProducto()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into products (name,crafter,type,mType,mSize,stock)  
            values ('$this->name','$this->crafter','$this->type','$this->mType','$this->mSize','$this->stock')");
            $consulta->execute();
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function LeerProducto()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT DISTINCT * from products");
			$consulta->execute();

            $products = array();
            $list = $consulta->fetchAll();
            foreach($list as $fila)
            {
                echo $fila['id']." ".$fila['name']." ".$fila['stock']."\n";
                $p = new Producto($fila['name'],$fila['crafter'],$fila['type'],$fila['mType'],$fila['mSize'],$fila['stock'],$fila['id']);
                array_push($products,$p); 
            }
    		
            return $products;
        }
    }    
?>