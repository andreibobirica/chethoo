<?php
//IMPOSTAZIONI SITO
define('BASEPATH','/');
//define('DOMAIN','chethoo.andreibobirica.duckdns.org');
define('DOMAIN','localhost');

//Inizio Sessione
if(session_status() == PHP_SESSION_NONE){
    //if session has not started
    session_start();
}

//Algoritmo di Routing Domain e Path
include_once('./core/routing/routing.php');
?>
