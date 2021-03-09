<?php

/**
 * La classe DomainRoute fornisce dei metodi capaci di fornire informazioni relative
 * al dominio e al sottodominio.
 * 
 */

class DomainRoute{

    //Attributo stringa o boolean contenente il nome del sottodominio o il suo stato
    private $subdomain = null;

    /**
     * Metodo costruttore, con parametri di default:
     * Si occupa di definire l'attributo subdomain, invoca il metodo privato calculateSubdomain
     * con parametri di defualt la variabile globale $_SERVER['SERVER_NAME'] e la macro DOMAIN
     * Con parametri specificati, invoca la funzione calculateSubdomain con $uridomain e $domain
     */
    public function __construct($uridomain=1,$domain=1) {
        if($uridomain && $domain)
        $this->subdomain = $this->calculateSubdomain($_SERVER['SERVER_NAME'],DOMAIN);
        else
        $this->subdomain = $this->calculateSubdomain($uridomain,$domain);

    }

    /**
     * Metodo GET
     * Restituisce l'attributo subdomain, stringa contenente il nome del sottodominio
     */
    public function getSubdomain(){
        return $this->subdomain;
    }

    /**
     * Data una stringa detta subdomain
     * Si controlla se la stringa fa parte di termini riservati.
     * Se fa parte di termini riservati viene ritornato TRUE
     * Se non fa parte di termini riservati viene successivamente
     * effettuato un controllo, per vedere se il subdomain è contenuto all'interno del 
     * DATABASE.
     * Se non è contenuto nel database viene ritornato FALSE
     * Se     è contenuto nel database viene ritornato il suo valore medesimo.
     */
    public function verifySubdomain($subdomain){
         //VERIFICA NEL DATABASE
        //Verifica se il sottodominio è veritiero
        if($subdomain=="marco")
        return "marco";


        //prova



        $reserved = array("api", "www", "admin", "mail");
        return in_array($subdomain,$reserved); 
    }


    /**
     * Metodo privato, serve per calcolare il subdomain e definirlo.
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
    private function calculateSubdomain($uridomain,$domain){
        $arrayDomain = explode(".",$domain);
        $arrayUriDomain = explode(".",$uridomain);
        //print_r($arrayDomain);
        //print("</br>");
        //print_r($arrayUriDomain);
        //print("</br>");
        //print_r($this->arrayExceptIndex($arrayUriDomain,0));

        //caso chethoo.it
        if($arrayDomain===$arrayUriDomain){
            return true;
        }
        //caso api.chethoo.it
        elseif($this->arrayExceptIndex($arrayUriDomain,0)===$arrayDomain){
            return $arrayUriDomain[0];
        }
        //caso errore o etc, ciao.api.chethoo.it
        else{
            return false;
        }
    }
    /**
     * Funzione che dato un array, lo restituisce per intero, tranne l'elemento specificato dal index
     * Nel processo ne ridefinisce anche gli indici.
     */
    private function arrayExceptIndex($array, $index){
        unset($array[$index]);
        $array = array_merge($array);
        return $array;
    }
}
?>
