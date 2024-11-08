<?php
class Sanitize{
    static function sanitizeString($var){
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
    }
    static function sanitizeMySQL($connection, $var){
        $var = $connection->real_escape_string($_POST[$var]);
        $var = Self::sanitizeString($var);
        return $var;
    }
    static function sanitizeMySQL_POST($connection, $var){
        $var = $connection->real_escape_string($var);
        $var = Self::sanitizeString($var);
        return $var;
    }
}
?>