<?php
    session_start();
    $_SESSION['id'] = $_SESSION['connected'] = NULL;
    $ERREUR_mdp = false;

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once('php/database.php');
    $db = database::connexionBD();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <title>Connexion</title>
    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body style="background-color:#CBDEFF">
        <div class="row d-flex justify-content-center align-items-center" style="height:15%;">
            <img src="ressources/logo.png" style="width:7em;height:5em">
        </div>
        <div class="row" style="height:10%;"></div>
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <div class="row">
                    <div class="col"></div>
                        <div class="col-8 border border-dark rounded text-center" style="background-color:rgb(222,222,222);">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        <h2 class="text-center" style="color:midnightblue;">Connexion</h2>
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        <label class="form-label" style="color:midnightblue;">Adresse email</label>
                                        <input type="email" name="email" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row">
                                    <div class="col">
                                        <label class="form-label" style="color:midnightblue;">Mot de passe</label>
                                        <input type="password" name="mdp" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-block mb-4" id="connect">Se connecter</button>
                                    </div>
                                </div>
                                <div class="form-outline mb-4 row" style="color:midnightblue;">
                                    <div class="col">
                                        Si vous n'avez pas de compte : <a href="inscription.php">S'inscrire</a>
                                    </div>
                                </div>
                            </form>
                            <?php
                                if(isset($_POST["email"]) && isset($_POST["mdp"])){
                                    $user = dbGetUser($db,$_POST["email"],$_POST["mdp"]);
                                    if ($user=="error") {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        Mot de passe ou email incorrect
                                             </div>";
                                    }else {
                                        $_SESSION['id'] = $user[0];
                                        $_SESSION['connected'] = true;
                                        header('Location: page/index.php');
                                    }
                                }
                            ?>
                        </div>
                    <div class="col"></div>
                </div>
            </div>
            <div class="col"></div>
        </div>
        <div class="row"></div>
    </body>
</html>