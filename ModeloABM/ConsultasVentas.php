<?php
function ReportVendidos($tickets)
{
    $cantVendida = 0;
    foreach($tickets as $t)
    {
        $cantVendida = $cantVendida + $t->cantidad;
    }
    echo "Cantidad vendidas: ".$cantVendida;
    return;
}

function CompararSabores($a, $b) {
    return $a->sabor <=> $b->sabor;
}

function ReportFechas($tickets,$fI = "", $fF = "")
{
    $f1 = strtotime($fI);
    $f2 = strtotime($fF);
    $array=[];
    foreach($tickets as $t)
    {
        $fT = strtotime($t->fecha);
        if($fT>$f1 && $fT<$f2)
        {
            array_push($array,$t);
        }
    }

    usort($array, 'CompararSabores');
    echo json_encode($array)."<br>";

    return;
}

function ReportUser($tickets, $user)
{
    foreach($tickets as $t)
    {
        if($t->email == $user)
        {
            echo json_encode($t)."<br>";
        }
    }
    return;
}



function ReportSabores($tickets, $sabor)
{
    foreach($tickets as $t)
    {
        if($t->sabor == $sabor)
        {
            echo json_encode($t)."<br>";
        }
    }
    return;
}
?>