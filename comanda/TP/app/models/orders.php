<?php
    include_once "pdo.php";
    class Orden
    {
        public $id;
        private $idTable;
        public $arrayProducts;
        
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
            foreach($products as $p)
            {
                array_push($this->arrayProducts,$p); 
            }
        }

        public function AltaOrden()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $delay = 0;
            foreach($this->arrayProducts as $product) {
                $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_ORDERS']. "(id,tableId,idProduct,cantProd,sector,delay,state)  
                values (:id,:tableId,:idProduct,:cantProduct,:sector,:delay,:state)");
            
                $consulta->bindValue(':id',$this->id);
                $consulta->bindValue(':tableId',$this->idTable);
                $consulta->bindValue(':idProduct',$product['id']);
                $consulta->bindValue(':cantProduct',$product['cant']);
                $consulta->bindValue(':sector',$product['sector']);
                $consulta->bindValue(':delay',$product['delay']);
                $consulta->bindValue(':state',"recibida");

                $consulta->execute();

                if($product['delay'] > $delay)
                {
                    $delay = $product['delay'];
                }
            }
            return $delay;
        }

        public static function GetAll()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_ORDERS']);
			$consulta->execute();

            $orders = array();
            $list = $consulta->fetchAll();
            $firstLine = true;
            $o = new stdClass;
            foreach($list as $fila)
            {

                if($firstLine || ($o->id != $fila['id']))
                {
                    $o = new Orden($fila['tableId'],$fila['id']);
                    $p = new stdClass;
                    $p->id = $fila['idProduct'];
                    $p->cant = $fila['cantProd'];
                    array_push($o->arrayProducts,$p);
                    //$o->arrayProducts[$fila['idProduct']] = $fila['cantProd'];
                    $firstLine = false;
                    array_push($orders,$o);
                }
                else
                {
                    $p = new stdClass;
                    $p->id = $fila['idProduct'];
                    $p->cant = $fila['cantProd'];
                    array_push($o->arrayProducts,$p);
                    //$o->arrayProducts[$fila['idProduct']] = $fila['cantProd'];
                }   
                 
            }
    		
            return $orders;
        }

        public function IsIn($orders)
        {
            $isIn = false;
            foreach($orders as $order)
            {
                if($order->id == $this->id)
                {
                    $isIn = true;
                    break;
                }
            }
            return $isIn;
        }


        public static function GetBy($sector)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from ".$_ENV['T_ORDERS']." WHERE sector = :sector");

            $consulta->bindValue(':sector',$sector);

			$consulta->execute();

            $orders = array();
            $list = $consulta->fetchAll();
            $firstLine = true;
            $o = new stdClass;
            foreach($list as $fila)
            {

                if($firstLine || ($o->id != $fila['id']))
                {
                    $o = new Orden($fila['tableId'],$fila['id']);
                    $p = new stdClass;
                    $p->id = $fila['idProduct'];
                    $p->cant = $fila['cantProd'];
                    array_push($o->arrayProducts,$p);
                    $firstLine = false;
                }
                else
                {
                    $p = new stdClass;
                    $p->id = $fila['idProduct'];
                    $p->cant = $fila['cantProd'];
                    array_push($o->arrayProducts,$p);
                }
                if(!$o->IsIn($orders))
                {
                    array_push($orders,$o); 
                }

            }
    		
            return $orders;
        }

        public static function UpdateBy($sector,$delay)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE ".$_ENV['T_ORDERS']." SET delay=:delay, state=:state WHERE sector = :sector");

            $consulta->bindValue(':delay',$delay);
            $consulta->bindValue(':state',"en preparacion");
            $consulta->bindValue(':sector',$sector);

            return  $consulta->execute();
        }

        public static function OrderFollow($order,$userId,$clientName,$url_photo,$delay,$state=-1)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
            if($state == -1)
            {
                $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_ORDERSF']. "(orderId,tableId,userId,clientName,url_photo,delay,state,start)  
                values (:orderId,:tableId,:userId,:clientName,:url_photo,:delay,:state,:start)");
            
                $consulta->bindValue(':orderId',$order->id);
                $consulta->bindValue(':tableId',$order->idTable);
                $consulta->bindValue(':userId',$userId);
                $consulta->bindValue(':clientName',$clientName);
                $consulta->bindValue(':url_photo',$url_photo);
                $consulta->bindValue(':delay',$delay);
                $consulta->bindValue(':state',"recibida");
                $consulta->bindValue(':start',date("Y-m-d H:i:s"));
                
        
                $consulta->execute();
                
            }
            else
            {

            }
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function GetAllMyOrders()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT orderId,tableId,clientName,delay,state FROM ".$_ENV['T_ORDERSF']);

			$consulta->execute();

            $list = $consulta->fetchAll();
            $firstLine = true;
            $orders = array();
            foreach($list as $fila)
            {
                $o = new stdClass;
                $o->id = $fila["orderId"];
                $o->tableId = $fila["tableId"];
                $o->clientName = $fila["clientName"];
                $o->orderDelay = $fila["delay"];
                $o->state = $fila["state"];
                array_push($orders,$o);
            }

            return $orders;
        }
        public static function GetMyOrder($idTable,$id)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT orderId,tableId,clientName,delay,start,state FROM ".$_ENV['T_ORDERSF']." WHERE orderId = :id");

            $consulta->bindValue(':id',$id);

			$consulta->execute();

            return $consulta->fetchObject();
        }

        public static function ReadyToServe($sector,$id,$idProduct)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE ".$_ENV['T_ORDERS']." SET state=:state WHERE sector=:sector AND id=:id AND idProduct=:idProduct");

            $consulta->bindValue(':state',"servir");
            $consulta->bindValue(':sector',$sector);
            $consulta->bindValue(':id',$id);
            $consulta->bindValue(':idProduct',$idProduct);

            try
            {
                $consulta->execute();

                return "OK";
            }
            catch(Exception $e)
            {
                throw new Exception($e);
            }
        }

        public static function IsReady($id)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT idProduct,state FROM ".$_ENV['T_ORDERS']." WHERE id=:id");
            $consulta->bindValue(':id',$id);

			$consulta->execute();

            $list = $consulta->fetchAll();
            $isReady = true;
            $order = array();
            $orderDetail=new stdClass;
            foreach($list as $fila)
            {
                $o = new stdClass;
                $o->id = $fila["idProduct"];
                $o->state = $fila["state"];
                array_push($order,$o);
                if($o->state != "servir")
                {
                    $isReady = false;
                }
            }

            if($isReady)
            {
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE ".$_ENV['T_ORDERSF']." SET state=:state WHERE orderId=:id");
                $consulta->bindValue(':state',"servir");
                $consulta->bindValue(':id',$id);   
                
                $consulta->execute();
            }
            $orderDetail->id = $id;
            $orderDetail->products = $order;
            $orderDetail->readyToServe = $isReady;

            return $orderDetail;
        }

        public static function GetAllReady()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT orderId,tableId,clientName,state FROM ".$_ENV['T_ORDERSF']." WHERE state=:state");
            $consulta->bindValue(':state',"servir");

			$consulta->execute();

            $list = $consulta->fetchAll();
            $orders = array();
            if($list != false)
            {
                foreach($list as $fila)
                {
                    $o = new stdClass;
                    $o->id = $fila["orderId"];
                    $o->tableId = $fila["tableId"];
                    $o->clientName = $fila["clientName"];
                    $o->state = $fila["state"];
                    array_push($orders,$o);
                }
            }
            else
            {
                throw new Exception("Ningun pedido listo para servir");
            }


            return $orders;
        }

        public static function Serve($id)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE ".$_ENV['T_ORDERSF']." SET state=:state, finish=:finish WHERE orderId=:id");

            $consulta->bindValue(':state',"comiendo");
            $consulta->bindValue(':finish',date("Y-m-d H:i:s"));
            $consulta->bindValue(':id',$id);

            try
            {
                $consulta->execute();

                return "Served";
            }
            catch(Exception $e)
            {
                throw new Exception($e);
            }
        }

        public static function Check($id)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE ".$_ENV['T_ORDERSF']." SET state=:state WHERE orderId=:id");

            $consulta->bindValue(':state',"cobrado");
            $consulta->bindValue(':id',$id);

            try
            {
                $consulta->execute();

                return "Cobrado";
            }
            catch(Exception $e)
            {
                throw new Exception($e);
            }
        }

        public static function Calificate($id,$tablep,$waiter,$cook,$comments)
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into ".$_ENV['T_COMMENTS']. "(orderId,tablep,waiter,cook,comments)  
            values (:orderId,:tablep,:waiter,:cook,:comments)");
            
            $consulta->bindValue(':orderId',$id);
            $consulta->bindValue(':tablep',$tablep);
            $consulta->bindValue(':waiter',$waiter);
            $consulta->bindValue(':cook',$cook);
            $consulta->bindValue(':comments',$comments);                
        
            $consulta->execute();
                
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }

        public static function GetMostUsed()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT tableId,COUNT(tableId) AS ammount FROM ".$_ENV['T_ORDERSF']." GROUP BY tableId ORDER BY ammount DESC LIMIT 1");

			$consulta->execute();

            return $consulta->fetchObject();
        }

        public static function GetBestComment()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT orderId,(tablep + waiter + cook) AS calification,comments FROM ".$_ENV['T_COMMENTS']." ORDER BY calification DESC LIMIT 1");

			$consulta->execute();

            return $consulta->fetchObject();
        }

        public static function Delayed()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT orderId,delay,start,finish FROM ".$_ENV['T_ORDERSF']." WHERE state='finished'");

			$consulta->execute();

            $list = $consulta->fetchAll();
            $orders = array();
            if($list != false)
            {
                foreach($list as $fila)
                {
                    $start = $date = DateTime::createFromFormat("Y-m-d H:i:s", $fila["start"]);
                    $finish = $date = DateTime::createFromFormat("Y-m-d H:i:s", $fila["finish"]);
                    $interval = $start->diff($finish);
                    $iMinutes = ($interval->h * 60) + $interval->i;

                    $o = new stdClass;
                    $o->id = $fila["orderId"];
                    $o->delay = $fila["delay"];
                    $o->start = $start;
                    $o->finish = $finish;
                    $o->interval = $iMinutes;
                    
                    if($iMinutes > $o->delay)
                    {
                        array_push($orders,$o);
                    }

                }
            }
            else
            {
                $o = "NINGUN PEDIDO ENCONTRADO";
                array_push($orders,$o);
            }


            return $orders;
        }
        
    }    
?>