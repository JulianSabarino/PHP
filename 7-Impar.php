<?php
/*
Aplicación No 7 (Mostrar impares)
Generar una aplicación que permita cargar los primeros 10 números impares en un Array.
Luego imprimir (utilizando la estructura for) cada uno en una línea distinta (recordar que el
salto de línea en HTML es la etiqueta <br/>). Repetir la impresión de los números
utilizando las estructuras while y foreach.
*/
$arrayI = array();

for($i = 1; count($arrayI)<=10;$i++)
{
    if($i%2 != 0)
        array_push($arrayI,$i);
}
foreach($arrayI as $num)
{
    echo $num."</br>";
}