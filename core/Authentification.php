<?php
include_once "Database.php";
include_once "Validator.php";
/**
 * Class Authentification that serves server side request to confirm login and registration process
 */
class Authentification
{
    //Variabile di accesso al DB
    private $db = null;

    public function __CONSTRUCT()
    {$this->db = new Database();}

    /**
     * Metodo publico che permette dati due parametri
     * @param $email string email del account con cui fare il login
     * @pass $pass string password con cui verificare l'autentificazione  
     * @return bool esito del login
     */
    public function makeLogin(string $email,string $pass):bool{
        $pass = $this->getHashPassword($pass);//Passoword to Hash
        if (!isset($_SESSION["login"]) || empty($_SESSION["login"])){
            $query = "SELECT email FROM User WHERE email='$email' AND pass='$pass";
            $result = $this->db->query($query);
            if ($result->num_rows == 1){
                $_SESSION["login"] = true;
                $_SESSION["user"] = $email;
                return true;
            }
        }
        return false;
    }

    /**
     * Metodo pubblico che dato un parametro
     * @param $email stringa rapresentante l'identificativo del account User
     * @return bool true se l'account esiste, false altrimenti
     */
    public function existsUser(string $email):bool{
        $query = "SELECT email FROM User WHERE email='$email'";
        $result = $this->db->query($query);
        return ($result->num_rows == 1);
    }

    /**
     * Metodo che dato un parametro
     * @param $pass stringa password in chiaro non criptata
     * @return stringa del hash corrispondente alla password inserita da parametro
     */
    private function getHashPassword(string $pass):string{
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    /**
     * Metodo che si occupa di effettuare la registrazione con un User di tipo Client
     * @param email che verrà verificata semanticamente
     * @param @pass che verra verificata semanticamente e tradotta in Hash
     * @param @name nome del Client
     * @param @surname cognome del Client
     * @return bool contenente informazioni riguardanti esito della registrazione
     */
    public function makeRegistrationClient(string $email,string $pass,string $name, string $surname):bool{
        $pass = $this->getHashPassword($pass);//Passoword to Hash
        if(Validator::valEmail($email))return false;//stop se email non valida sintaticamente
        //Validare PASSWORD

        $querys = array();
        array_push($querys,"INSERT INTO User (email,pass) VALUES ('$email','$pass')");
        array_push($querys,"INSERT INTO Client (user,name,surname) VALUES ('$email','$name','$surname')");
        return $this->db->verifyResults($this->db->transactionQuerys($querys));
    }


    /**
     * Function that returns the variable login, returns if there is a login or not
     * Another thing that this function do, is control if we expire the session time, if we expire the limited time to be loged in, we
     * occur to log in another time
     * @return bool|mixed
     */
    function getIfLogin()
    {
        //Control of session time
        $this->sessionTimeExpire();
        //Se non c'è sessione, o non esiste la variabile di sessine o login == false, quindi siamo non loggati
        //return false, altrimenti siamo loggati e return true.
        return (!isset($_SESSION["login"]) && empty($_SESSION["login"]) && !$_SESSION["login"]);
    }

    /**
     * Metodo che controlla se la sessione è scadura, se la sessione è scaduta fa ripartire il timer e concella 
     * le variabili di sessione, perdendo automaticamente il login
     * After 180 minutes = 10800 seconds the session exepires
     */
    function sessionTimeExpire():void{
        //Control of session time
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 10800)) {
            // last request was expired
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }


}
?>