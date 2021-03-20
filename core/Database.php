<?php


class Database
{
    private $servername = "andreibobirica.duckdns.org";
    private $username = "chethoouser";
    private $password = "passwdHA123";
    private $dbname = "chethoo";

    private $conn = null;

    public function __construct()
    {
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconect();
    }

    public function connect()
    {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function disconect()
    {
        if (isset($this->conn) && !is_null($this->conn))
            $this->conn->close();
    }

    public function query($str)
    {
        if (isset($str) && !empty($str)) {
            $result = $this->conn->query($str);
            return $result;
        }
    }

    public function getConn()
    {
        return $this->conn;
    }

    public function sanitizeString($arrayOfString){
        $admited = "abcdefghijklmnopqrstuvwxyz";
        $admited .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $admited .= "0123456789";
        $admited .= "áéíñóúü¿¡ÁÉÍÑÓÚÜè+ùòà,.-é*§ç°;:_[]@#{}€$!/ ";

        $stringsReturn = array();
        foreach($arrayOfString as $index => $string){
            if(!empty($string)){
                for ($pos = 0; $pos < strlen($string); $pos++) {
                    $car = substr($string, $pos, 1);
                    if (strpos($admited, $car) === false){
                        $string = str_replace($car,' ',$string);
                    }
                }
            }
            $stringsReturn[$index]=$string;
        }
        return $stringsReturn;
    }
}
