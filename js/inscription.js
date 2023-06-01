function inscription(){
    nom = document.getElementById('nom').value;
    document.getElementById('nom').value = "";
    prenom = document.getElementById('prenom').value;
    document.getElementById('prenom').value = "";
    date_naissance = document.getElementById('date_naissance').value;
    document.getElementById('date_naissance').value = "";
    email = document.getElementById('email').value;
    document.getElementById('email').value = "";
    mdp1 = document.getElementById('mdp1').value;
    document.getElementById('mdp1').value = "";
    mdp2 = document.getElementById('mdp2').value;
    document.getElementById('mdp2').value = "";
    if(mdp1!=mdp2 || nom=="" || prenom=="" || date_naissance=="" || email=="" || mdp1==""){
        console.log("erreur");
    }else{
        request = 'nom='+nom+'&prenom='+prenom+'&date_naissance='+date_naissance+'&email='+email+'&mdp='+mdp1;
        ajaxRequest('POST','../php/request.php/inscription/',confirmation,request);
    }
}
function confirmation(data){
    document.location.href="connexion.php"; 
}

function listener(){
    document.getElementById("envoyer").addEventListener("click", function(event){
        event.preventDefault();
        inscription();
    });
}

listener();