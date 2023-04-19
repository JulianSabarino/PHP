<?php
/*
Aplicación No 6 (Carga aleatoria)
Definir un Array de 5 elementos enteros y asignar a cada uno de ellos un número (utilizar la
función rand). Mediante una estructura condicional, determinar si el promedio de los números
son mayores, menores o iguales que 6. Mostrar un mensaje por pantalla informando el
resultado.
*/

$array = array(rand(0,10),rand(0,10),rand(0,10),rand(0,10),rand(0,10));
$suma = 0;

foreach($array as $num)
{
    $suma += $num;
}
$promedio = round($suma/5);
if($promedio > 6)
    echo "El promedio es mayor a 6 ".$promedio;
elseif($promedio < 6)
    echo "El promedio es menor a 6 ".$promedio;
else
    echo "El promedio es igual a 6 ".$promedio;
