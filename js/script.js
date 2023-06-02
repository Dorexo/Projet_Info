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
            id = playlists[i].getAttribute("value");
            document.location.href="playlists.php"; 
            console.log(id);
        });
    }
  
}



getPlaylists();
