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
                $date = date("Y-m-d",time());

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
            $request = "SELECT p.id_playlist, p.nom, m.image FROM playlists p JOIN musique_dans_playlists c ON c.id_playlist=p.id_playlist JOIN musiques m ON m.id_musique=c.id_musique where p.id_user=:id_user and nom!='Favoris' and nom!='Historique' and m.id_musique=(SELECT id_musique FROM musique_dans_playlists l WHERE p.id_playlist=l.id_playlist LIMIT 1)";
            $statement = $db->prepare($request);
            $statement->bindParam(':id_user', $id_user);   
            $statement->execute();
            return $statement->fetchall(PDO::FETCH_ASSOC);
        }catch (PDOException $exception){
            error_log('Request error: '.$exception->getMessage());
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
            $date_ajout = date("Y-m-d H:i:s",time());
            $stmt = $db->prepare("INSERT INTO musique_dans_playlists (date_ajout, id_playlist, id_musique) VALUES (:date_ajout, :id_playlist, :id_musique)");
            $stmt->bindParam(':date_ajout', $date_ajout);
            $stmt->bindParam(':id_playlist', $id_playlist);
            $stmt->bindParam(':id_musique', $id_musique);
            $stmt->execute();
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }
    function ListenMusic($db, $id_musique, $id_user) {
        try {
            $request = 'SELECT m.titre, m.duree, m.src, m.image, a.nom as "anom", r.nom as "rnom" FROM musiques m JOIN albums a ON m.id_album=a.id_album JOIN artistes r ON a.id_artiste=r.id_artiste WHERE id_musique = :id_musique';
            $statement = $db->prepare($request);
            $statement->bindParam(':id_musique', $id_musique);    
            $statement->execute();
            addHistorique($db,$id_musique, $id_user);
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception){
            error_log('Request error: '. $exception->getMessage());
            return false;
        }
    }

    function dbGetHistorique($db, $user_id) {
        try {
            $request = 'SELECT m.id_musique, m.titre, m.image, alb.nom as "anom", art.nom as "rnom" FROM musiques m
            JOIN musique_dans_playlists d ON m.id_musique = d.id_musique
            JOIN playlists p ON  p.id_playlist = d.id_playlist
            JOIN users u ON u.id_user = p.id_user
            JOIN albums alb ON alb.id_album = m.id_album
            JOIN artistes art ON art.id_artiste = alb.id_artiste
            WHERE p.nom ='." 'Historique' AND u.id_user = :id ORDER BY date_ajout DESC";
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
    function dbSearchMusiques($db, $search) {
        try {
            $request = 'SELECT m.id_musique, m.image, titre, a.nom as "anom", r.nom as "rnom", duree, m.date_parution '."FROM musiques m JOIN albums a ON a.id_album=m.id_album JOIN artistes r ON a.id_artiste=r.id_artiste WHERE titre ILIKE CONCAT('%',:search::text, '%') ORDER BY titre";
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
    function dbSearchAlbums($db, $search) {
        try {
            $request = 'SELECT a.id_album,a.nom as "anom", r.nom as "rnom", a.image, a.date_parution '."FROM albums a JOIN artistes r ON a.id_artiste=r.id_artiste WHERE a.nom ILIKE CONCAT('%',:search::text, '%') ORDER BY a.nom";
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
            $request = "SELECT id_artiste, nom, image FROM artistes WHERE nom ILIKE CONCAT('%',:search::text, '%') ORDER BY nom";
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
?>