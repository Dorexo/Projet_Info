<?php
    class database {
        static $db = null;
        static function connexionBD() {
            if (self::$db != null) {
                return self::$db;
            }
            require_once ("config.php");
            try {
                self::$db = new PDO('pgsql:host='.DB_SERVER.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PWD);
            }
            catch (PDOException $exception) {
                error_log('Connection error: '.$exception->getMessage());
                return false;
            }
            return self::$db;
        }
    }

    //CONNEXION/INSCRIPTIONS

    function dbGetUser($db,$email,$mdp){
        try{
            $request = 'SELECT * FROM users where email=:email';
            $statement = $db->prepare($request);
            $statement->bindParam(':email', $email);   
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if(!empty($result) && password_verify($mdp,$result['mdp'])){
                return array($result['id_user'],$result['nom'],$result['prenom']);
            }else{
                return "error";
            }
        }catch (PDOException $exception){
            error_log('Request error: '.$exception->getMessage());
            return false;
        }
    } 

    function AlreadyUser($db,$email){
        try{
            $request = 'SELECT * FROM users where email=:email';
            $statement = $db->prepare($request);
            $statement->bindParam(':email', $email);    
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if(empty($user)){
                return false;
            }else{
                return true;
            }
        }catch (PDOException $exception){
            error_log('Request error: '.$exception->getMessage());
            return false;
        }
    }
    function dbInsertNewUser($db,$nom,$prenom,$date_naissance,$email,$mdp){
        try{
            if(!AlreadyUser($db,$email)){
                $hash=password_hash($mdp, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (nom, prenom, date_naissance, email, mdp) VALUES (:nom, :prenom, :date_naissance, :email, :mdp)");
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':date_naissance', $date_naissance);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mdp', $hash);
                $stmt->execute();

                $id = dbGetUser($db,$email,$mdp)[0];
                $date = date("d-m-Y",time());

                $stmt = $db->prepare("INSERT INTO playlists (nom, date_creation, id_user) VALUES ('Favoris', :date_creation, :id_user)");
                $stmt->bindParam(':date_creation', $date);
                $stmt->bindParam(':id_user', $id);
                $stmt->execute();
                $stmt = $db->prepare("INSERT INTO playlists (nom, date_creation, id_user) VALUES ('Historique', :date_creation, :id_user)");
                $stmt->bindParam(':date_creation', $date);
                $stmt->bindParam(':id_user', $id);
                $stmt->execute();
                return true;
            }else{
                return "Already";
            }
        }catch (PDOException $exception){
            error_log('Request error: '.$exception->getMessage());
            return false;
        }
    } 

    // ACCEUIL
    
    function dbGetPlaylists($db,$id_user){
        try{
            $request = "SELECT p.id_playlist, p.nom, p.date_creation, (SELECT count(*) from musique_dans_playlists mdp  WHERE mdp.id_playlist=p.id_playlist) FROM playlists p WHERE p.id_user=:id_user and p.nom!='Favoris' and p.nom!='Historique' ORDER BY UPPER(p.nom)";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);   
            $statement->execute();
            return $statement->fetchall(PDO::FETCH_ASSOC);
        }catch (PDOException $exception){
            error_log('Request error: '.$exception->getMessage());
            return false;
        }
    }

    function dbGetid_favoris($db,$id_user){
        try {
            $request = "SELECT p.id_playlist,p.date_creation,(SELECT count(*) from musique_dans_playlists mdp JOIN playlists pp ON pp.id_playlist=mdp.id_playlist WHERE pp.nom='Favoris' AND pp.id_user= :id_user) FROM playlists p WHERE p.nom='Favoris' AND p.id_user= :id_user";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }


    function deleteHistorique($db,$id_user){
        try {
            $request = "SELECT m.id_musique,m.date_ajout
            FROM musique_dans_playlists m JOIN playlists p ON m.id_playlist = p.id_playlist
            WHERE p.nom = 'Historique' AND p.id_user = :id_user
            ORDER BY date_creation ASC
            LIMIT 3";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_ASSOC);
            $id_musique = $res['id_musique'];
            $date_ajout = $res['date_ajout'];
            $stmt = $db->prepare("DELETE FROM musique_dans_playlists m WHERE m.id_playlist=(SELECT p.id_playlist FROM playlists p WHERE p.nom = 'Historique' AND p.id_user = :id_user ) AND m.id_musique=:id_musique AND m.date_ajout=:date_ajout");
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->bindParam(':date_ajout', $date_ajout);
            $stmt->execute();
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function addHistorique($db, $id_musique, $id_user){
        try {
            $request = "SELECT COUNT(*) FROM musique_dans_playlists m JOIN playlists p ON m.id_playlist=p.id_playlist WHERE p.nom = 'Historique' and p.id_user=:id_user";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();
            $nb = $statement->fetch(PDO::FETCH_ASSOC);
            if($nb['count']>9){
                deleteHistorique($db,$id_user);
            }
            $stmt = $db->prepare("SELECT id_playlist from playlists WHERE id_user=:id_user AND nom='Historique'");
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $id_playlist = $stmt->fetch(PDO::FETCH_ASSOC)['id_playlist'];
            $date_ajout = date("d-m-Y H:i:s",time());
            
            $stmt = $db->prepare("SELECT id_musique from musique_dans_playlists WHERE id_playlist=:id_playlist and id_musique=:id_musique");
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->execute();
            $already = $stmt->fetchall(PDO::FETCH_ASSOC);
            if(empty($already)){
                $stmt = $db->prepare("INSERT INTO musique_dans_playlists (date_ajout, id_playlist, id_musique) VALUES (:date_ajout, :id_playlist, :id_musique)");
                $stmt->bindParam(':date_ajout', $date_ajout);
                $stmt->bindParam(':id_playlist', $id_playlist);
                $stmt->bindParam(':id_musique', $id_musique);
                $stmt->execute();
            }else{
                $stmt = $db->prepare("UPDATE musique_dans_playlists SET date_ajout=:date_ajout WHERE id_playlist=:id_playlist and id_musique=:id_musique");
                $stmt->bindParam(':date_ajout', $date_ajout);
                $stmt->bindParam(':id_playlist', $id_playlist);
                $stmt->bindParam(':id_musique', $id_musique);
                $stmt->execute();
            }
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function ListenMusic($db, $id_musique, $id_user) {
        try {
            $request = 'SELECT m.id_musique, m.titre, m.duree, m.src, a.image, a.nom as "anom", r.nom as "rnom" FROM musiques m JOIN albums a ON m.id_album=a.id_album JOIN artistes r ON a.id_artiste=r.id_artiste WHERE id_musique = :id_musique';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_musique', $id_musique);    
            $statement->execute();
            $result = [$statement->fetch(PDO::FETCH_ASSOC)];
            array_unshift($result,isFavoris($db,$id_musique,$id_user));
            return $result;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetAleaMusique($db){
        try {
            $statement = $db->query('SELECT id_musique FROM musiques ORDER BY random() LIMIT 1');
            return $statement->fetch(PDO::FETCH_ASSOC)['id_musique'];
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetLastMusique($db,$id_user){
        try {
            $request = "SELECT m.id_musique FROM musiques m JOIN musique_dans_playlists mdp ON m.id_musique=mdp.id_musique JOIN playlists p ON p.id_playlist=mdp.id_playlist WHERE id_user = :id_user and p.nom='Historique' ORDER BY mdp.date_ajout DESC";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();            
            return $statement->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    function dbGetHistorique($db, $user_id) {
        try {
            $request = 'SELECT m.id_musique, m.titre, alb.image, alb.nom as "anom", art.nom as "rnom" FROM musiques m
            JOIN musique_dans_playlists d ON m.id_musique = d.id_musique
            JOIN playlists p ON  p.id_playlist = d.id_playlist
            JOIN albums alb ON alb.id_album = m.id_album
            JOIN artistes art ON art.id_artiste = alb.id_artiste
            WHERE p.nom ='." 'Historique' AND p.id_user = :id ORDER BY date_ajout DESC";
            $statement = $db->prepare($request);
            $statement->bindParam(':id', $user_id);    
            $statement->execute();
            $histo = $statement->fetchall(PDO::FETCH_ASSOC);
            return $histo;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }


    //Recherche
    function dbSearchMusiques($db, $search, $id_user) {
        try {
            $request = 'SELECT m.id_musique, a.image, titre, a.nom as "anom", r.nom as "rnom", duree, a.date_parution '."FROM musiques m JOIN albums a ON a.id_album=m.id_album JOIN artistes r ON a.id_artiste=r.id_artiste WHERE titre ILIKE CONCAT('%',:search::text, '%') ORDER BY UPPER(titre)";
            $statement = $db->prepare($request);
            $statement->bindParam(':search', $search);    
            $statement->execute();
            $research = $statement->fetchall(PDO::FETCH_ASSOC);
            $result = [];
            for($i=0;$i<count($research);$i++){
                $arry = [isFavoris($db,$research[$i]['id_musique'],$id_user),$research[$i]];
                array_push($result,$arry);
            }
            return $result;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbSearchAlbums($db, $search) {
        try {
            $request = 'SELECT a.id_album,a.nom as "anom", r.nom as "rnom", a.image, a.date_parution '."FROM albums a JOIN artistes r ON a.id_artiste=r.id_artiste WHERE a.nom ILIKE CONCAT('%',:search::text, '%') ORDER BY UPPER(a.nom)";
            $statement = $db->prepare($request);
            $statement->bindParam(':search', $search);    
            $statement->execute();
            $research = $statement->fetchall(PDO::FETCH_ASSOC);
            return $research;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbSearchArtistes($db, $search) {
        try {
            $request = "SELECT id_artiste, nom, image FROM artistes WHERE nom ILIKE CONCAT('%',:search::text, '%') ORDER BY UPPER(nom)";
            $statement = $db->prepare($request);
            $statement->bindParam(':search', $search);    
            $statement->execute();
            $research = $statement->fetchall(PDO::FETCH_ASSOC);
            return $research;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    function isFavoris($db, $id_musique, $id_user) {
        try {
            $request = "SELECT COUNT(*) AS favs FROM musiques m
            JOIN musique_dans_playlists mp ON m.id_musique = mp.id_musique
            JOIN playlists p ON mp.id_playlist = p.id_playlist
            JOIN users u ON p.id_user = u.id_user
            WHERE u.id_user = :id_user AND p.nom = 'Favoris' AND m.id_musique = :id_musique";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);
            $statement->bindParam(':id_musique', $id_musique);    
            $statement->execute();
            $fav = $statement->fetchall(PDO::FETCH_ASSOC);
            return ($fav[0]['favs'] > 0) ? true : false;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    

    function dbInsertFav($db,$id_musique,$id_user){
        try {
            $stmt = $db->prepare("SELECT id_playlist from playlists WHERE id_user=:id_user AND nom='Favoris'");
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $id_playlist = $stmt->fetch(PDO::FETCH_ASSOC)['id_playlist'];
            $date_ajout = date("d-m-Y H:i:s",time());

            $stmt = $db->prepare("INSERT INTO musique_dans_playlists (date_ajout, id_playlist, id_musique) VALUES (:date_ajout, :id_playlist, :id_musique)");
            $stmt->bindParam(':date_ajout', $date_ajout);
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->execute();
            return true;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbDeleteFav($db,$id_musique,$id_user){
        try {
            $stmt = $db->prepare("SELECT id_playlist from playlists WHERE id_user=:id_user AND nom='Favoris'");
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $id_playlist = $stmt->fetch(PDO::FETCH_ASSOC)['id_playlist'];

            $stmt = $db->prepare("DELETE FROM musique_dans_playlists WHERE id_playlist=:id_playlist AND id_musique=:id_musique");
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->execute();
            return true;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    function dbGetMusiqueOfPlaylist($db,$id_playlist,$id_user){
        try {
            $request = 'SELECT m.id_musique, a.image, titre, a.nom as "anom", r.nom as "rnom", duree, mdp.date_ajout FROM musique_dans_playlists mdp JOIN musiques m ON m.id_musique=mdp.id_musique JOIN albums a ON a.id_album=m.id_album JOIN artistes r ON a.id_artiste=r.id_artiste  WHERE mdp.id_playlist = :id_playlist';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_playlist', $id_playlist);    
            $statement->execute();
            $research = $statement->fetchall(PDO::FETCH_ASSOC);
            $result = [];
            for($i=0;$i<count($research);$i++){
                $arry = [isFavoris($db,$research[$i]['id_musique'],$id_user),$research[$i]];
                array_push($result,$arry);
            }
            return $result;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetNomPlaylist($db,$id_playlist){
        try {
            $request = 'SELECT nom,id_playlist FROM playlists WHERE id_playlist = :id_playlist';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_playlist', $id_playlist);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    // Modal
    function dbGetPlaylistsWhitoutMusique($db,$id_musique,$id_user){
        try {
            $request = "SELECT nom,id_playlist FROM playlists WHERE id_playlist NOT IN (SELECT id_playlist FROM musique_dans_playlists WHERE id_musique = :id_musique) AND nom!='Historique' AND nom!='Favoris' AND id_user=:id_user ORDER BY UPPER(nom)";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_musique', $id_musique);    
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();
            return $statement->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetTitreMusique($db,$id_musique){
        try {
            $request = "SELECT titre,id_musique FROM musiques WHERE id_musique = :id_musique";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_musique', $id_musique);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbInsertMusique($db,$id_musique,$id_playlist){
        try {
            $date_ajout = date("d-m-Y H:i:s",time());
            $stmt = $db->prepare("INSERT INTO musique_dans_playlists (date_ajout, id_playlist, id_musique) VALUES (:date_ajout,:id_playlist, :id_musique)");
            $stmt->bindParam(':date_ajout', $date_ajout);
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->execute();
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbDeleteMusique($db,$id_musique,$id_playlist){
        try {
            $stmt = $db->prepare("DELETE FROM musique_dans_playlists WHERE id_playlist=:id_playlist AND id_musique=:id_musique");
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->execute();
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }


    function AlreadyPlaylist($db,$nom,$id_user){
        try {
            $request = "SELECT nom FROM playlists WHERE id_user = :id_user";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();
            $noms = $statement->fetchall(PDO::FETCH_ASSOC);
            for($i=0;$i<count($noms);$i++){
                if(strtoupper($noms[$i]['nom'])==strtoupper($nom)){
                    return true;
                }
            }
            return false;
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbInsertPlaylist($db,$nom,$id_user){
        try {
            if(!AlreadyPlaylist($db,$nom,$id_user)){
                $date_creation = date("d-m-Y",time());
                $stmt = $db->prepare("INSERT INTO playlists (nom, date_creation, id_user) VALUES (:nom, :date_creation, :id_user)");
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':date_creation', $date_creation);
                $stmt->bindParam(':id_user', $id_user);
                $stmt->execute();
            }
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbDeletePlaylist($db,$id_playlist){
        try {
            $stmt = $db->prepare("DELETE FROM musique_dans_playlists WHERE id_playlist=:id_playlist ");
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->execute();
            $stmt = $db->prepare("DELETE FROM playlists WHERE id_playlist=:id_playlist");
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->execute();
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }


    // Profil
    function dbGetInfoProfil($db,$id_user){
        try {
            $request = "SELECT nom,prenom,date_naissance,email FROM users WHERE id_user = :id_user";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    function dbModifProfil($db,$id_user,$nom,$prenom,$email,$date_naissance,$mdp){
        try {
            if(!AlreadyUser($db,$email)){
                $hash=password_hash($mdp, PASSWORD_DEFAULT);
                $request = "UPDATE users SET nom=:nom, prenom=:prenom, email=:email, date_naissance=:date_naissance, mdp=:mdp WHERE id_user = :id_user";
                $stmt = $db->prepare($request);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':date_naissance', $date_naissance);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mdp', $hash);
                $stmt->bindParam(':id_user', $id_user);
                $stmt->execute();
            }
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    // Detail
    function dbGetDetailMusique($db,$id_musique){
        try {
            $request = 'SELECT m.id_musique,m.titre,m.duree, al.id_album, al.nom as "anom", al.image as "aimage", style_album, ar.id_artiste, type_artiste, ar.nom as "rnom", ar.image as "rimage" FROM musiques m JOIN albums al ON al.id_album=m.id_album JOIN artistes ar ON al.id_artiste=ar.id_artiste JOIN styles s ON s.id_style=al.id_style JOIN types t ON t.id_type=ar.id_type WHERE m.id_musique = :id_musique';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_musique', $id_musique);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    function dbGetMusiqueOfAlbum($db,$id_album){
        try {
            $request = 'SELECT id_musique,al.image,titre FROM musiques m JOIN albums al ON al.id_album=m.id_album WHERE m.id_album = :id_album';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_album', $id_album);    
            $statement->execute();
            return $statement->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetDetailAlbum($db,$id_album){
        try {
            $request = 'SELECT al.id_album,al.date_parution , al.nom as "anom", al.image as "aimage", style_album, ar.id_artiste, type_artiste, ar.nom as "rnom", ar.image as "rimage" FROM albums al JOIN artistes ar ON al.id_artiste=ar.id_artiste JOIN styles s ON s.id_style=al.id_style JOIN types t ON t.id_type=ar.id_type WHERE al.id_album = :id_album';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_album', $id_album);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetAlbumOfArtiste($db,$id_artiste){
        try {
            $request = 'SELECT id_album,image,nom FROM albums WHERE id_artiste = :id_artiste';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_artiste', $id_artiste);    
            $statement->execute();
            return $statement->fetchall(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function dbGetDetailArtiste($db,$id_artiste){
        try {
            $request = 'SELECT ar.id_artiste, type_artiste, ar.nom as "rnom", ar.image as "rimage" FROM  artistes ar JOIN types t ON t.id_type=ar.id_type WHERE ar.id_artiste = :id_artiste';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_artiste', $id_artiste);    
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
?>