<?php
    include_once "pdo.php";
    class Mesa
    {
        public $id;
        public $position;
        public $state;
        

        
        public function __construct($posicion,$state, $id = -1)
        {
            $this->position = $posicion;
            $this->state = $state;

            if($id == -1)
            {
                $this->id = $this->AltaMesa();
            }
            else
            {
                $this->id = $id;
            }
            
        }


        static function MostrarMesa($m) 
        {
            echo "\nID: ",$m->id,"\n",
            "Posicion: ",$m->posicion,"\n";
        }

        public function AltaMesa()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_TABLES']." (position, state) values (:position, :state)");
            
            $consulta->bindValue(':position',$this->position);
            $consulta->bindValue(':state',$this->state);

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

        public static function LeerMesa()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_TABLES']);
            
            try
            {
                $consulta->execute();

                $tables = array();
                $list = $consulta->fetchAll();
                if($list != false)
                {
                    foreach($list as $fila)
                    {
                        $t = new Mesa($fila['position'],$fila['state'],$fila['id']);
                        array_push($tables,$t); 
                    }
                    return $tables;
                }
                else
                {
                    throw new Exception("No Tables");
                }
                
            }
            catch(Exception $e)
            {
                throw new Exception("Imposible de agregar");
            }
        }
        public static function UpdateStatus($idTable,$state)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE ".$_ENV['T_TABLES']." SET state=:state WHERE id=:id");
            
            $consulta->bindValue(':state',$state);
            $consulta->bindValue(':id',$idTable);

            $consulta->execute();

            return "ok";
        }
    }    
?>