<?php
// Vérification autorisation
session_start();
if (!$_SESSION['connected']) {
    header('Location: ../connexion.php');
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
    <link rel="shortcut icon" href="#">

    <script src="../js/ajax.js" defer></script>
    <script src="../js/script.js" defer></script>
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
                        <a class="nav-link" href="#" onclick="accueil()"><i class="fa-solid fa-house"></i> Accueil</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="#" onclick="recherche()"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="library()"><i class="fa-solid fa-list-music"></i> Playlists</a>
                    </li>
                </ul>
            </div>
            <a class="nav-link" href="#" onclick="profil()" id="id_user" value="<?php echo $_SESSION['id']; ?>">Profil </i><i class="fa-solid fa-user"></i></a>
            </ul>
        </div>
    </nav>
    <div id="divmodal">
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-center" id="modaltitle"></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalbody">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="addMusic">Ajouter</button>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="container" id="page" style="height:82%;">

    </div>
    <footer class="bg-light d-flex align-items-center" style="height:10%;">
        <div class="row" id="music" style="width:100%;">
        
        </div>
    </footer>
</body>

</html>