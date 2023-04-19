<?php
/*
Aplicación No 5 (Números en letras)
Realizar un programa que en base al valor numérico de una variable $num, pueda mostrarse
por pantalla, el nombre del número que tenga dentro escrito con palabras, para los números
entre el 20 y el 60.
Por ejemplo, si $num = 43 debe mostrarse por pantalla “cuarenta y tres”.
*/
$num = 59;
$dec = floor($num/10);
$uni = $num - ($dec * 10);
$out = "";
switch($dec)
{
    case 1:
        switch($uni)
        {
            case 0:
                $out = "diez";
                break;
            case 1:
                $out = "once";
                break;
            case 2:
                $out = "doce";
                break;
            case 3:
                $out = "trece";
                break;
            case 4:
                $out = "catorce";
                break;
            case 5:
                $out = "quince";
                break;
            case 6:
                $out = "dieciseis";
                break;
            case 7:
                $out = "diecisiete";
                break;
            case 8:
                $out = "dieciocho";
                break;
            case 9:
                $out = "diecinueve";
                break;
        }
        break; 
    case 2:
        if($uni == 0)
            $out = "veinte";
        else
            $out = "veinti";
        break;
    case 3:
        if($uni == 0)
            $out = "treinta";
        else
            $out = "treinta y ";
        break;
    case 4:
        if($uni == 0)
            $out = "cuarenta";
        else
            $out = "cuarenta y ";
        break;
    case 5:
        if($uni == 0)
            $out = "cincuenta";
        else
            $out = "cincuenta y ";
        break;
}
if($uni == 0 || $dec == 1)
    echo $out;
else
{
    switch($uni)
    {
        case 1:
            $out = $out."uno";
            break;
        case 2:
            $out = $out."dos";
            break;
        case 3:
            $out = $out."tres";
            break;
        case 4:
            $out = $out."cuatro";
            break;
        case 5:
            $out = $out."cinco";
            break;
        case 6:
            $out = $out."seis";
            break;
        case 7:
            $out = $out."siete";
            break;
        case 8:
            $out = $out."ocho";
            break;
        case 9:
            $out =$out."nueve";
            break;
    }
    echo $out;
}