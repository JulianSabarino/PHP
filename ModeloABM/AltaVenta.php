<?php
class Ticket
    {
        private static $ultimo_id = 0;
        public $id;
        public $email;
        public $sabor;
        public $tipo;
        public $precio;
        public $cantidad;
        public $fecha;
       
       public function __construct($email,$sabor,$tipo,$precio,$cantidad)    
       {
            self::$ultimo_id++;
            $this->id = self::$ultimo_id;
            $this->email = explode("@", $email)[0];
            $this->sabor = $sabor;
            $this->tipo = $tipo;
            $this->precio = $precio;
            $this->cantidad = $cantidad;
            $this->fecha = date("Y-m-d H:i:s");
       }

       public static function CargarTickets()   
       {
            $ticketArray = file_get_contents("tickets.json");
            $tickets = json_decode($ticketArray);
            
            if(!is_null($tickets))
            {
                foreach($tickets as $t)
                {
                    if(Ticket::$ultimo_id < $t->id)
                    {
                        Ticket::$ultimo_id = $t->id;
                    }
                }
            }
            else
            {
                //$tickets = [{}];
            }

            return $tickets;
       }

       public static function CargarJSON($tickets)
       {
            $ticketsJason = json_encode($tickets);
            file_put_contents("tickets.json",$ticketsJason);   
       }

       public static function CargarImagen($url,$t)
       {
            $content = file_get_contents($url);
            $folder = "ImagenesDeLaVenta/".$t->tipo."_".$t->sabor."_".$t->email."_".explode(" ", $t->fecha)[0].".jpg";
            file_put_contents($folder, $content);
       }

       public static function GetById($tickets,$id)
       {
            $ti=null;
            if(!is_null($tickets))
            {
                foreach($tickets as $t)
                {
                    if($t->id == $id)
                    {
                        $ti = $t;
                        break;
                    }
                }
            }
            return $ti;
       }

       public static function DeleteById($tickets,$id)
       {
            $t = Ticket::GetById($tickets,$id);
            echo json_encode($t);
            if(!(is_null($t)))
            {
                $fOrigin = "ImagenesDeLaVenta/".$t->tipo."_".$t->sabor."_".$t->email."_".explode(" ", $t->fecha)[0].".jpg";   
                $fDestiny = "BackUpVentas/".$t->tipo."_".$t->sabor."_".$t->email."_".explode(" ", $t->fecha)[0].".jpg";
                rename($fOrigin,$fDestiny);
            }
       }
    }

    ?>