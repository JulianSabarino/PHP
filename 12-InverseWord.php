<?php
/*
Aplicación No 12 (Invertir palabra)
Realizar el desarrollo de una función que reciba un Array de caracteres y que invierta el orden
de las letras del Array.
Ejemplo: Se recibe la palabra “HOLA” y luego queda “ALOH”.
*/
function ReverseWord($array)
{
    $array2 = $array;
    if(krsort($array))
        echo implode($array2)." | ".implode($array);
    
}

$array = array('H','O','L','A');
ReverseWord($array);