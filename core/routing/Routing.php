<?php
/**
 * Per processo di routing si intende l'algoritmo mediante il quale si rendirizzano le richieste HTTP, di un utente o di uno script, verso
 * un detterminato contenuto.
 * Il routing può essere direzionato mediante 
 * - subdomains         marco.chethoo.it  -> MARCO
 * - path               chethoo.it/chi-siamo  -> CHI-SIAMO
 * - GET params         chethoo.it?account="marco"  -> account=marco
 * 
 * Per l'applicativo chethoo si è deciso di unire i primi due metodi , lasciando da parte il passaggio di parametri 
 * tramite parametri GET.
 * Per applicativi API tuttavia si useranno passaggi di dati tramite GET e POST.
 * 
 * Le cose che si possono chiedere alla piattaforma sono generalmente
 * VISTE
 * DATI
 * Richieste di modifica dati
 * Controller Delle Viste
 * 
 * La classe PathRoute gestisce in maniera efficiente il routing delle path
 * La classe DomainRoute fornisce metodi per la gestione del routing dei sottodomini.
 * Il funzionamente della classe PathRoute permette di aggiungere infiniti percorsi di route e solo 
 * in base alla richiesta URI , solo una route viene invocata.
 * Tuttavia su questo applicativo, non verrano aggiunti già da subito tutti i percorsi di routepath ma in base al 
 * indirizzamento del subdomain, si aggiungeranno delle route path.
 * In particolare ci sarà una condizione simil SWITCH, che in base alla tipologia di domain route, creerà delle path Route, 
 * successivamente in base alla URI , il reindirizzamento verrà farro.
 * In questo modo si potranno avere route path uguali ma con differente utilizzo in base al domain path.
 * 
 * Esempio:
 * - admin.chethoo.it
 *      - /         ->>     Pagina Panello di Controllo
 *      - /login    ->>     Pagina lOGIN Normale
 * - chethoo.it
 *      - /         ->>     Pagina di ricerca
 *      - /login    ->>     Pagina Login Normale
 * - supercar.chethoo.it
 *      - /         ->>     Pagina del venditore
 *      - /login    ->>     pagina Login Normale
 * 
 */

 //Include neccessari
 include_once('./core/routing/DomainRoute.php');
 include_once('./core/routing/PathRoute.php');

class Routing{
    //stringa o booleano che rappresenta nome o stato del sottodomino e dominio
    private $subdomain = null;

    //classe DomainRoute per la gestione del routing di dominio
    private $domainRoute = null;
    //classe PathRoute per la gestione del routing di path
    private $pathRoute = null;

    function __construct() {
        $this->domainRoute = new DomainRoute();
        $this->pathRoute = new PathRoute();
        $this->subdomain = $this->domainRoute->getSubdomain();
    }

    /**Metodo run che fa partire il processo di routing */
    public function run():void{
        $this->casoEseguitoSempre();

        if($this->domainRoute->existsErrorDomain())
            $this->casoSubdomainErrore();
        elseif($this->domainRoute->existsSubdomain()){
            if($this->domainRoute->verifySubdomainReserved())
                $this->casoSubdomainRiservato($this->subdomain);
            elseif($this->domainRoute->verifySubdomainInDB())
                $this->casoSubdomainInDB($this->subdomain);
            else
                $this->casoSubdomainNonInDB($this->subdomain);
        }else{
            $this->casoBase();
        }

        // Run the Router with the given Basepath
        //Viene eseguita la ricerca dela routePath corrispondente, e viene effettivamente eseguita la reindirizzazione
        $this->pathRoute->run(BASEPATH);
    }

    /**
     * Funzione eseguita sempre in ogni caso ed in ogni sottodominio
     */
    private function casoEseguitoSempre():void{
        //DA DEFINIRE LA 404
        $this->pathRoute->add('/login/',function(){
            include_once("./core/view/login/loginSkeletron.php");
        });
    }

    /**
     * Funzione eseguita nel caso base in cui il dominio è chethoo.it senza sottodomini, e senza sottodomini errati
     */
    private function casoBase():void{
        // Add base route (startpage)
        $this->pathRoute->add('/',function(){
        echo 'Welcome to caso base, sei sulla pagina di ricerca :-)';
        });

        $this->pathRoute->add('/chi-siamo/',function(){
            echo 'Ciao siamo gli admin di chethoo, ecco noi siamo così bravi</br>';
            echo 'Ciao comunque';
        });

        $this->pathRoute->add('/ciao/',function(){
            echo 'Ciao comunque';
        });
    }

    /**
     * Funzione eseguita in caso di errore presenti nel sottodominio, come per esempio ciao.mamma.chethoo.it
     */
    private function casoSubdomainErrore():void{
        print("false, errore subdominio errore 404");
    }

    /**
     * Funzione eseguita nel caso in cui il sottodomino valido sintatticamente , formalmente rapresenta una stringa
     * riservata come per esempio www oppure mail oppure ftp.
     * Il parametro $subdomain serve appunto a tenere traccia nel valore del subdomain, e reindirizzarlo perso il giusto 
     * servizio.
     */
    private function casoSubdomainRiservato(string $subdomain):void{
        //REINDIRIZZAMENTO SU SERVIZIO RISERVATO
        print("subdomain riservato");
    }

    /**
     * Funzione eseguita nel caso in cui il subdomain sia valido sintatticamente, non siano presenti errori nel
     * sottodominio o nel dominio , ma tuttavia il sottodominio, o meglio la stringa che la cui lo rappresenta
     * non viene trovata nel DB, e quindi non è valida.
     * Il reindirizzamento può avvenire in base al parametro $subdomain
     * 
     */
    private function casoSubdomainNonInDB(string $subdomain):void{
        //REINDIRIZZAMENTO SU pagina 404
        print("subdomain non valido da DB, errore 404");
    }

    /**
     * Funzione eseguita nel caso in cui il subdomain sia valido sintatticamente, non siano presenti errori nel
     * sottodominio o nel dominio e il sottodomionio quindi la stringa che la cui lo rappresenta viene trovata nel DB, 
     * e quindi è valida.
     * Il reindirizzamento può avvenire in base al parametro $subdomain
     */
    private function casoSubdomainInDB(string $subdomain):void{
        //REINDIRIZZAMENTO SU pagina specifica.
        print("subdomain valido da DB");
        $this->pathRoute->add('/profile/',function(){
            echo '<br/>Ciao Ecco il tuo profilo';
        });
    }
}
?>