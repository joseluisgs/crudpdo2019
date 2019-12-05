<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function alerta($texto) {
    echo '<script type="text/javascript">alert("' . $texto . '")</script>';
}

// filtrado de datos de formulario
function filtrado($datos) {
    $datos = trim($datos); // Elimina espacios antes y después de los datos
    $datos = stripslashes($datos); // Elimina backslashes \
    $datos = htmlspecialchars($datos); // Traduce caracteres especiales en entidades HTML
    return $datos;
}

// Codifica en base64
function encode($str){
    return urlencode(base64_encode($str));
}
//Decodifica en base64
function decode($str){
    return base64_decode(urldecode($str));
}

