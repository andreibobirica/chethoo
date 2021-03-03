<?php
class DomainRoute{

    /**
     * Data una stringa detta subdomain, già verificata e controllatane l'autenticità:
     * Si controlla se la stringa fa parte di termini riservati.
     * Se fa parte di termini riservati viene ritornato FALSE
     * Se non fa parte di termini riservati viene successivamente
     * effettuato un controllo, per vedere se il subdomain è contanuto all'interno del 
     * DATABASE.
     * Se non è contenuto nel database viene ritornato TRUE
     * Se     è contenuto nel database viene ritornato il suo valore medesimo.
     */
    public static function verifySubdomain($subdomain){
         //VERIFICA NEL DATABASE
        //Verifica se il sottodominio è veritiero
        if($subdomain=="marco")
        return "marco";
        //prova
        $reserved = array("api", "www", "admin", "mail");
        return !in_array($subdomain,$reserved);
       
    }


    /**
     * Date due stringe come parametro:
     * $uridomain : dominio intero preso dalla URI
     * $domain : dominio teorico del sito
     * Le analizza, e restituisce il sottodominio della URIDOMAIN più piccolo.
     * Se il $domain è chethoo.it.
     * Con input api.chethoo.it si avrà in output api
     * Con input api.chethoo.andreibobirica.duckdns.org si avrà in output api
     * Con input chethoo.com si avrà in output return true
     * Con input non autorizzati, come per esempio api.api.chethoo.it, return false
     * In particolare
     * o restituisce true se non esiste dominio più piccolo di chethoo.it
     * o restituisce il sottodominio più piccolo se quello più grande è chethoo.it
     * o restituisce false in tutti gli altri casi
     */
    public static function getSubdomain($uridomain,$domain){
        $arrayDomain = explode(".",$domain);
        $arrayUriDomain = explode(".",$uridomain);
        //print_r($arrayDomain);
        //print("</br>");
        //print_r($arrayUriDomain);
        //print("</br>");
        //print_r(DomainRoute::arrayExceptIndex($arrayUriDomain,0));
        //caso chethoo.it
        if($arrayDomain===$arrayUriDomain){
            return true;
        }
        //caso api.chethoo.it
        elseif(DomainRoute::arrayExceptIndex($arrayUriDomain,0)===$arrayDomain){
            return $arrayUriDomain[0];
        }
        //caso errore o etc
        else{
            return false;
        }
    }
    /**
     * Funzione che dato un array, lo restituisce per intero, tranne l'elemento specificato dal index
     * Nel processo ne ridefinisce anche gli indici.
     */
    public static function arrayExceptIndex($array, $index){
        unset($array[$index]);
        $array = array_merge($array);
        return $array;
    }
}
?>
