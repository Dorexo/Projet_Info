const page = document.getElementById('page');


function getPlaylists(){
    id_user = document.getElementById("id_user").getAttribute("value");
    ajaxRequest('GET','../php/request.php/accueil/?id_user='+id_user,printPlaylists);
}
function printPlaylists(data){
    nb = data.length
    if(nb>0){
        document.getElementById("playlist_accueil").innerHTML = '<button class="btn btn-secondary playlist" value="'+data[0]['id_playlist']+'" style="width:8em; height:8em;"><img class="img-fluid rounded" src="'+data[0]['image']+'" ></button><p>'+data[0]['nom']+'</p>';
        nb--;
        console.log(data[0]['image']);
        liste = document.getElementById("playlists_accueil");
        inner = liste.innerHTML;
        for(i=1;i<nb+1;i+=2){
            inner = inner + '<div class="row"><div class="col mt-2"><button class="btn btn-secondary playlist" value="'+data[i]['id_playlist']+'" style="width:8em; height:8em;"><img class="img-fluid rounded" src="'+data[i]['image']+'" ></button><p>'+data[i]['nom']+'</p></div>'
            if(i+1<nb+1){
                inner = inner + '<div class="col mt-2"><button class="btn btn-secondary playlist" value="'+data[i+1]['id_playlist']+'" style="width:8em; height:8em;"><img class="img-fluid rounded" src="'+data[i+1]['image']+'" ></button><p>'+data[i+1]['nom']+'</p></div></div>'
            }else{
                inner = inner + '<div class="col mt-2"></div></div>'
            }
        }
        liste.innerHTML= inner;
    }

    document.getElementById("boutton_favoris").addEventListener("click", function(event){
        event.preventDefault();
        document.location.href="favoris.php"; 
    });
    playlists = document.getElementsByClassName("playlist")
    for (i = 0; i < playlists.length; i++) {
        playlists[i].addEventListener("click", function(event){
            id = event.target.value;
            document.location.href="playlists.php"; 
            console.log(id);
        });
    }
  
}



function accueil(){
    page.innerHTML = `    <br>
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
    <div class="row" style="height:70%">
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
                                <source src="../ressources/Squeezie/Treis Degete/Spaceship/Spaceship.mp3">
                            </audio>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-footer text-center">
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
        <div class="col border border-dark rounded text-center" style="background-color:rgb(222,222,222); overflow:auto; width:100%; height:100%;">
            <div id="playlists_accueil">
            <div class="row">
                <div class="col mt-2">
                    <button class="btn btn-secondary" id="boutton_favoris" style="width:8em; height:8em;"><img class="img-fluid rounded" src="../ressources/Playlists/favoris.png" ></button>
                    <p>Favoris</p>
                </div>
                <div class="col mt-2" id="playlist_accueil">
                    
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row" style="height:4%"></div>`

    getPlaylists();
}


accueil();