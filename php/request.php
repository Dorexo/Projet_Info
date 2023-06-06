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
                array_unshift($request,dbGetid_favoris($db,$_GET['id_user']));
            }
        }elseif($requesttype=="historique"){
            if($_SERVER['REQUEST_METHOD']=="GET"){
                $request = dbGetHistorique($db,$_GET['id_user']);
            }
        }
    }elseif($requesttype=="recherche"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if($_GET['who']=="musique"){
                $request = dbSearchMusiques($db,$_GET['search'],$_GET['id_user']);
            }elseif($_GET['who']=="album"){
                $request = dbSearchAlbums($db,$_GET['search']);
            }elseif($_GET['who']=="artiste"){
                $request = dbSearchArtistes($db,$_GET['search']);
            }
            array_unshift($request,$_GET['who']);
        }
    }elseif($requesttype=="favoris"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = dbGetMusiqueOfPlaylist($db,$_GET['id_playlist'],$_GET['id_user']);
            array_unshift($request,dbGetNomPlaylist($db,$_GET['id_playlist']));
        }
    }elseif($requesttype=="music"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = ListenMusic($db,$_GET['id_musique'],$_GET['id_user']);
        }
    }elseif($requesttype=="inserthistorique"){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $request = addHistorique($db,$_POST['id_musique'],$_POST['id_user']);
        }
    }elseif($requesttype=="fav"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            if($_GET['what']=="insert"){
                $request = dbInsertFav($db,$_GET['id_musique'],$_GET['id_user']);
            }elseif($_GET['what']=="delete"){
                $request = dbDeleteFav($db,$_GET['id_musique'],$_GET['id_user']);
            }
        }
    }elseif($requesttype=="favfoot"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = [isFavoris($db,$_GET['id_musique'],$_GET['id_user']),$_GET['id_musique']];
        }
    }elseif($requesttype=="modal"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = [dbGetTitreMusique($db,$_GET['id_musique']),dbGetPlaylistsWhitoutMusique($db,$_GET['id_musique'],$_GET['id_user'])];
        }elseif($_SERVER['REQUEST_METHOD']=="POST"){
            $request = dbInsertMusique($db,$_POST['id_musique'],$_POST['id_playlist']);
        }elseif($_SERVER['REQUEST_METHOD']=="DELETE"){
            $request = dbDeleteMusique($db,$_GET['id_musique'],$_GET['id_playlist']);
        }
    }elseif($requesttype=="playlists"){
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $request = dbInsertPlaylist($db,$_POST['nom_playlist'],$_POST['id_user']);
        }elseif($_SERVER['REQUEST_METHOD']=="DELETE"){
            $request = dbDeletePlaylist($db,$_GET['id_playlist']);
        }
    }elseif($requesttype=="profil"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = dbGetInfoProfil($db,$_GET['id_user']);
        }elseif($_SERVER['REQUEST_METHOD']=="PUT"){
            parse_str(file_get_contents('php://input'), $_PUT);
            $request = dbModifProfil($db,$_PUT['id_user'],$_PUT['nom'],$_PUT['prenom'],$_PUT['email'],$_PUT['date_naissance'],$_PUT['mdp']);
        }
    }elseif($requesttype=="detailM"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = [isFavoris($db,$_GET['id_musique'],$_GET['id_user']),dbGetDetailMusique($db,$_GET['id_musique'])];
        }
    }elseif($requesttype=="detailAl"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = [dbGetDetailAlbum($db,$_GET['id_album']),dbGetMusiqueOfAlbum($db,$_GET['id_album'])];
        }
    }elseif($requesttype=="detailAr"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = [dbGetDetailArtiste($db,$_GET['id_artiste']),dbGetAlbumOfArtiste($db,$_GET['id_artiste'])];
        }
    }elseif($requesttype=="musiqueAlea"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = dbGetAleaMusique($db);
        }
    }elseif($requesttype=="lastMusique"){
        if($_SERVER['REQUEST_METHOD']=="GET"){
            $request = dbGetLastMusique($db,$_GET['id_user']);
        }
    }

    echo json_encode($request);
?>