const page = document.getElementById('page');
const id_user = document.getElementById("id_user").getAttribute("value");
var id_playlist_library;
var id_musique_modal;
var id_musique_detail;
var inaccueil;
var inlibrary;
var inrecherche;
var indetail;
var inmusic = false;

var lastl = -1;
var inlecture = false;
var playlist_lecture = lecture_liste = [];
var last = 1;
var id_music;

//========================================================= Check Favoris =========================================================//
// Parcours tous les boutons fav pour savoir si on doit mettre la musique dans notre playlist favoris
function checkFav(classe,callback){
    for (i = 0; i < classe.length; i++) {
        classe[i].addEventListener("click", function(event){
            icon = event.currentTarget.children[0];
            if(icon.getAttribute("id")=="true"){
                ajaxRequest('GET','../php/request.php/fav?id_musique='+icon.getAttribute("value")+'&what=delete&id_user='+id_user,callback);
            }else{
                ajaxRequest('GET','../php/request.php/fav?id_musique='+icon.getAttribute("value")+'&what=insert&id_user='+id_user,callback);
            }           
        });
    }
}
// Appeler après avoir modifier les favoris et en fonction de l'endroit où l'on est, refresh la page
function refresh(){
    if(inrecherche){
        getRecherche();
    }else if(inlibrary){
        library(id_playlist_library);
    }else if(indetail){
        getDetailMusique();
    }
    if(inmusic){
        icon = document.getElementById("foot").children[0];
        ajaxRequest('GET','../php/request.php/favfoot?id_musique='+icon.getAttribute("value")+'&id_user='+id_user,refreshfavfoot);
    }
}
// Refresh le footer pour les fav
function refreshfavfoot(data){
    bouton = document.getElementById("foot");
    if(data[0]==true){
        inner = '<i class="fa-solid fa-heart" name="coeur" id="true" value="'+data[1]+'"></i>';
    }else{
        inner = '<i class="fa-regular fa-heart" name="coeur" id="false" value="'+data[1]+'"></i>';
    }
    bouton.innerHTML = inner;
}

//========================================================== Check Plus ===========================================================//
// Parcours tt les boutons pour ouvrir le modal avec la liste des playlists attitrée
function checkPlus(classeadd){
    for (i = 0; i < classeadd.length; i++) {
        classeadd[i].addEventListener("click", function(event){
            id_musique_modal = event.currentTarget.getAttribute('value');
            getPlaylistsAndMusique();
        });
    }
}
// Récupère toutes les playlists qui ne contienent pas une musique
function getPlaylistsAndMusique(){
    ajaxRequest('GET','../php/request.php/modal?id_musique='+id_musique_modal+'&id_user='+id_user,printModal);
}
// Affiche le modal avec les infos nécessaires
function printModal(data){
    modalTitle = document.getElementById('modaltitle');
    modalTitle.innerHTML = '<span value="'+data[0]['id_musique']+'" id="id_musique_modal">Ajouter '+data[0]['titre']+' à :</span>';  
    modalText = document.getElementById('modalbody');
    nbpl = data[1].length;
    inner = '';
    if(nbpl>0){
        inner = inner + `<div class="form-outline mb-4">
        <label class="form-label" style="color: midnightblue">Playlists</label>
            <select class="form-control form-select" id="id_playlist_modal">`;
        for(i=0;i<nbpl;i++){
            inner = inner + '<option value="'+data[1][i]['id_playlist']+'">'+data[1][i]['nom']+'</option>';
        }
        inner = inner + `
            </select>
        </div>`;

    }
    modalText.innerHTML = inner;
}
// Raffraichie une fois que la musique fut ajouter
function refreshModal(data){
    getPlaylistsAndMusique();
    if(inlibrary){
        refresh();
    }else if(inaccueil){
        getPlaylists();
    }
}

//========================================================= Check Détails =========================================================//
// Parcours tt les boutons pour envoyer vers la page des détails de la musique
function CheckDetail(classD){
    for (i = 0; i < classD.length; i++) {
        classD[i].addEventListener("click", function(event){
            id_musique_detail = event.currentTarget.getAttribute('value');
            getDetailMusique();
        });
    }
}

//============================================================= Footer ============================================================//
// Récupère la musique avec sa source pour l'écouter et l'insert si on est pas en train d'écouter les musiques précédantes
function getMusique(insert=true){
    inmusic = true;
    if(!inlecture){
        playlist_lecture = [];
        last = 1;
    }
    if(insert){
        ajaxRequest('POST','../php/request.php/inserthistorique',getMusique,'id_musique='+id_music+'&id_user='+id_user);
    }
    ajaxRequest('GET','../php/request.php/music?id_musique='+id_music+'&id_user='+id_user,printMusique);
}
// Affiche la musique et toutes les infos en la jouant dans le footer 
function printMusique(data){
    inner = `
        <div class="col d-flex align-items-center ms-3">
            <img class="img-fluid rounded" src="`+data[1]['image']+`" style="width:3em; heigth:3em;">
        </div>
        <div class="col-1 d-flex align-items-center ms-3">
                <div class="col-8"></div>
                <div class="col d-flex align-items-center justify-content-start">
                    <a href="#" style="color:black;" onclick="precedant()"><i class="fa-solid fa-backward-step"></i></a>
                </div>
                <div class="col d-flex align-items-center justify-content-end ms-4">
                    <a href="#" style="color:black;" onclick="suivant()"><i class="fa-solid fa-forward-step"></i></a>
                </div>
                <div class="col-1"></div>            
        </div>
        <div class="col-4 d-flex justify-content-center">
            <audio controls style="width:100%;" autoplay id="audioplay">
                <source src="`+data[1]['src']+`">
            </audio>
        </div>
        <div class="col" >
            <b>`+data[1]['titre']+`</b> par <i>`+data[1]['rnom']+`</i><br>
            `+data[1]['anom']+`
        </div>
        <div class="col d-flex align-items-center justify-content-end">
            <div class="row">
            <div class="col d-flex justify-content-start">
                <button type="button" class="btn btn-outline-danger favfoot" id="foot">`;
    if(data[0]==true){
        inner = inner + '<i class="fa-solid fa-heart" id="true" value="'+data[1]['id_musique']+'"></i>';
    }else{
        inner = inner + '<i class="fa-regular fa-heart" id="false" value="'+data[1]['id_musique']+'"></i>';
    }
    inner = inner +`
                </button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-success detailf" value="`+data[1]['id_musique']+`">
                    <i class="fa-solid fa-info"></i>
                </button>
            </div>
            <div class="col d-flex justify-content-end">
                <button type="button" class="btn btn-outline-dark modalclass" data-bs-toggle="modal" data-bs-target="#myModal" value="`+data[1]['id_musique']+`">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            </div>
        </div> `; 
    document.getElementById("music").innerHTML = inner;
    if(inaccueil){
        // Actualise l'historique   
        getHistorique();
    }
    // Vérifie si l'on appuie sur le bouton d'insertion dans une playlist
    buttons = document.getElementsByClassName('modalclass');
    checkPlus(buttons);
    // Vérifie si l'on appuie sur le bouton de fav
    classe = document.getElementsByClassName("favfoot");
    checkFav(classe,refresh);
    // Vérifie si l'on appuie sur le bouton des détails d'une musique
    classD = document.getElementsByClassName("detailf");
    CheckDetail(classD);
    // Passe à la musique suivante à la fin de l'audio
    document.getElementById("audioplay").addEventListener("ended", function(){
        suivant();
    });
}

// Récupère une musique aléatoire
function getALea(){
    ajaxRequest('GET','../php/request.php/musiqueAlea',setIdMusicAlea);
}
// Récupère les dernières musiques
function getLast(){
    ajaxRequest('GET','../php/request.php/lastMusique?id_user='+id_user,setIdMusicLast);
}
// Met une musique aléatoire
function setIdMusicAlea(data){
    if(data){
        id_music = data;
        inlecture = false;
        getMusique();
    }
}
// Met la dernière musique
function setIdMusicLast(data){
    if(data){
        inlecture = true;
        playlist_lecture.push(id_music);
        if(!data[last]){
            id_music = playlist_lecture[last-1];
        }else{
            console.log(data,last);
            id_music = data[last]['id_musique'];
            last = last+1;
        }
        getMusique(false);
    }
}
// Met la prochaine musique qu'elle soit aléatoire ou dans une playlist/album
function suivant(lecture=false){
    if(playlist_lecture.length==0){
        last = 1;
        inlecture = false;
        getALea();
    }else{
        last = last-1
        id_music = playlist_lecture.pop();
        console.log(lecture_liste);
        if(lecture_liste.includes(id_music)){
            lecture = true;
            t = []
            for(i=0;i<lecture_liste.length;i++){
                if(lecture_liste[i]!=id_music){
                    t.push(lecture_liste[i]);
                }
            }
            lecture_liste = t;
        }
        getMusique(lecture);
    }
}
// Récupère la musique précédante
function precedant(){
    getLast();
}
// Ecoute une playlist
function ListenPlaylist(data){
    if(data.length>1){
        playlist_lecture = [];
        for(i=1;i<data.length;i++){
            playlist_lecture.unshift(data[i][1]['id_musique']);
            lecture_liste.unshift(data[i][1]['id_musique']);
        }
        last = playlist_lecture.length;
        inlecture = true;
        suivant(true);
    }
}
// Ecoute un album
function ListenAlbum(data){
    data = data[1];
    console.log(data);
    if(data.length>1){
        playlist_lecture = [];
        for(i=0;i<data.length;i++){
            playlist_lecture.unshift(data[i]['id_musique']);
            lecture_liste.unshift(data[i]['id_musique']);
        }
        last = playlist_lecture.length;
        inlecture = true;
        suivant(true);
    }
}

//============================================================ Accueil ============================================================//
// Récupère l'historique
function getHistorique(){
    ajaxRequest('GET','../php/request.php/accueil/historique?id_user='+id_user,printHistorique);
}
// Affiche l'historique
function printHistorique(data){
    nbhisto = data.length
    liste = document.getElementById("historique_accueil");
    inner =""
    for(i=0;i<nbhisto;i+=2){
        inner = inner + '<div class="row"><div class="col mt-3"><button class="btn btn-secondary musique" value="'+data[i]['id_musique']+'" style="width:70%; height:70%;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button><b><p style="color: midnightblue">'+data[i]['titre']+'</b><br><i>'+data[i]['anom']+'</i><br>'+data[i]['rnom']+'</p></div>'
        if(i+1<nbhisto){
            inner = inner + '<div class="col mt-3"><button class="btn btn-secondary musique" value="'+data[i+1]['id_musique']+'" style="width:70%; height:70%;"><img class="img-fluid rounded" src="'+data[i+1]['image']+'" ></button><p style="color: midnightblue"><b>'+data[i+1]['titre']+'</b><br><i>'+data[i+1]['anom']+'</i><br>'+data[i+1]['rnom']+'</p></div></div>'
        }else{
            inner = inner + '<div class="col mt-3"></div></div>'
        }
        liste.innerHTML= inner;
    }

    playlists = document.getElementsByClassName("musique")
    for (i = 0; i < playlists.length; i++) {
        playlists[i].addEventListener("click", function(event){
            id_music = event.currentTarget.value;
            inlecture = false;
            getMusique();
        });
    }
}
// Récupère les playlsits
function getPlaylists(){
    ajaxRequest('GET','../php/request.php/accueil/playlists?id_user='+id_user,printPlaylists);
}
// Affiche les playlsits dont celle des favoris
function printPlaylists(data){
    nbplaylist = data.length
    
    liste = document.getElementById("playlists_accueil");
    inner = '<div class="row"><div class="col mt-3"><button class="btn btn-secondary play" value="'+data[0]['id_playlist']+'"style="width:11em; height:11em;"><img class="img-fluid rounded" src="../ressources/Playlists/favoris.png" ></button><p><b style="color: midnightblue">Favoris</b></p></div>';
    if(nbplaylist>1){
        inner = inner + '<div class="col mt-3"><button class="btn btn-secondary play" value="'+data[1]['id_playlist']+'" style="width:11em; height:11em;"><img class="img-fluid rounded" src="../ressources/Playlists/playlists.png" ></button><p><b style="color: midnightblue">'+data[1]['nom']+'</b></p></div></div>';
        nbplaylist--;
        for(i=2;i<nbplaylist+1;i+=2){
            inner = inner + '<div class="row"><div class="col mt-3"><button class="btn btn-secondary play" value="'+data[i]['id_playlist']+'" style="width:11em; height:11em;"><img class="img-fluid rounded" src="../ressources/Playlists/playlists.png" ></button><p><b style="color: midnightblue">'+data[i]['nom']+'</b></p></div>'
            if(i+1<nbplaylist+1){
                inner = inner + '<div class="col mt-3"><button class="btn btn-secondary play" value="'+data[i+1]['id_playlist']+'" style="width:11em; height:11em;"><img class="img-fluid rounded" src="../ressources/Playlists/playlists.png" ></button><p><b style="color: midnightblue">'+data[i+1]['nom']+'</b></p></div></div>'
            }else{
                inner = inner + '<div class="col mt-3"></div></div>'
            }
        }
    }else{
        inner = inner + '<div class="col mt-3"></div></div>'
    }
    liste.innerHTML= inner;
    // Vérifie si l'on veut des info sur les playlists
    playlists = document.getElementsByClassName("play")
    for (i = 0; i < playlists.length; i++) {
        playlists[i].addEventListener("click", function(event){
            id_playlist_library = event.currentTarget.getAttribute("value");
            library(id_playlist_library);
        });
    }
  
}
// Affiche la page d'accueil
function accueil(){
    inaccueil = true;
    inlibrary = inrecherche = indetail = false;
    page.innerHTML = `
    <br>
    <div class="row">
        <div class="col">
        <form>
        <div class="row input-group" style="height:8%">
            <div class="col"></div>
            <div class="col-4 d-flex justify-content-center">
                <div class="form-outline">
                    <input type="text" placeholder="Recherche" class="form-control" id="recherche"/>
                </div>
                <button type="button" class="btn btn-primary" id="recherche_submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="col-4 text-center d-flex align-items-center" style="color:midnightblue">
                <div class="col">
                    <input class="form-check-input" type="radio" name="parqui" value="musique" checked>
                    <label class="form-check-label">Par Morceaux </label>
                </div>
                <div class="col">
                    <input class="form-check-input" type="radio" name="parqui" value="album">
                    <label class="form-check-label">Par Albums </label>
                </div>
                <div class="col">
                    <input class="form-check-input" type="radio" name="parqui" value="artiste">
                    <label class="form-check-label">Par Artistes </label>
                </div>
            </div>
            <div class="col"></div>
        </div>
        </form>
        </div>
    </div>
    <br>
    <div class="row text-center" style="height:8%">
        <div class="col-5">
            <h2 style="color: midnightblue">10 dernières musiques écoutées</h2>
        </div>
        <div class="col"></div>
        <div class="col-5">
            <h2 style="color: midnightblue">Playlists</h2>
        </div>
    </div>
    <div class="row" style="height:70%">
        <div class="col-5 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:45%; height:100%;">
        <div id="historique_accueil">
            
        </div>
        </div>
        <div class="col"></div>
        <div class="col-5 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:45%; height:100%;">
            <div id="playlists_accueil">

            </div>
        </div>
    </div>
    <div class="row" style="height:4%"></div>`;
    getPlaylists();
    getHistorique();
    // Vérifie si l'on appuyer sur la barre de recherche
    document.getElementById("recherche_submit").addEventListener("click", function(event){
        event.preventDefault();
        search = document.getElementById('recherche').value;
        radios = document.getElementsByName("parqui");
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                recherche();
                ajaxRequest('GET','../php/request.php/recherche?search='+search+'&who='+radios[i].value+'&id_user='+id_user,printRecherche);
                break;
            }
        }
    }); 
}


//=========================================================== Recherche ===========================================================//
// Récupère les infos des recherches en fonction des filtres
function getRecherche(){
    search = document.getElementById('recherche').value;
    radios = document.getElementsByName("parqui");
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            ajaxRequest('GET','../php/request.php/recherche?search='+search+'&who='+radios[i].value+'&id_user='+id_user,printRecherche);
            break;
        }
    }
}
// Affiche les résultats des recherches
function printRecherche(data){
    nbresult = data.length
    liste = document.getElementById("recherche_page");
    inner ='<table class="table text-center align-middle" style="color: midnightblue">';
    if(data[0]=="musique"){
        inner = inner + '<thead><tr><th style="width:10%"></th><th>Titre</th><th>Album</th><th>Artiste</th><th>Durée</th><th>Date de parution</th><th></th></thead><tbody>'
        for(i=1;i<nbresult;i++){
            inner = inner + '<tr><td><button class="btn btn-secondary musique" value="'+data[i][1]['id_musique']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i][1]['image']+'" ></button></td><td>'+data[i][1]['titre']+'</td><td>'+data[i][1]['anom']+'</td><td>'+data[i][1]['rnom']+'</td><td>'+data[i][1]['duree']+'</td><td>'+data[i][1]['date_parution']+'</td><td style="width:20%">'+`
            <div class="col d-flex align-items-end">
                <div class="row">
                    <div class="col d-flex justify-content-start">
                        <button type="button" class="btn btn-outline-danger favrech">`;
            if(data[i][0]==true){
                inner = inner + '<i class="fa-solid fa-heart" id="true" value="'+data[i][1]['id_musique']+'"></i>';
            }else{
                inner = inner + '<i class="fa-regular fa-heart" id="false" value="'+data[i][1]['id_musique']+'"></i>';
            }
            inner = inner +`
                        </button>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-outline-success detailr" value="`+data[i][1]['id_musique']+`">
                            <i class="fa-solid fa-info"></i>
                        </button>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-dark modalclassr" data-bs-toggle="modal" data-bs-target="#myModal" value="`+data[i][1]['id_musique']+`">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div></td></tr>`
        }
    }else if(data[0]=="album"){
        inner = inner + "<thead><tr><th style='width:10%'></th><th>Nom de l'album</th><th>Artiste</th><th>Date de parution</th></thead><tbody>"
        for(i=1;i<nbresult;i++){
            inner = inner + '<tr><td><button class="btn btn-secondary album" value="'+data[i]['id_album']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button></td><td>'+data[i]['anom']+'</td><td>'+data[i]['rnom']+'</td><td>'+data[i]['date_parution']+'</td></tr>'
        }
    }else if(data[0]=="artiste"){
        inner = inner + "<thead><tr><th style='width:10%'></th><th>Nom de l'artiste</th></thead><tbody>"
        for(i=1;i<nbresult;i++){
            inner = inner + '<tr><td><button class="btn btn-secondary artiste" value="'+data[i]['id_artiste']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button></td><td>'+data[i]['nom']+'</td></tr>'
        }
    }
    liste.innerHTML= inner + '</tbody></table>';
    if(data[0]=="musique"){
        classe = document.getElementsByClassName("favrech")
        checkFav(classe,refresh);
        classeadd = document.getElementsByClassName("modalclassr");
        checkPlus(classeadd);
        classD = document.getElementsByClassName("detailr");
        CheckDetail(classD);
        playlists = document.getElementsByClassName("musique")
        for (i = 0; i < playlists.length; i++) {
            playlists[i].addEventListener("click", function(event){
                id_music = event.currentTarget.value;
                inlecture = false;
                getMusique();
            });
        }  
    }else if(data[0]=='album'){
        albums = document.getElementsByClassName("album")
        for (i = 0; i < albums.length; i++) {
            albums[i].addEventListener("click", function(event){
                id = event.currentTarget.value;
                getDetailAlbum(id);
            });
        }
    }else if(data[0]=='artiste'){
        artistes = document.getElementsByClassName("artiste")
        for (i = 0; i < artistes.length; i++) {
            artistes[i].addEventListener("click", function(event){
                id = event.currentTarget.value;
                getDetailArtiste(id);
            });
        }
    }
}
// Affiche la page Recherche
function recherche(){
    inrecherche= true;
    inlibrary = inaccueil = indetail = false;
    page.innerHTML = `
    <br>
    <div class="row">
        <div class="col">
            <div class="row input-group" style="height:8%">
                <div class="col"></div>
                <div class="col-4 d-flex justify-content-center">
                    <div class="form-outline">
                        <input type="text" placeholder="Recherche" class="form-control" id="recherche"/>
                    </div>
                    <button type="button" class="btn btn-primary recherche_submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="col-4 text-center d-flex align-items-center" style="color: midnightblue">
                    <div class="col">
                        <input class="form-check-input recherche_submit" type="radio" name="parqui" value="musique" checked>
                        <label class="form-check-label">Par Morceaux </label>
                    </div>
                    <div class="col">
                        <input class="form-check-input recherche_submit" type="radio" name="parqui" value="album">
                        <label class="form-check-label">Par Albums </label>
                    </div>
                    <div class="col">
                        <input class="form-check-input recherche_submit" type="radio" name="parqui" value="artiste">
                        <label class="form-check-label">Par Artistes </label>
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>
    </div>
    <br>
    <div class="row" style="height:78%">
        <div class="col"></div>
        <div class="col-8 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:80%; height:100%;">
        <div id="recherche_page">
        
        </div>
        </div>
        <div class="col"></div>
    </div>
    <div class="row" style="height:4%"></div>`;
    // Vérifie si l'on appuie sur les boutons de recherche
    document.getElementById("recherche").addEventListener("input", function(){
        getRecherche();
    });
    rsubmit = document.getElementsByClassName("recherche_submit");
    for (i = 0; i < rsubmit.length; i++) {
        rsubmit[i].addEventListener("click", function(){
            getRecherche();
        });
    }
}

//=========================================================== Playlists ===========================================================//
// Récupères toutes les playlists d'un user
function getPlaylistsLibrary(){
    ajaxRequest('GET','../php/request.php/accueil/playlists?id_user='+id_user,printPlaylistsLibrary);
}
// Affiche toutes les playlists dont celle des favoris
function printPlaylistsLibrary(data){
    nbplaylistl = data.length;
    liste = document.getElementById("liste_playlists");
    inner ='<table class="table text-center align-middle" style="color: midnightblue"><thead><tr><th></th><th>Date de creation</th><th>Nb de musiques</th><th></th></thead><tbody><tr><td><button class="btn btn-secondary play" value="'+data[0]['id_playlist']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="../ressources/Playlists/favoris.png" ></button><p>Favoris</p></td><td>'+data[0]['date_creation']+'<br><br><br></td><td>'+data[0]['count']+'<br><br><br></td><td></td></tr>'
    for(i=1;i<nbplaylistl;i++){
        inner = inner + '<tr><td><button class="btn btn-secondary play" value="'+data[i]['id_playlist']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="../ressources/Playlists/playlists.png" ></button><p>'+data[i]['nom']+'</p></td><td>'+data[i]['date_creation']+'<br><br><br></td><td>'+data[i]['count']+'<br><br><br></td><td>'+`
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-outline-primary playsup" value="`+data[i]['id_playlist']+`">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div><br><br>
        </td></tr>`
    }
    liste.innerHTML = inner + '</tbody></table>';
    // Vérifie si l'on veut les musique d'une playlist
    playlists = document.getElementsByClassName("play")
    for (i = 0; i < playlists.length; i++) {
        playlists[i].addEventListener("click", function(event){
            id_playlist_library = event.currentTarget.getAttribute("value");
            getMusiqueLibrary();
        });
    }
    // Vérifie si l'on veut supprimer une playlist
    playlistssup = document.getElementsByClassName("playsup")
    for (i = 0; i < playlistssup.length; i++) {
        playlistssup[i].addEventListener("click", function(event){
            id_playlist_supp = event.currentTarget.getAttribute('value');
            ajaxRequest('DELETE','../php/request.php/playlists?id_playlist='+id_playlist_supp,getPlaylistsLibrary);
        });
    }
}
// Récupère les musique d'une playlist
function getMusiqueLibrary(){
    ajaxRequest('GET','../php/request.php/favoris?id_user='+id_user+'&id_playlist='+id_playlist_library,printMusiquesLibrary);
}
// Affiche les musiques d'une playlist
function printMusiquesLibrary(data){
    titre = document.getElementById("titre_playlist");
    titre.innerHTML = "<div class='row'><div class='col'></div><div class='col-8'><h2 style='color: midnightblue'>Liste des musiques de " + data[0]['nom'] +"</h2></div><div class='col d-flex align-items-center'><button type='button' class='btn btn-success' id='playplaylist' value='" + data[0]['id_playlist'] +"'><i class='fa-solid fa-play'></i></button></div>";

    nbm = data.length
    liste = document.getElementById("liste_musiques");
    inner ='<div class=" border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:100%; height:100%;"><table class="table text-center align-middle" style="color: midnightblue"><thead><tr><th style="width:5%"></th><th style="width:20%">Titre</th><th style="width:10%">Artiste</th><th style="width:4%">Durée</th><th style="width:12%">Date '+"d'ajout</th><th style='width:22%'></th><th style='width:8%'></th></thead><tbody>";
    for(i=1;i<nbm;i++){
        inner = inner + '<tr><td><button class="btn btn-secondary musique" value="'+data[i][1]['id_musique']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i][1]['image']+'" ></button></td><td><b>'+data[i][1]['titre']+'</b><br>'+data[i][1]['anom']+'</td><td>'+data[i][1]['rnom']+'</td><td>'+data[i][1]['duree']+'</td><td>'+data[i][1]['date_ajout']+'</td><td>'+`
        <div class="col d-flex align-items-center">
            <div class="row">
                <div class="col d-flex justify-content-start">
                    <button type="button" class="btn btn-outline-danger favlab">`;
        if(data[i][0]==true){
            inner = inner + '<i class="fa-solid fa-heart" id="true" value="'+data[i][1]['id_musique']+'"></i>';
        }else{
            inner = inner + '<i class="fa-regular fa-heart" id="false" value="'+data[i][1]['id_musique']+'"></i>';
        }
        inner = inner +`
                    </button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-outline-success detaill" value="`+data[i][1]['id_musique']+`">
                        <i class="fa-solid fa-info"></i>
                    </button>
                </div>
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-dark modalclassp" data-bs-toggle="modal" data-bs-target="#myModal" value="`+data[i][1]['id_musique']+`">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
        </div></td><td>
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-outline-primary supclass" value="`+data[i][1]['id_musique']+`">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </td></tr>`
    }
    liste.innerHTML= inner + '</tbody></table></div>';
    classe = document.getElementsByClassName("favlab");
    checkFav(classe,refresh);
    classeadd = document.getElementsByClassName("modalclassp");
    checkPlus(classeadd);
    classD = document.getElementsByClassName("detaill");
    CheckDetail(classD);
    musiques = document.getElementsByClassName("musique");
    for (i = 0; i < musiques.length; i++) {
        musiques[i].addEventListener("click", function(event){
            id_music = event.currentTarget.value;
            inlecture = false;
            getMusique();
        });
    }
    // Vérifie si l'on veut enlevé une musique d'une playlist 
    buttonssupp = document.getElementsByClassName('supclass');
    for (i = 0; i < buttonssupp.length; i++) {
        buttonssupp[i].addEventListener("click", function(event){
            id_musique_supp = event.currentTarget.getAttribute('value');
            ajaxRequest('DELETE','../php/request.php/modal?id_musique='+id_musique_supp+'&id_playlist='+id_playlist_library,refreshModal);
        });
    }
    // Vérifie si l'on veut jouer une playlist
    document.getElementById("playplaylist").addEventListener("click", function(event){
        id_playlist = event.currentTarget.getAttribute('value');
        ajaxRequest('GET','../php/request.php/favoris?id_user='+id_user+'&id_playlist='+id_playlist,ListenPlaylist);
    });
}
// Affiche la page des playlists
function library(id=-1){
    inlibrary = true;
    inrecherche = inaccueil = indetail = false;
    page.innerHTML = `
    <br>
    <div class="row">
        <div class="col-4">
            <form>
            <div class="row input-group" style="height:8%">
                <div class="col">
                    <div class="form-group">
                        <label style="color:midnightblue">Nouvelle playlist</label>
                        <input type="text" class="form-control" placeholder="Nom playlist" id="nom_playlist">
                    </div>
                </div>
                <div class="col-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary" id="nvplaylist">Créer</button>
                </div>
            </div>
            </form>
        </div>
        <div class="col"></div>
    </div>
    <br>
    <div class="row">
        <div class="col-3 text-center" style="width:30%; height:100%;">
            <h2 style="color:midnightblue">Liste des playlists</h2>
        </div>
        <div class="col-1"></div>
        <div class="col text-center" id="titre_playlist">
            
        </div>
    </div>
    <div class="row" style="height:66%">
        <div class="col-3 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:30%; height:100%;">
            <div id="liste_playlists">
            
            </div>
        </div>
        <div class="col-1"></div>
        <div class="col">
            <div id="liste_musiques">
                
            </div>
        </div>
    </div>
    <div class="row" style="height:4%"></div>`;
    getPlaylistsLibrary();
    if(id!=-1){
        id_playlist_library = id;
        getMusiqueLibrary();
    }
    document.getElementById("nvplaylist").addEventListener("click", function(event){
        event.preventDefault();
        nom_playlist = document.getElementById("nom_playlist").value;
        document.getElementById("nom_playlist").value = "";
        if(nom_playlist!=""){
            ajaxRequest('POST','../php/request.php/playlists',getPlaylistsLibrary,'nom_playlist='+nom_playlist+'&id_user='+id_user);
        }
    });
}

//============================================================ Profil =============================================================//
// Récupère les infos du profil
function getProfil(){
    ajaxRequest('GET','../php/request.php/profil?id_user='+id_user,printProfil);
}
// Affiche les infos du profil
function printProfil(data){
    date_naissance = new Date(data['date_naissance']);
    dateage = new Date(Date.now() - date_naissance.getTime());
    age = dateage.getUTCFullYear() - 1970;
    document.getElementById("info_profil").innerHTML = `
    <br>
    <form>
        <div class="form-outline mb-4 row">
            <div class="col">
                <div class="form-group">
                    <label style="color:midnightblue">Nom</label>
                    <input type="text" class="form-control" value="`+data['nom']+`" id="nom">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label style="color:midnightblue">Prenom</label>
                    <input type="text" class="form-control" value="`+data['prenom']+`" id="prenom">
                </div>
            </div>
        </div>
        <div class="form-outline mb-4 row">
            <div class="col">
                <div class="form-group">
                    <label style="color:midnightblue">Email</label>
                    <input type="email" class="form-control" value="`+data['email']+`" id="email">
                </div>
            </div>
        </div>
        <div class="form-outline mb-4 row">
            <div class="col">
                <div class="form-group">
                    <label style="color:midnightblue">Date de naissance</label>
                    <input type="date" id="date_naissance" value="`+data['date_naissance']+`" min="1970-01-01" max="2010-12-31" id="date_naissance" class="form-control" />
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label style="color:midnightblue">Âge</label>
                    <input type="text" value="`+age+` ans" class="form-control" disabled/>
                </div>
            </div>
        </div>
        <div class="form-outline mb-4 row">
            <div class="col">
                <label class="form-label" style="color:midnightblue">Nouveau mot de passe</label>
                <input type="password" id="mdp1" class="form-control" />
            </div>
            <div class="col">
                <label class="form-label" style="color:midnightblue">Confirmation mot de passe</label>
                <input type="password" id="mdp2" class="form-control" />
            </div>
        </div>
        <div id="succes"></div>
        <section id="errors" class="container alert alert-danger d-none">
        </section>
        <div class="form-outline mb-4 row text-center">
            <div class="col">
                <button type="submit" id="modifier" class="btn btn-primary btn-block mb-4" style="width:50%;">Modifier</button>
            </div>
        </div>
    </form>
    `;
    // Modifie les infos
    boutonmodif = document.getElementById("modifier");
    boutonmodif.addEventListener("click", function(event){
        event.preventDefault();
        nom = document.getElementById("nom").value;
        prenom = document.getElementById("prenom").value;
        email = document.getElementById("email").value;
        date_naissance = document.getElementById("date_naissance").value;
        mdp1 = document.getElementById("mdp1").value;
        mdp2 = document.getElementById("mdp2").value;
        if(nom!="" && prenom!="" && date_naissance!="" && email!="" && mdp1!=""){
            if(email.includes('@') && email[email.length-1]!='@'){
                if(mdp1!=mdp2){
                    document.getElementById("errors").style.display = "block";
                    document.getElementById("errors").innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> '+'Mot de passe incorrect';
                    document.getElementById("errors").classList.remove("d-none");
                }else{
                    request = '&id_user='+id_user+'&nom='+nom+'&prenom='+prenom+'&date_naissance='+date_naissance+'&email='+email+'&mdp='+mdp1;
                    console.log(request)
                    ajaxRequest('PUT','../php/request.php/profil',accueil,request);
                }
            }   
        }
    });
}
// Affiche la page du profil
function profil(){
    inrecherche = inaccueil = inlibrary = indetail = false;
    page.innerHTML = `
    <br>
    <h1 class="text-center" style="color: midnightblue">Profil</h1>
    <div class="row" style="height:78%;">
        <div class="col"></div>
        <div class="col-4" id="info_profil"></div>
        <div class="col-2"></div>
        <div class="col-4 text-center">
            <div class="row" style="height:35%;"></div>
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-success" id="nettoyer" style="width:50%;">Nettoyer l'historique</button>
                </div>
            </div>
            <div class="row" style="height:5%;"></div>
            <div class="row">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary" id="deconnect" style="width:50%;">DECONNEXION</button>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
    <div class="row" style="height:4%"></div>`;
    // Vérifie si l'on veut se déconnecter
    boutondeconect = document.getElementById("deconnect");
    boutondeconect.addEventListener("click", function(){
        document.location.href="../connexion.php"; 
    });
    // Vérifie si l'on veut nettoyer l'historique
    boutonnettoyer = document.getElementById("nettoyer");
    boutonnettoyer.addEventListener("click", function(){
        ajaxRequest('DELETE','../php/request.php/nettoyer?id_user='+id_user,accueil);
    });
    getProfil();
}

//===================================================== Detail des Musiques =======================================================//
// Récupère les détails des musiques
function getDetailMusique(){
    ajaxRequest('GET','../php/request.php/detailM?id_musique='+id_musique_detail+'&id_user='+id_user,printdetailMusique);
}
// Affiche les détails des musiques
function printdetailMusique(data){
    indetail = true;
    inrecherche = inaccueil = inlibrary = false;
    inner = `
    <div class="row" style="height:15%"></div>
    <div class="row" style="height70%;color:midnightblue">
        <div class="col"></div>
        <div class="col-6 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:60%; height:100%;">
            <div class="row mt-3">
                <div class="col-4">
                    <button class="btn btn-secondary album" value="`+data[1]['id_album']+`" style="width:60%; height:48%;"><img class="img-fluid rounded" src="`+data[1]['aimage']+`"></button>
                    <p>
                        <b>`+data[1]['anom']+`</b><br>Style : `+data[1]['style_album']+`
                    </p>
                </div>
                <div class="col-4">
                    <button class="btn btn-secondary musique" value="`+data[1]['id_musique']+`" style="width:80%; height:65%;"><img class="img-fluid rounded" src="`+data[1]['aimage']+`"></button>
                    <p>
                       <b>`+data[1]['titre']+`</b><br>`+data[1]['anom']+`<br><i>`+data[1]['rnom']+`</i><br>
                       Durée : `+data[1]['duree']+`
                    </p>
                </div>
                <div class="col-4">
                    <button class="btn btn-secondary artiste" value="`+data[1]['id_artiste']+`" style="width:60%; height:48%;"><img class="img-fluid rounded" src="`+data[1]['rimage']+`"></button>
                    <p>
                        <b>`+data[1]['rnom']+`</b><br>Type : `+data[1]['type_artiste']+`
                    </p>
                </div>
            </div>
            <div class="row mt-3 mb-3">
                <div class="col d-flex justify-content-start">
                    <button type="button" class="btn btn-outline-danger favd">`;
            if(data[0]==true){
                inner = inner + '<i class="fa-solid fa-heart" id="true" value="'+data[1]['id_musique']+'"></i>';
            }else{
                inner = inner + '<i class="fa-regular fa-heart" id="false" value="'+data[1]['id_musique']+'"></i>';
            }
            inner = inner +`
                        </button>
                    </div>
                <div class="col"></div>
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-dark modalclassd" data-bs-toggle="modal" data-bs-target="#myModal" value="`+data[1]['id_musique']+`">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
    <div class="row" style="height:15%"></div>`;
    page.innerHTML = inner;
    buttons = document.getElementsByClassName('modalclassd');
    checkPlus(buttons);
    classe = document.getElementsByClassName("favd");
    checkFav(classe,refresh);
    // Vérifie si l'on veut écouter une musique
    musiques = document.getElementsByClassName("musique")
    for (i = 0; i < musiques.length; i++) {
        musiques[i].addEventListener("click", function(event){
            id_music = event.currentTarget.value;
            inlecture = false;
            getMusique();
        });
    }
    // Vérifie si l'on veut les détails d'un album
    albums = document.getElementsByClassName("album")
    for (i = 0; i < albums.length; i++) {
        albums[i].addEventListener("click", function(event){
            id = event.currentTarget.value;
            getDetailAlbum(id);
        });
    }
    // Vérifie si l'on veut les détails d'un artiste
    artistes = document.getElementsByClassName("artiste")
    for (i = 0; i < artistes.length; i++) {
        artistes[i].addEventListener("click", function(event){
            id = event.currentTarget.value;
            getDetailArtiste(id);
        });
    }
}

//====================================================== Detail des Albums ========================================================//
// Récupère les détails d'un album
function getDetailAlbum(id_album_detail){
    ajaxRequest('GET','../php/request.php/detailAl?id_album='+id_album_detail,printdetailAlbum);
}
// Affiche les détails de l'album
function printdetailAlbum(data){
    inrecherche = inaccueil = inlibrary = indetail = false;
    inner = `
    <br>
    <div class="row" style="height:91%; color:midnightblue">
        <div class="col"></div>
        <div class="col-6 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:60%; height:100%;">
            <div class="row mt-3">
                <div class="col-4">
                    <div style="height:38%"></div>
                    <button type='button' class='btn btn-success' id='playalbum' value='` + data[0]['id_album'] +`'><i class='fa-solid fa-play'></i></button>
                </div>
                <div class="col-4">
                    <button class="btn btn-secondary" value="`+data[0]['id_album']+`" style="width:80%; height:75%;"><img class="img-fluid rounded" src="`+data[0]['aimage']+`"></button>
                    <p>
                       <b>`+data[0]['anom']+`</b><br>Style : `+data[0]['style_album']+`<br>
                       Date de parution : `+data[0]['date_parution']+`
                    </p>
                </div>
                <div class="col-4">
                    <button class="btn btn-secondary artiste" value="`+data[0]['id_artiste']+`" style="width:60%; height:55%;"><img class="img-fluid rounded" src="`+data[0]['rimage']+`"></button>
                    <p>
                        <b>`+data[0]['rnom']+`</b><br>Type : `+data[0]['type_artiste']+`
                    </p>
                </div>
            </div><br><h3 class="text-start">Musiques :</h3>
            <div class="row"><div class="col">`
    for(i=0;i<data[1].length;i=i+5){
        inner = inner + '<div class="row mt-3">';
        for(j=i;j<i+5;j++){
            if(j<data[1].length){
                inner = inner +`
                <div class="col">
                    <button class="btn btn-secondary musique" value="`+data[1][j]['id_musique']+`" style="width:80%; height:65%;"><img class="img-fluid rounded" src="`+data[1][j]['image']+`"></button>
                    <p><b>`+data[1][j]['titre']+`</b><br>
                        <button type="button" class="btn btn-outline-success detaild" value="`+data[1][j]['id_musique']+`">
                            <i class="fa-solid fa-info"></i>
                        </button>
                    </p>
                </div>
                `;
            }else{
                inner = inner + '<div class="col"></div>';
            }
        }
        inner = inner + '</div>';
    }
    inner = inner +`
            </div></div>
        </div>
        <div class="col"></div>
    </div>
    <div class="row" style="height:4%"></div>`;
    page.innerHTML = inner;
    if(data[1].length>0){
        classD = document.getElementsByClassName("detaild");
        CheckDetail(classD);
    }
    // Vérifie si l'on veut écouter une musique
    musiques = document.getElementsByClassName("musique")
    for (i = 0; i < musiques.length; i++) {
        musiques[i].addEventListener("click", function(event){
            id_music = event.currentTarget.value;
            inlecture = false;
            getMusique();
        });
    }
    // Vérifie si l'on veut les détails d'un artiste
    artistes = document.getElementsByClassName("artiste")
    for (i = 0; i < artistes.length; i++) {
        artistes[i].addEventListener("click", function(event){
            id = event.currentTarget.value;
            getDetailArtiste(id);
        });
    }
    // Vérifie si l'on veut écouter un album
    document.getElementById("playalbum").addEventListener("click", function(event){
        id_album = event.currentTarget.getAttribute('value');
        ajaxRequest('GET','../php/request.php/detailAl?id_album='+id_album,ListenAlbum);
    });
}

//===================================================== Detail des Artistes =======================================================//
// Récupère les détails d'un artistes
function getDetailArtiste(id_artiste_detail){
    ajaxRequest('GET','../php/request.php/detailAr?id_artiste='+id_artiste_detail,printdetailArtiste);
}
// Affiche les détails d'un artiste
function printdetailArtiste(data){
    inrecherche = inaccueil = inlibrary = indetail = false;
    inner = `
    <br>
    <div class="row" style="height:91%; color:midnightblue">
        <div class="col"></div>
        <div class="col-6 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:60%; height:100%;">
            <div class="row mt-3">
                <div class="col-4"></div>
                <div class="col-4">
                    <button class="btn btn-secondary" value="`+data[0]['id_artiste']+`" style="width:80%; height:75%;"><img class="img-fluid rounded" src="`+data[0]['rimage']+`"></button>
                    <p>
                       <b>`+data[0]['rnom']+`</b><br>Type : `+data[0]['type_artiste']+`
                    </p>
                </div>
                <div class="col-4"></div>
            </div><br><h3 class="text-start">Albums :</h3>
            <div class="row"><div class="col">`
    for(i=0;i<data[1].length;i=i+5){
        inner = inner + '<div class="row mt-3">';
        for(j=i;j<i+5;j++){
            if(j<data[1].length){
                inner = inner +`
                <div class="col">
                    <button class="btn btn-secondary album" value="`+data[1][j]['id_album']+`" style="width:80%; height:75%;"><img class="img-fluid rounded" src="`+data[1][j]['image']+`"></button>
                    <p><b>`+data[1][j]['nom']+`</b><br></p>
                </div>
                `;
            }else{
                inner = inner + '<div class="col"></div>';
            }
        }
        inner = inner + '</div>';
    }
    inner = inner +`
            </div></div>
        </div>
        <div class="col"></div>
    </div>
    <div class="row" style="height:4%"></div>`;
    page.innerHTML = inner;
    if(data[1].length>0){
        classD = document.getElementsByClassName("detaild");
        CheckDetail(classD);
    }
    // Vérifie si l'on veut les détails d'un album
    albums = document.getElementsByClassName("album")
    for (i = 0; i < albums.length; i++) {
        albums[i].addEventListener("click", function(event){
            id = event.currentTarget.value;
            getDetailAlbum(id);
        });
    }
}

// Vérifie si l'on veut ajouter une musique à une playlist
document.getElementById("addMusic").addEventListener("click", function(){
    id_musique = document.getElementById("id_musique_modal").getAttribute('value');
    id_playlist = document.getElementById("id_playlist_modal").value;
    ajaxRequest('POST','../php/request.php/modal',refreshModal,'id_musique='+id_musique+'&id_playlist='+id_playlist);
});

// Appel accueil, le lieu d'arrivée
accueil();