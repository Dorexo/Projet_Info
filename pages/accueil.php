<?php
// Vérification autorisation
session_start();
if (!$_SESSION['connected']) {
    header('Location: ../connection.php');
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Accueil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.php"><i class="fa-solid fa-house"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recherche.php"><i class="fa-solid fa-magnifying-glass"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="playlists.php">Playlists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="favoris.php">Favoris</a>
                    </li>
                </ul>
            </div>
            <a class="nav-link" href="profil.php">Profil</i></a>
            </ul>
        </div>
    </nav>

    <div class="row h-25"></div>
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <div class="col-sm-4 col-sm-offset-4 embed-responsive embed-responsive-4by3">
                <audio controls class="embed-responsive-item">
                    <source src="../ressources/Squeezie/Treis Degete/SpaceShip/Spaceship.mp3">
                </audio>
                <div class="card">
                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                        <img class="card-img-top" src="https://mdbootstrap.com/wp-content/uploads/2019/02/flam.jpg" alt="Card image cap">
                        <a href="#!">
                            <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                        </a>
                    </div>
                    <div class="card-body text-center">
                        <audio id="music">
                            <source src="../ressources/Squeezie/Treis Degete/SpaceShip/Spaceship.mp3">
                        </audio>
                        <div id="audioplayer">
                            <i id="pButton" class="fas fa-play"></i>
                            <div id="timeline">
                                <div id="playhead"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col h-25"></div>
        </div>
        <div class="row"></div>
</body>

</html>