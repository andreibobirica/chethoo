<?php
/**
 * Classe Database, che se instanziata offre una interfaccia di colelgamento e per interfacciarsi col database.
 * Offre la possibilità di interrogare e manipolare dati nel DB.
 */
class Database
{
    /**
     * Parametri di inizializzazione e configurazione del DB
     */
    private $servername = "andreibobirica.duckdns.org";
    private $username = "chethoouser";
    private $password = "passwdHA123";
    private $dbname = "chethoo";

    //Variabile di connessione al db
    private $conn = null;

    public function __construct()
    {$this->connect();}

    public function __destruct()
    {$this->disconect();}

    public function connect():void
    {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection DB failed: " . $this->conn->connect_error);
        }
    }

    /**
     * Metodo che fa avvenire la disconessione dal DB
     */
    public function disconect():void
    {
        if (isset($this->conn) && !is_null($this->conn))
            $this->conn->close();
    }

    /**
     * Funzione che esegue una query all'iterno del DB data una string SQL come parametro,
     * ne ritorna il risultato
     * @return mixed risultato della querry sotto fomra di array di oggetti
     */
    public function query(string $str):mixed{
        if (isset($str) && !empty($str)) {
            $result = $this->conn->query($str);
            return $result;
        }
    }

    /**
     * Metodo che dato un arry di stringhe sotto forma di SQL le esegue una ad una
     * In caso di errore in una delle Query SQL si blocca e ritorna un booleano 
     * @return true in caso in cui le querry siano state eseguite con successo
     * @return false in caso in cui ci sia stato un qualunque errore.
     * Questo metodo non è in grado di ritornare i valori delle query ed è ideale 
     * per querry di Inserimento e modifica
     */
    public function querys(array $sqls):boolean{
        $failed = false;
        for ($i=0; $i < siezeof($sqls) && !$failed; $i++) {
            $sql = $sqls[$i];
            if (isset($sql) && !empty($sql))
            if(!$this->conn->query($sql))
            $failed = true;
        }
        return !$failed;
    }


    /**
     * Metodo che esegue in un blocco di transazione un array di stringhe di querry SQL;
     * Inizialmente disabilita l'autocommit, successivamente esegue le querry attraverso la funzione querys
     * successivamente esegue il commit.
     * Ne ripristina lo stato di autocommit alla fine.
     * @return esito del inserimento && esito del commit, se true tutto andato bene
     */
    public function transactionQuerys(array $sqls):boolean{
        $this->conn->autocommit(FALSE);
        $notfailed = $this->querys($sqls);
        $notfailed = ($notfailed && !$this->conn->commit());
        $this->conn->autocommit(TRUE);
        return $notfailed;
    }


    /**
     * Metodo che se eseguita dopo un errore di una query ne ritorna il messaggio di errore
     */
    public function verifyQueryError():string{
        return "Error description: ".$this->conn->error;
    }

    /**
     * Metodo Get che ritorna la variabile di connessione al DB
     */
    public function getConn():mixed
    {return $this->conn;}
}
