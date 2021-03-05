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
 * Per Vista si intende una view, una pagina HTML vuota e non popolata, nemmeno con testo dentro, dove è presente solo uno skeleton.
 * Abbinata alla Vista ci sarà un controller fatto in Javascript che , facendo le opportune richieste, popolerà
 * la Vista, sia con i dati statici, che con i dati dinamini.
 * Si intende per dati statici scritte e descrizioni del sito, si intende per dati dinamici parti di testo prese
 * dal database ed immagini allegate.
 * Durante l'interazione con la pagina, il controller potrà chiedere nuovi dati e popolare nuove parti della view.
 * In caso ci fosse bisogno di inviare dei dati, tramite FORM, oppure panelli di modifica dati:
 * Il processo di visualizzazione, modifica, caricamento, e ri-visualizzazione, sarà fatto dalla stessa VIEW.
 * In pratica ogni view sarà come una piccola web app.
 */

 //Include neccessari
include_once('./core/routing/DomainRoute.php');
include_once('./core/routing/PathRoute.php');

$subdomain = DomainRoute::getSubdomain($_SERVER['SERVER_NAME'],DOMAIN);
//Caso dominio BASE es chethoo.it
if($subdomain===true){
    casoBase();
}
//Caso subdomain errore es c.ciao.ciao.chethoo.it
elseif($subdomain===false){
    casoSubdomainErrore();
}
//Caso in cui è presente un subdomain valido sintaticamente
else{
    //in base al valore di $subdomain
    //se riservato = FALSE
    //se non in DB = TRUE
    //se    in DB  = valore

    $esitoVerificaSubdomain = DomainRoute::verifySubdomain($subdomain);
    //caso subdominio riservato
    if($esitoVerificaSubdomain===false){
        casoSubdomainRiservato($subdomain);
    }
    //caso subdomain non riservato e non presente in DB
    elseif($esitoVerificaSubdomain === true){
        casoSubdomainNonInDB($subdomain);
    }
    //caso subdomnio non riservato e presente in db
    else{
        casoSubdomainInDB($subdomain);
    }
}

/**
 * Funzione eseguita nel caso base in cui il dominio è chethoo.it senza sottodomini, e senza sottodomini errati
 */
function casoBase(){
    print("true sito normale <br/>");
    // Add base route (startpage)
    PathRoute::add('/',function(){
      echo 'Welcome to caso base, sei sul dominio di default :-)';
    });

    PathRoute::add('/ciao/',function(){
        echo 'Welcome to caso base, sei sul dominio di default :-)</br>';
        echo 'Ciao comunque';

    });
}

/**
 * Funzione eseguita in caso di errore presenti nel sottodominio, come per esempio ciao.mamma.chethoo.it
 */
function casoSubdomainErrore(){
    print("false, errore subdominio errore 404");
}

/**
 * Funzione eseguita nel caso in cui il sottodomino valido sintatticamente , formalmente rapresenta una stringa
 * riservata come per esempio www oppure mail oppure ftp.
 * Il parametro $subdomain serve appunto a tenere traccia nel valore del subdomain, e reindirizzarlo perso il giusto 
 * servizio.
 */
function casoSubdomainRiservato($subdomain){
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
function casoSubdomainNonInDB($subdomain){
    //REINDIRIZZAMENTO SU pagina 404
    print("subdomain non valido da DB, errore 404");
}

/**
 * Funzione eseguita nel caso in cui il subdomain sia valido sintatticamente, non siano presenti errori nel
 * sottodominio o nel dominio e il sottodomionio quindi la stringa che la cui lo rappresenta viene trovata nel DB, 
 * e quindi è valida.
 * Il reindirizzamento può avvenire in base al parametro $subdomain
 */
function casoSubdomainInDB($subdomain){
    //REINDIRIZZAMENTO SU pagina specifica.
    print("subdomain valido da DB");
    PathRoute::add('/',function(){
        echo "Welcome to marco  :-)";
    });
}



// Run the Router with the given Basepath
//Viene eseguita la ricerca dela routePath corrispondente, e viene effettivamente eseguita la reindirizzazione
PathRoute::run(BASEPATH);
?>