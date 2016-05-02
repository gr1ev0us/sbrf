<?php
/**
 * Created by PhpStorm.
 * User: grievous
 * Date: 27.04.16
 * Time: 19:40
 */
namespace QFive\gr1ev0us\SBRF;


function ifNoEmpty($value, $defaultValue = null){
    if(!empty($value)){
        return $value;
    }
    return $defaultValue;
}

function sendRedirect($url){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . $url);
}