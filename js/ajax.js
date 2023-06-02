// Requête ajax
function ajaxRequest(type, url, callback, data = null)
{
    let xhr;
    //console.log(data);

    xhr = new XMLHttpRequest();
    xhr.open(type, url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = () =>
    {
        switch (xhr.status)
        {
        case 200:
            console.log(xhr.responseText);
            let resp = JSON.parse(xhr.responseText);
            callback(resp);
            break;
        case 201:
        default:
            displayErrors(xhr.status);
        }
    };

    xhr.send(data);
}

// Affiche l'erreur
function httpErrors(errorCode)
{
    let messages = {
        400: 'Requête incorrecte',
        401: 'Authentifiez vous',
        403: 'Accès refusé',
        404: 'Page non trouvée',
        500: 'Erreur interne du serveur',
        503: 'Service indisponible'
    };

    if (errorCode in messages){
        $('#errors').html('<strong>' + messages[errorCode] + '</strong>');
        $('#errors').show();
        setTimeout(() =>{
            $('#errors').hide();
        }, 5000);
    }
}