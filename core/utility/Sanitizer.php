<?php
/**
 * Classe Sanitizer contenente solo metodi publici statici che servono a Sanitizzare e purificare dei dati 
 * in input, coreggendoli nel caso, le verifiche che fa questa classe sono puramente legate alla codifica e
 * ai caratteri contenuti all'interno dei dati e non nella loro sintissi o semantica
 */
class Sanitizer{

    /**
     * Dato un array di stringhe ne esamina una ad una e ci applica sanitizeString()
     * Parametro opzionale $replace con cui sostituire in caso di not match nel filter
     * @return l'array di stringhe da parametro sanitizzato
     */
    public static function sanitizeArrayString(array $strings, string $replace='_'):array{
        foreach($strings as $index => $string){
            $string = $this->sanitzeString($string,$replace);
            $strings[$index] = $string;
        }
        return $strings;
    }

    /**
     * Funzione che data una stringa ci applica le due funzioni di sanitizzazione
     * sanitizeEscapeString e sanitizeFilterString
     * Parametro opzionale $replace con cui sostituire in caso di not match nel filter
     * @return la stringa da parametro sanitizzata
     */
    public static function sanitzeString(string $string,string $replace='_'):string{
        $string = $this->sanitizeFilterString($string,$replace);
        return $this->sanitizeEscapeString($string);
    }


    /**
     * Funzione che dato un parametro stringa ne esegue la funzione mysql escape string che dovrebbe filtrare la stringa 
     * in vista di un inserimento nel DB
     * @return stringa da parametro sanitizzata con la funzione di escape
     */
    public static function sanitizeEscapeString(string $string):string{
        return mysql_real_escape_string($string);
    }

    /**
     * Funzione che dato un parametro stringa ne controlla i caratteri e li ritorna 
     * se corrispondono nel array di caratteri $admited, in caso contrario li sostituisce col char $replace.
     * @return La stringa di parametro filtrata secondo i caratteri dentro $adminted
     * Parametro opzionale $replace con cui sostituire in caso di not match nel filter
     */
    public static function sanitizeFilterString(string $string, string $replace='_'):string{
        $admited = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $admited .= "0123456789áéíñóúü¿¡ÁÉÍÑÓÚÜè+ùòà,.-é*§ç°;:_[]@#{}€$!/ ";
        if(!empty($string)){
            for ($pos = 0; $pos < strlen($string); $pos++) {
                $char = substr($string, $pos, 1);
                if (strpos($admited, $char) === false){
                    $string = str_replace($char,$replace,$string);
                }
            }
        }
        return $string;
    }
}


?>