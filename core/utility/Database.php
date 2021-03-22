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
        return false;
    }

    /**
     * Metodo che esegue più querry in successione.
     * Data un array di stringhe SQL questo metodo ne esegue la query su
     * ciascuno e ne salva per ciascuno il risultato dentor un array con gli stessi 
     * indici del array $sqls;
     * Nel caso una query sia fallita le altre verrano comunque eseguite.
     * @param array $sqls stringhe rappresentanti querry SQL
     * @return $results array di risulati di querry SQL 
     */
    public function querys(array $sqls):array{
        //array dei risultati delle query
        $results = array();
        foreach($sqls as $i => $sql){
            if (isset($sql) && !empty($sql))
            array_push($results,$this->conn->query($sql));
        }
        return $results;
    }


    /**
     * Metodo che data un array di stringhe, interpretabili come array di querry da eseguire:
     * Questo metodo lo esegue in un blocco di tansazione, se c'è un fallimento, effettua il rollback,
     * Altrimenti effettua il commit.
     * @param array $sqls stringhe rappresentanti querry SQL
     * @return $results array di risulati di querry SQL 
     */
    public function transactionQuerys(array $sqls):array{
        $this->conn->autocommit(FALSE);
        $results = $this->querys($sqls);
        //Se ne esiste almeno una fallita, fa rollback, altrimenti fa commit
        in_array(false,$results) ? $this->conn->rollback() : $this->conn->commit();
        $this->conn->autocommit(TRUE);
        return $results;
    }

    /**
     * Metodo che verifica se nel array di risultati delle query, è presente qualche errore.
     * @param $result array di risultati da query
     * @return bool true se tutti i risultati sono consistenti, false se esiste almeno une errore
     */
    public function verifyResults(array $results):bool{
        return in_array(false,$results);
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

