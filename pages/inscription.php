<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="css/style_connection.css" rel="stylesheet">
        
        <title>Inscription</title>
    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="row h-25"></div>
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-outline mb-4 row">
                        <div class="col">
                            <h2 class="text-center">Inscription</h2>
                        </div>
                    </div>
                    <div class="form-outline mb-4 row">
                        <div class="col">
                            <label class="form-label">Nom</label>
                            <input type="email" name="nom" class="form-control" />
                        </div>
                        <div class="col">
                            <label class="form-label">Prénom</label>
                            <input type="email" name="prenom" class="form-control" />
                        </div>
                    </div>
                    <div class="form-outline mb-4 row">
                        <div class="col">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" id="start" name="trip-start" value="2000-01-01" min="1970-01-01" max="2010-12-31" name="date_naissance" class="form-control" />
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
                            <label class="form-label">Mod de passe</label>
                            <input type="password" name="mdp1" class="form-control" />
                        </div>
                        <div class="col">
                            <label class="form-label">Confirmation mot de passe</label>
                            <input type="password" name="mdp2" class="form-control" />
                        </div>
                    </div>
                    <div class="form-outline mb-4 row text-center">
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block mb-4">S'incrire</button>
                        </div>
                        <div class="col">
                            <a class="btn btn-primary btn-block mb-4" href="connexion.php">Se connecter</a>
                        </div>                    
                    </div>
                </form>
            </div>
            <div class="col h-25"></div>
        </div>
        <div class="row"></div>
    </body>
</html>