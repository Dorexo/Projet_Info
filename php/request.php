<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once('database.php');
    $db = database::connexionBD();
  
    $requestid = substr($_SERVER['PATH_INFO'], 1);
    $requestid = explode('/', $requestid);
    $requesttype = array_shift($requestid);

    if($requesttype=="inscription"){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $request = dbInsertNewUser($db,$_POST['nom'],$_POST['prenom'],$_POST['date_naissance'],$_POST['email'],$_POST['mdp']);
        }
    }

    echo json_encode($request);
?>