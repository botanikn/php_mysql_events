<?php

    function db_cn() {
        $host = "localhost";
        $db = "events";
        $user = "a1";
        $password = "1";

        $cn = mysqli_connect($host, $user, $password, $db) or die("No connection to database");

        return $cn;
    }

?>