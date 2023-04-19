<?php
/*
Aplicación No 13 (Invertir palabra)
Crear una función que reciba como parámetro un string ($palabra) y un entero ($max). La
función validará que la cantidad de caracteres que tiene $palabra no supere a $max y además
deberá determinar si ese valor se encuentra dentro del siguiente listado de palabras válidas:
“Recuperatorio”, “Parcial” y “Programacion”. Los valores de retorno serán: 1 si la palabra
pertenece a algún elemento del listado.
0 en caso contrario.
*/
function Validar($string, $size)
{
    $array = array("Recuperatorio","Parcial","Programacion");

    echo $string." ".$size."<br>";
    if (strlen($string) > $size)
        echo "Muy larga. ";
    else
        echo "Normal. ";

    if(array_search($string,$array) !== false)//Es estricto. 
        echo "Existe la palabra";
    else
        echo "No existe la palabra";
    
    echo "<br>";
}

Validar("Hola",5);
Validar("Hola",3);

Validar("Recuperatorio",5);
Validar("Recuperatorio",20);

Validar("Parcial",5);
Validar("Parcial",20);

Validar("Programacion",5);
Validar("Programacion",20);