<?php
/*
Aplicación No 4 (Calculadora)
Escribir un programa que use la variable $operador que pueda almacenar los símbolos
matemáticos: ‘+’, ‘-’, ‘/’ y ‘*’; y definir dos variables enteras $op1 y $op2. De acuerdo al
símbolo que tenga la variable $operador, deberá realizarse la operación indicada y mostrarse el
resultado por pantalla.
*/
$operador = "+";
$n1 = 5;
$n2 = 7;

switch($operador)
{
    case "+":
        echo $n1 + $n2 . "<br>";
        break;
    case "-":
        echo $n1 - $n2 . "<br>";
        break;
    case "/":
        if($n2 != 0)
            echo $n1 / $n2 . "<br>";
        else
            echo "Math Error" . "<br>";
        break;
    case "*":
        echo $n1 * $n2 . "<br>";
        break;
}