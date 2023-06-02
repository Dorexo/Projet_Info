const page = document.getElementById('page');
const id_user = document.getElementById("id_user").getAttribute("value");


function getMusique(id_musique=1){
    ajaxRequest('GET','../php/request.php/music?id_musique='+id_musique+'&id_user='+id_user,printMusique);
}
function printMusique(data){
    document.getElementById("music").innerHTML = `
        <div class="col"></div>
        <div class="col-4 d-flex justify-content-center">
            <audio controls style="width:100%;">
                <source src="`+data['src']+`">
            </audio>
        </div>
        <div class="col">
            <b>`+data['titre']+`</b> par `+data['rnom']+`<br>
            <b>`+data['anom']+`</b>
        </div>
        <div class="col d-flex align-items-center">
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
        </div> `
    getHistorique();
}

function getHistorique(){
    ajaxRequest('GET','../php/request.php/accueil/historique?id_user='+id_user,printHistorique);
}
function printHistorique(data){
    nbhisto = data.length
    liste = document.getElementById("historique_accueil");
    inner =""
    for(i=0;i<nbhisto;i+=2){
        inner = inner + '<div class="row"><div class="col mt-2"><button class="btn btn-secondary musique" value="'+data[i]['id_musique']+'" style="width:10em; height:10em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button><p>'+data[i]['titre']+'</p></div>'
        if(i+1<nbhisto){
            inner = inner + '<div class="col mt-2"><button class="btn btn-secondary musique" value="'+data[i+1]['id_musique']+'" style="width:10em; height:10em;"><img class="img-fluid rounded" src="'+data[i+1]['image']+'" ></button><p>'+data[i+1]['titre']+'</p></div></div>'
        }else{
            inner = inner + '<div class="col mt-2"></div></div>'
        }
        liste.innerHTML= inner;
    }

    playlists = document.getElementsByClassName("musique")
    for (i = 0; i < playlists.length; i++) {
        playlists[i].addEventListener("click", function(event){
            id = event.currentTarget.value;
            getMusique(id);
        });
    }
}

function getPlaylists(){
    ajaxRequest('GET','../php/request.php/accueil/playlists?id_user='+id_user,printPlaylists);
}
function printPlaylists(data){
    nbplaylist = data.length
    if(nbplaylist>0){
        document.getElementById("playlist_accueil").innerHTML = '<button class="btn btn-secondary playlist" value="'+data[0]['id_playlist']+'" style="width:10em; height:10em;"><img class="img-fluid rounded" src="'+data[0]['image']+'" ></button><p>'+data[0]['nom']+'</p>';
        nbplaylist--;
        liste = document.getElementById("playlists_accueil");
        inner = liste.innerHTML;
        for(i=1;i<nbplaylist+1;i+=2){
            inner = inner + '<div class="row"><div class="col mt-2"><button class="btn btn-secondary playlist" value="'+data[i]['id_playlist']+'" style="width:10em; height:10em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button><p>'+data[i]['nom']+'</p></div>'
            if(i+1<nbplaylist+1){
                inner = inner + '<div class="col mt-2"><button class="btn btn-secondary playlist" value="'+data[i+1]['id_playlist']+'" style="width:10em; height:10em;"><img class="img-fluid rounded" src="'+data[i+1]['image']+'" ></button><p>'+data[i+1]['nom']+'</p></div></div>'
            }else{
                inner = inner + '<div class="col mt-2"></div></div>'
            }
        }
        liste.innerHTML= inner;
    }

    document.getElementById("boutton_favoris").addEventListener("click", function(event){
        event.preventDefault();
        favoris(id);
    });
    playlists = document.getElementsByClassName("playlist")
    for (i = 0; i < playlists.length; i++) {
        playlists[i].addEventListener("click", function(event){
            id = event.target.value;
            playlists(id);
        });
    }
  
}
function accueil(id=-1){
    if(id!=-1){
        getMusique(id);
    }
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
            <div class="col-4 text-center d-flex align-items-center">
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
            <h2>10 dernières musiques écoutées</h2>
        </div>
        <div class="col"></div>
        <div class="col-5">
            <h2>Playlists</h2>
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
            <div class="row">
                <div class="col mt-2">
                    <button class="btn btn-secondary" id="boutton_favoris" style="width:10em; height:10em;"><img class="img-fluid rounded" src="../ressources/Playlists/favoris.png" ></button>
                    <p>Favoris</p>
                </div>
                <div class="col mt-2" id="playlist_accueil">
                    
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row" style="height:4%"></div>`;
    getPlaylists();
    getHistorique();
    document.getElementById("recherche_submit").addEventListener("click", function(event){
        event.preventDefault();
        console.log("sssssssssss")
        search = document.getElementById('recherche').value;
        document.getElementById('recherche').value = "";
        radios = document.getElementsByName("parqui");
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                recherche();
                ajaxRequest('GET','../php/request.php/recherche?search='+search+'&who='+radios[i].value,printRecherche);
                break;
            }
        }
    }); 
}



function recherche(){
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
                <div class="col-4 text-center d-flex align-items-center">
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
    <div class="row" style="height:78%">
        <div class="col"></div>
        <div class="col-6 border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:60%; height:100%;">
        <div id="recherche_page">
        
        </div>
        </div>
        <div class="col"></div>
    </div>
    <div class="row" style="height:4%"></div>`;

    document.getElementById("recherche_submit").addEventListener("click", function(event){
        event.preventDefault();
        search = document.getElementById('recherche').value;
        document.getElementById('recherche').value = "";
        radios = document.getElementsByName("parqui");
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                ajaxRequest('GET','../php/request.php/recherche?search='+search+'&who='+radios[i].value,printRecherche);
                break;
            }
        }
    }); 
}
function printRecherche(data){
    console.log(data[0]);
    nbresult = data.length
    liste = document.getElementById("recherche_page");
    inner ='<table class="table text-center align-middle">';
    if(data[0]=="musique"){
        inner = inner + '<thead><tr><th></th><th>Titre</th><th>Album</th><th>Artiste</th><th>Durée</th><th>Date de parution</th><th></th></thead><tbody>'
        for(i=1;i<nbresult;i++){
            inner = inner + '<tr><td><button class="btn btn-secondary musique" value="'+data[i]['id_musique']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button></td><td>'+data[i]['titre']+'</td><td>'+data[i]['anom']+'</td><td>'+data[i]['rnom']+'</td><td>'+data[i]['duree']+'</td><td>'+data[i]['date_parution']+'</td><td>'+`
            <div class="col d-flex align-items-center">
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
            </div></td></tr>`
        }
    }else if(data[0]=="album"){
        inner = inner + "<thead><tr><th></th><th>Nom de l'album</th><th>Artiste</th><th>Date de parution</th></thead><tbody>"
        for(i=1;i<nbresult;i++){
            inner = inner + '<tr><td><button class="btn btn-secondary musique" value="'+data[i]['id_album']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button></td><td>'+data[i]['anom']+'</td><td>'+data[i]['rnom']+'</td><td>'+data[i]['date_parution']+'</td></tr>'
        }
    }else if(data[0]=="artiste"){
        inner = inner + "<thead><tr><th></th><th>Nom de l'artiste</th></thead><tbody>"
        for(i=1;i<nbresult;i++){
            inner = inner + '<tr><td><button class="btn btn-secondary musique" value="'+data[i]['id_album']+'" style="width:5em; height:5em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button></td><td>'+data[i]['nom']+'</td></tr>'
        }
    }
    liste.innerHTML= inner + '</tbody></table>';
}






accueil();
getMusique();