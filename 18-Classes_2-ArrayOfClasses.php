<?php
/*
Aplicación No 18 (Auto - Garage)
Crear la clase Garage que posea como atributos privados:

_razonSocial (String)
_precioPorHora (Double)
_autos (Autos[], reutilizar la clase Auto del ejercicio anterior)
Realizar un constructor capaz de poder instanciar objetos pasándole como

parámetros: i. La razón social.
ii. La razón social, y el precio por hora.

Realizar un método de instancia llamado “MostrarGarage”, que no recibirá parámetros y
que mostrará todos los atributos del objeto.
Crear el método de instancia “Equals” que permita comparar al objeto de tipo Garaje con un
objeto de tipo Auto. Sólo devolverá TRUE si el auto está en el garaje.
Crear el método de instancia “Add” para que permita sumar un objeto “Auto” al “Garage”
(sólo si el auto no está en el garaje, de lo contrario informarlo).
Ejemplo: $miGarage->Add($autoUno);
Crear el método de instancia “Remove” para que permita quitar un objeto “Auto” del
“Garage” (sólo si el auto está en el garaje, de lo contrario informarlo). Ejemplo:
$miGarage->Remove($autoUno);
En testGarage.php, crear autos y un garage. Probar el buen funcionamiento de todos
los métodos.
*/

class Auto
{
    private $_color;
    private $_precio;
    private $_marca;
    private $_fecha;
    
    public function __construct(string $marca,string $color,float $precio =100.0,string $fecha="now")
    {
        $this->_color=$color;
        $this->_precio=$precio;
        $this->_marca=$marca;
        $this->_fecha=new DateTime($fecha);
    }

    public function AgregarImpuestos(float $impuesto)
    {
        $this->_precio+=$impuesto;
        return $this->_precio;
    }

    static function MostrarAuto($auto)
    {
        return "marca: ".$auto->_marca."<br> color: ".$auto->_color."<br> precio: ".$auto->_precio."<br> fecha de fabricacion: ".$auto->_fecha->format('Y/m/d');
    }

    public function Equals($auto)
    {
        $retorno = false;
        if($this->_marca == $auto->_marca)
            $retorno = true;
        return  $retorno;
    }

    static function Acumular($auto1,$auto2)
    {
        $retorno = 0;
        if($auto1->Equals($auto2) && $auto1->_color == $auto2->_color)
            $retorno = $auto1->_precio + $auto2->_precio;
        else
            echo "Los autos no coinciden en color y/o marca";

        return $retorno;
    
    }
}

class Garage 
{
    private string $_razonSocial;
    private float $_precioPorHora;
    public array $_autos;
    public function __construct(string $razonSocial,float $precioPorHora = 20.0)
    {
        $this->_razonSocial = $razonSocial;
        $this->_precioPorHora = $precioPorHora;
        $this->_autos = array();
    }

    public function MostrarGarage(){

        $retorno="<br>NO hay autos en el garage ".$this->_razonSocial."<br>";

        if(count($this->_autos)>0)
        {
            $retorno = $this->_razonSocial."<br>";
            foreach($this->_autos as $key => $value)
            {
                $retorno.="_____<br>Puesto ".($key+1)."<br>".Auto::MostrarAuto($value)."\n<br>";
            }   
        }

        return $retorno;
    }

    public function Equals($auto)
    {
       foreach($this->_autos as $value)
       {
            if($auto->equals($value))
                return true;
       }
       return false;
    }

    public function Add($auto)
    {
        if(!$this->Equals($auto))
        {
            array_push($this->_autos, $auto);
            return true;
        }
        return false;
    }

    public function Remove($auto)
    {
        if($this->Equals($auto))
        {
            foreach($this->_autos as $key => $value)
            {
                if($auto->equals($value))
                {
                    unset($this->_autos[$key]);
                    return true;
                }
            }
        }
        return false;
    }
}

$auto1= new Auto("Renault","rosa",120);
$auto2= new Auto("Renault","verde",150);
$auto3= new Auto("Peugeot","rojo",80);
$auto4= new Auto("Ferrari","negro",400);
$auto5= new Auto("Fiat","blanco",600,"2020/03/25");


$garage1 = new Garage("Abuelo Autito");
$garage2 = new Garage("Hijo Autito",30);

$garage1->Add($auto1);
$garage1->Add($auto2);
$garage1->Add($auto3);
$garage1->Add($auto4);

echo $garage1->MostrarGarage();
echo $garage2->MostrarGarage();

$garage1->Remove($auto1);
$garage1->Remove($auto2);
$garage1->Remove($auto3);
$garage1->Remove($auto4);

echo $garage1->MostrarGarage();
