<?php
include_once "Database.php";
/**
 * Class Authentification that serves server side request to confirm login and registration process
 */
class Authentification
{
    //Variabile di accesso al DB
    private $db = null;

    public function __CONSTRUCT()
    {$this->db = new Database();}

    public function makeLogin(string $email,string $pass){

    }

    /**
     * Function that make the login with the parameters user and pass
     * @param $user username in login
     * @param $pass password in login
    */
    function login($email, $pass)
    {
        //Controll if we are not already loged in
        if (!isset($_SESSION["login"]) || empty($_SESSION["login"])) {
            //Traditional login with email and pass to the DB

            //Validdate input credential
            $CEmail = $this->validate($email);
            $CPass = $this->validate($pass);
            if ($CEmail == false || $CPass == false)
                return false;

            //Execute query to DB to solve login request
            $query = "SELECT email,id FROM account WHERE email='" . $CEmail . "' AND pass = '" . $CPass . "'";
            $result = $this->db->query($query);
            //Control on the result of DB to stabil or not the login
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $_SESSION["loginAccount"] = $row["id"];
                $_SESSION["emailAccount"] = $row["email"];

                $_SESSION["nameAccount"] = "";
                $_SESSION["surnameAccount"] = "";
                $_SESSION["birthdateAccount"] = "";
                $_SESSION["genderAccount"] = "";
                $_SESSION["photoAccount"] = "";
                $queryUser="SELECT name,surname,birthdate,sex,photo FROM `user` WHERE user.account = '$row[id]'";
                //Applaying query user
                $result =  $this->db->query($queryUser);
                if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $_SESSION["nameAccount"] = $row["name"];
                $_SESSION["surnameAccount"] = $row["surname"];
                $_SESSION["birthdateAccount"] = $row["birthdate"];
                $_SESSION["genderAccount"] = $row["sex"];
                $_SESSION["photoAccount"] = $row["photo"];
                }
                return true;
            }
        }
        return false;
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
        
        //Return the information about the login
        if (!isset($_SESSION["loginAccount"]) || empty($_SESSION["loginAccount"]))
            return false;
        else
            return $_SESSION["loginAccount"];
    }

    /**
     * Another thing that this function do, is control if we expire the session time, if we expire the limited time to be loged in, we
     * occur to log in another time
     * After 30 minutes 1800 seconds the session exepires
     */
    function sessionTimeExpire()
    {
        //Control of session time
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    function register($email,$pass1,$pass2,$fName,$lName,$bDate,$gender)
    {
        //Verify Parameters
        
        if(!$email=$this->validate($email))
            return false;
        if(!$pass1=$this->validate($pass1))
            return false;
        if(!$pass2=$this->validate($pass2))
            return false;
        if(!$fName=$this->validate($fName))
            return false;
        if(!$lName=$this->validate($lName))
            return false;
        if(!$bDate=$this->validate($bDate))
            return false;
        if(!$gender=$this->validate($gender))
            return false;
        
        ///Registration Process
        //Creating Query Account
        $queryAccount="INSERT INTO `account` (`id`, `email`, `pass`) VALUES (NULL, '$email', '$pass1');";
        //Verify if the email is already existing
        $result = $this->db->query("SELECT email FROM account WHERE email='$email'");
        //print($queryAccount);
        if ($result->num_rows == 0)
        {
            //Applying query account on db
            $res = $this->db->query($queryAccount);
            if($res)//control the succesfully of the querry
            {
                //Take the id of accound collumn
                $accID =  $this->db->query("SELECT id FROM account WHERE email= '$email'")->fetch_assoc()["id"];
                //Creating the User Query with the parameter
                $queryUser="INSERT INTO `user` (`name`, `surname`, `birthdate`, `sex`, `account`, `photo`) VALUES ('$fName', '$lName', '$bDate', '$gender', '$accID', NULL);";
                //Applaying query user
                $res =  $this->db->query($queryUser);
            }
            //Control of succesfully of the querry user
            if($res)
                return true;   //return result of all registration process
        }
        return false;
    }

    function getIfProp($id){
        //Verificare se per questo Id è un Prop
        $ret = false;
        $query = "SELECT * FROM prop WHERE prop.account =".$id."";
        $result = $this->db->query($query);
        if ($result->num_rows == 1) {
            $ret = true;
            $row = $result->fetch_assoc();
            $_SESSION["company"] = $row["company"];
        }
        return $ret;
    }
}

////API Construct

//Start Session
/*
if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}
*/
?>