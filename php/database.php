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
?>