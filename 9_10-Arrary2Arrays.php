<?php
/*
Aplicación No 9 (Arrays asociativos)
Realizar las líneas de código necesarias para generar un Array asociativo $lapicera, que
contenga como elementos: ‘color’, ‘marca’, ‘trazo’ y ‘precio’. Crear, cargar y mostrar tres
lapiceras.

Aplicación No 10 (Arrays de Arrays)
Realizar las líneas de código necesarias para generar un Array asociativo y otro indexado que
contengan como elementos tres Arrays del punto anterior cada uno. Crear, cargar y mostrar los
Arrays de Arrays.
*/

function mostrarLapicera($lapicera)
{
    foreach($lapicera as $key=>$value)
{
    echo $key.": ".$value."<br>";
}
}

$lapicera1 = array(
    'color' => "negro",
    'marca' => "sharpy",
    'trazo' => "fino",
    'precio' => 63
);

$lapicera2 = array(
    'color' => "azul",
    'marca' => "bic",
    'trazo' => "redondo",
    'precio' => 5
);

$lapicera3 = array(
    'color' => "rojo",
    'marca' => "segunda",
    'trazo' => "alguno",
    'precio' => 2
);

$arrayLapicera = array($lapicera1,$lapicera2,$lapicera3);

foreach($arrayLapicera as $lapicera)
{
    mostrarLapicera($lapicera);
    echo "<br>";
}

