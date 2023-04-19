<?php
/*
Aplicación No 3 (Obtener el valor del medio)
Dadas tres variables numéricas de tipo entero $a, $b y $c realizar una aplicación que muestre
el contenido de aquella variable que contenga el valor que se encuentre en el medio de las tres
variables. De no existir dicho valor, mostrar un mensaje que indique lo sucedido. Ejemplo 1: $a
= 6; $b = 9; $c = 8; => se muestra 8.
Ejemplo 2: $a = 5; $b = 1; $c = 5; => se muestra un mensaje “No hay valor del medio”
*/
function HasMiddle($vec)
{
    sort($vec);
    if($vec[0] == $vec[1] || $vec[1] == $vec[2])
        echo "No hay central";
    else
        echo $vec[1];
    
    echo "<br>";
}

$vec = array(1,2,3);
HasMiddle($vec);

$vec = array(1,2,1);
HasMiddle($vec);

$vec = array(2,3,1);
HasMiddle($vec);