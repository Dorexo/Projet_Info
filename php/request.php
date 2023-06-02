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
    }elseif($requesttype=="accueil"){
        $requesttype = array_shift($requestid);
        if($requesttype=="playlists"){
            if($_SERVER['REQUEST_METHOD']=="GET"){
                $request = dbGetPlaylists($db,$_GET['id_user']);
            }
        }elseif($requesttype=="historique"){
            if($_SERVER['REQUEST_METHOD']=="GET"){
                $request = dbGetHistorique($db,$_GET['id_user']);
            }
        }
    }elseif($requesttype=="music"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = getMusic($db,$_GET['id_musique']);
        }
    }

    echo json_encode($request);
?>