<?php

    function connection(){
        $dbHost = "localhost";
        $name = "root";
        $password = "";
        $dbname = "inventory_management_project";

        $connect = new mysqli($dbHost, $name, $password, $dbname);

        return $connect;
    }

    function closeConnection($cn){
        $cn->close();
    }

?>