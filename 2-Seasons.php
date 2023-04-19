<?php
/*
Aplicación No 2 (Mostrar fecha y estación)
Obtenga la fecha actual del servidor (función date) y luego imprímala dentro de la página con
distintos formatos (seleccione los formatos que más le guste). Además indicar que estación del
año es. Utilizar una estructura selectiva múltiple.
*/
$inicio = strtotime("01/01/2023");
$fecha = date("d/m/Y");

$diferencia = round((strtotime($fecha) - $inicio)/ (60 * 60 * 24)); //segundos > minutos > horas > dias

//echo $diferencia . "<br>";
//Verano > Oto;o > Invierno > Primavera > Verano
//Recordar que es m/d/Y
$otono = round((strtotime("03/21/2023") - $inicio)/(60 * 60 * 24));
$invierno = round((strtotime("06/21/2023") - $inicio)/(60 * 60 * 24));
$primavera = round((strtotime("09/21/2023") - $inicio)/(60 * 60 * 24)); 
$verano = round((strtotime("12/21/2023") - $inicio)/(60 * 60 * 24));

//echo $otono . "|" . $invierno . "|" . $primavera . "|" . $verano . "<br>" ;

if($diferencia < $otono)
    echo "Verano";
elseif($diferencia >= $otono && $diferencia < $invierno)
    echo "Otono";
elseif($diferencia >= $invierno && $diferencia < $primavera)
    echo "Invierno";
elseif($diferencia >= $primavera && $diferencia < $verano)
    echo "Primavera";
else
    echo "Verano";