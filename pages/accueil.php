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

    <script src="../js/ajax.js" defer></script>
    <script src="../js/accueil.js" defer></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="accueil.php"><i class="fa-solid fa-house"></i></a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
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
            <a class="nav-link" href="profil.php">Profil </i><i class="fa-solid fa-user"></i></a>
            </ul>
        </div>
    </nav>

    <div class="container">
    <br>
    <div class="row">
        <div class="col">
            <form>
            <div class="row input-group">
                <div class="col"></div>
                <div class="col-4 d-flex justify-content-center">
                    <div class="form-outline">
                        <input type="text" placeholder="Recherche" class="form-control" />
                    </div>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-4 text-center d-flex align-items-center">
                    <div class="col">
                        <input class="form-check-input" type="radio" name="parqui" checked>
                        <label class="form-check-label">Par Morceaux </label>
                    </div>
                    <div class="col">
                        <input class="form-check-input" type="radio" name="parqui">
                        <label class="form-check-label">Par Albums </label>
                    </div>
                    <div class="col">
                        <input class="form-check-input" type="radio" name="parqui">
                        <label class="form-check-label">Par Artistes </label>
                    </div>
                </div>
                <div class="col"></div>
            </div>
            </form>
        </div>
    </div>
    <div class="row text-center">
        <div class="col">
            <h2>Historique</h2>
        </div>
        <div class="col"></div>
        <div class="col">
            <h2>Playlists</h2>
        </div>
    </div>
    <div class="row">
        <div class="col border border-dark rounded" style="background-color:rgb(222,222,222);">
            
        </div>
        <div class="col d-flex justify-content-center">
            <div class="card" style="width:60%;">
                <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img class="card-img-top" src="https://mdbootstrap.com/wp-content/uploads/2019/02/flam.jpg" alt="Card imagecap">
                    <a href="#!">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </a>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col">
                            <h5><a href="#">Dj Flam</a></h5>
                            <p><a href="#">Urban Bachata remix</a></p>
                            <audio controls preload="metadata" style="width:100%;">
                                <source src="../ressources/Squeezie/Treis Degete/SpaceShip/Spaceship.mp3">
                            </audio>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col d-flex justify-content-start">
                            <button type="button" class="btn btn-outline-danger">
                                <i class="fa-solid fa-heart" style="color:red;"></i>
                                <!--
                                    <i class="fa-regular fa-heart"></i>
                                -->
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-success">
                                <i class="fa-solid fa-info"></i>
                            </button>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-dark">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col border border-dark rounded" style="background-color:rgb(222,222,222);">
            
        </div>
        <div class="row" style="height:40px;">
        </div>
        <div class="row">
            <div class="col">
                <div class="row ">   
                    <div class="col"><h2>Favoris</h2></div>     
                </div>
                <div class="row border border-dark rounded" style="height:150px;background-color:rgb(222,222,222);">        
                    
                </div>
            </div>
        </div>
        <div class="row" style="height:40px;"></div>
    </div>
</body>

</html>