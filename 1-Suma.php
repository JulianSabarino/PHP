<?php
/*
Aplicación No 1 (Sumar números)
Confeccionar un programa que sume todos los números enteros desde 1 mientras la suma no
supere a 1000. Mostrar los números sumados y al finalizar el proceso indicar cuantos números
se sumaron.
*/
$num = 1;
$suma = 0;

while(($suma + $num) < 1000)
{
    $suma = $suma + $num;
    echo $num . "|" . $suma . "<br>";
    $num++;
}