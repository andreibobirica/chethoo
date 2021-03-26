<?php

/**
 * La classe DomainRoute fornisce dei metodi capaci di fornire informazioni relative
 * al dominio e al sottodominio.
 * 
 */

class DomainRoute{

    //Attributo stringa o boolean contenente il nome del sottodominio o il suo stato
    private $subdomain = null;
    //* $this->uridomain stringa rapresentante il dominio effettivo del dominio in questa istanza
    //* $this->domain dominio ideale del sito deafult e.g. chethoo.it
    private $uridomain;
    private $domain;

    /**
     * Metodo costruttore, con parametri di default:
     * Si occupa di definire l'attributo subdomain, invoca il metodo privato calculateSubdomain
     * con parametri di defualt la variabile globale $_SERVER['SERVER_NAME'] e la macro DOMAIN
     * Con parametri specificati, invoca la funzione calculateSubdomain con $this->uridomain e $this->domain
     */
    public function __construct($uridomain=1,$domain=1) {
        if($uridomain || $domain){
            $this->uridomain = $_SERVER['SERVER_NAME'];
            $this->domain = DOMAIN;
        }else{
            $this->uridomain = $uridomain;
            $this->domain = $domain;
        }
        $GLOBALS["domain"] = $this->domain;//Variabile globale domain
        $GLOBALS["httpsec"] = $this->getHTTPSec();//variabile glovale httpsec
        $GLOBALS["uridomain"] = $this->getHTTPSec()."://".$this->domain."/";//variabile glovale "https://domain/"
        $this->subdomain = $this->calculateSubdomain();
    }

    /**
     * Metodo GET
     * Restituisce l'attributo subdomain, stringa contenente il nome del sottodominio
     */
    public function getSubdomain():string{
        return $this->subdomain;
    }

    /**
     * Metodo GET
     * Restituisce l'attributo domain, stringa contenente il nome del dominio
     */
    public function getDomain():string{
        return $this->domain;
    }

    /**
     * Metodo GET
     * Restituisce una stringa rapresentante il metodo di sicurezza del http durante la chiamata
     */
    public function getHTTPSec():string{
        return isset($_SERVER['HTTPS']) ? "https" : "http";
    }

    /**
     * Metodo che controlla l'attributo privato subdomain.
     * Si controlla se la stringa fa parte di termini riservati.
     * Se fa parte di termini riservati viene ritornato TRUE
     * Se non fa parte di termini riservati viene ritornato FALSE
     */
    public function verifySubdomainReserved():bool{
       $reserved = array("api", "www", "admin", "mail");
       return in_array($this->subdomain,$reserved); 
    }

    /**
     * Metodo che controlla l'attributo privato subdomain.
     * Si controlla se la stringa è contenuta nel DB come sottodominio autorizzato
     * Se     è contenuto ritorna TRUE
     * Se non è contenuto ritorna FALSE
     */
    public function verifySubdomainInDB():bool{
        //VERIFICA NEL DATABASE
       //Verifica se il sottodominio è veritiero
       if($this->subdomain=="marco")
       return true;
       return false;
   }


    /**
     * Metodo che elabora i due attributi privati $uridomain e $domain
     * Elabora e da come risultato un
     * @return string contenente la stringa del subdomain, nel caso sia valido sintatticamente ed esista, altrimenti stringa vuota
     * */
    private function calculateSubdomain():string{
        $arrayDomain = explode(".",$this->domain);
        $arrayUriDomain = explode(".",$this->uridomain);
        if($this->existsSubdomain($this->uridomain,$this->domain))
            return $arrayUriDomain[0];
        return '';
    }

    /**
     * Metodo che elabora i due attributi privati $uridomain e $domain
     * Elabora e da come risultato un
     * @return bool rapresentante se esiste o meno un errore nel dominio
     */
    public function existsErrorDomain():bool{
        $arrayDomain = explode(".",$this->domain);
        $arrayUriDomain = explode(".",$this->uridomain);
        //Se è il dominio principale oppure se è un subdomain valido, va tutto bene
        //Return false se non ci sono errori
        //Return true se ci sono errori, quindi se non è ne dominio principale e ne subdomain valido
        return !(($arrayDomain===$arrayUriDomain) || $this->existsSubdomain($this->uridomain,$this->domain));
    }

    /**
     * Metodo che elabora i due attributi privati $uridomain e $domain
     * Elabora e da come risultato un
     * @return bool rapresentante se esiste o meno un sottodominio valido sintatticamente nella uridomain
     */
    public function existsSubdomain():bool{
        $arrayDomain = explode(".",$this->domain);
        $arrayUriDomain = explode(".",$this->uridomain);
        //return true se il uridomain non corrisponde a domain e se il primo sottodominio di uridomain corrispode a domain
        return (!($arrayDomain===$arrayUriDomain)&&($this->arrayExceptIndex($arrayUriDomain,0)===$arrayDomain));
    }
    /**
     * Funzione che dato un array, lo restituisce per intero, tranne l'elemento specificato dal index
     * Nel processo ne ridefinisce anche gli indici.
     */
    private function arrayExceptIndex(array $array, int $index):array{
        unset($array[$index]);
        $array = array_merge($array);
        return $array;
    }
}
?>
