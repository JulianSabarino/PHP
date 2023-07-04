<?php
    include_once "pdo.php";
    class Producto
    {
        public $id;
        public $name;
        public $crafter;
        public $type;
        public $mType;
        public $mSize;
        public $stock;

        
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
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_PRODUCTS']." (name, crafter, type, mType, mSize, stock) values (:name, :crafter, :type, :mType, :mSize, :stock)");
            
            $consulta->bindValue(':name',$this->name);
            $consulta->bindValue(':crafter',$this->crafter);
            $consulta->bindValue(':type',$this->type);
            $consulta->bindValue(':mType',$this->mType);
            $consulta->bindValue(':mSize',$this->mSize);
            $consulta->bindValue(':stock',$this->stock);

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
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_PRODUCTS']);
			$consulta->execute();

            $products = array();
            $list = $consulta->fetchAll();
            foreach($list as $fila)
            {
                $p = new Producto($fila['name'],$fila['crafter'],$fila['type'],$fila['mType'],$fila['mSize'],$fila['stock'],$fila['id']);
                array_push($products,$p); 
            }
    		
            return $products;
        }
    }    
?>