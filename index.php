<?php
//Include neccessari
include_once('./core/routing/DomainRoute.php');

//Definizio del dominio
//define('DOMAIN','chethoo.andreibobirica.duckdns.org');
define('DOMAIN','chethoo.it');


//Inizio Sessione
if(session_status() == PHP_SESSION_NONE){
    //if session has not started
    session_start();
}

$subdomain = DomainRoute::getSubdomain($_SERVER['SERVER_NAME'],DOMAIN);
//Caso dominio BASE es chethoo.it
if($subdomain===true){
    print("true sito normale");
}
//Caso dominio errore es c.ciao.ciao.chethoo.it
elseif($subdomain===false){
    print("false, errore dominio errore 404");
}else{
    //se riservato FALSE
    //se non in DB TRUE
    //se    in DB valore
    $esitoVerificaSubdomain = DomainRoute::verifySubdomain($subdomain);
    //caso subdominio riservato
    if($esitoVerificaSubdomain===false){
        //REINDIRIZZAMENTO SU SERVIZIO RISERVATO
        print("sottodominio riservato");
    }
    //caso subdominio non riservato e non presente in DB
    elseif($esitoVerificaSubdomain === true){
        //REINDIRIZZAMENTO SU pagina 404
        print("sottodominio non valido in DB, errore 404");
    }
    //caso subdomnio non riservato e presente in db
    else{
        //REINDIRIZZAMENTO SU pagina specifica.
        print("sottodominio valido da DB");
    }
}
?>
