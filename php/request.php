<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once('database.php');
    $db = database::connexionBD();
  
    $requestid = substr($_SERVER['PATH_INFO'], 1);
    $requestid = explode('/', $requestid);

    if($requestid[0]=="connexion"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
        }
    }

    echo json_encode($request);
?>