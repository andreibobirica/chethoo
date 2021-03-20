<?php
/**
 * Classe Validator che fornisce dei metodi pubblici statici che servono a validare dei dati dati in
 * inpur secondo la loro semnatica e sintassi, secondo il loro significato
 */
class Validator{
    /**
     * Metodo che verifica se una email ha lo giusto formato nella sua scrittura
     * @return boolean con esito della verifica
     */
    public static function valEmail(string $email):boolean{
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Metodo che ritorna se la URL ha il giusto formato nella sua scrittura
     * @return boolean con esito della verifica
     */
    public static function valURL(string $url):boolean{
        return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url);
    }

    /**
     * Metodo che ritorna se un nome ha la giusta scrittura, appunto essendo formato solo da lettere normali
     * @return boolean con esito della verifica
     */
    public static function valName(string $name):boolean{
        return preg_match("/^[a-zA-Z-' ]*$/",$name);
    }
}

?>