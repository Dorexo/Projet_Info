<?php
    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once('../php/database.php');
    $db = database::connexionBD();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="css/style_connection.css" rel="stylesheet">
        
        <title>Connexion</title>
    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="row h-25"></div>
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="row">
                    <div class="col"></div>
                        <div class="col-8">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        <h2 class="text-center">Connexion</h2>
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        <label class="form-label">Adresse email</label>
                                        <input type="email" name="email" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        <label class="form-label">Mot de passe</label>
                                        <input type="password" name="mdp" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-block mb-4" id="connect">Se connecter</button>
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        Si vous n'avez pas de comptes : <a href="inscription.php">S'inscrire</a>
                                    </div>
                                </div>
                            </form>
                            <?php
                                if(isset($_POST["email"]) && isset($_POST["mdp"])){
                                    $user = dbGetUser($db,$_POST["email"],$_POST["mdp"]);
                                    if ($user=="error") {
                                        $ERREUR_mdp = true;
                                    }else {
                                        $_SESSION['id'] = $user[0];
                                        $_SESSION['nom'] = $user[1];
                                        $_SESSION['prenom'] = $user[2];
                                        $_SESSION['connected'] = true;
                                        header('Location: accueil.php');
                                    }
                                }
                            ?>
                        </div>
                    <div class="col"></div>
                </div>
            </div>
            <div class="col h-25"></div>
        </div>
        <div class="row"></div>
    </body>
</html>