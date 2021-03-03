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
 * La gestione del routing dei domini avviene tramite delle funzioni.
 * Il funzionamente della classe PathRoute permette di aggiungere infiniti percorsi di route e solo 
 * in base alla richiesta URI , solo una route viene invocata.
 * Tuttavia su questo applicativo, non verrano aggiunti già da subito tutti i percorsi di route path, ma in base al 
 * indirizzamento del subdomain, si aggiungeranno delle route path.
 * In particolare ci sarà una condizione simil SWITCH, che in base alla tipologia di domain route, creerà delle path Route, 
 * successivamente in base alla URI , il reindirizzamento verrà farro.
 * In questo modo si potranno avere route path uguali ma con differente utilizzo in base al domain path.
 * 
 * Esempio:
 * - admin.chethoo.it
 *      - /         ->>     Pagina Panello di Controllo
 *      - /login    ->>     Pagina lOGIN ADMIN
 * - chethoo.it
 *      - /         ->>     Pagina di ricerca
 *      - /login    ->>     Pagina Login Normale
 * - supercar.chethoo.it
 *      - /         ->>     Pagina del venditore
 *      - /login    ->>     pagina Login Normale
 * 
 * Per Vista si intende una view, una pagina HTML vuota e non popolata, nemmeno con testo dentro
 * Abbinata alla Vista ci sarà un controlle fatto in Javascript che , facendo le opportune richieste, popolerà
 * la Vista, sia con i dati statici, che con i dati dinamini.
 * Si intende per dati statici scritte e descrizioni del sito, si intende per dati dinamici parti di testo prese
 * dal database ed immagini allegate.
 * Durante l'interazione con la pagina, il controller potrà chiedere nuovi dati e popolare nuove parti della view.
 * In caso ci fosse bisogno di inviare dei dati, tramite FORM, oppure panelli di modifica dati:
 * Il processo di visualizzazione, modifica, caricamento, e ri-visualizzazione, sarà fatto dalla stessa VIEW.
 * In pratica ogni view sarà come una piccola web app.
 */
?>