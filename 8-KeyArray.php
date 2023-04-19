<?php
/*
Aplicación No 8 (Carga aleatoria)
Imprima los valores del vector asociativo siguiente usando la estructura de control foreach:
$v[1]=90; $v[30]=7; $v['e']=99; $v['hola']= 'mundo';
*/
$array = array(
    1=>30,
    30=>30,
    'e'=>99,
    'hola'=>'mundo',
);

foreach($array as $key=>$value)
{
    echo $key."=>".$value."<br>";
}