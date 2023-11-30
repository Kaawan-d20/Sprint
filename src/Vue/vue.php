<?php

function afficherLogin(){
    $contenu="";
    require_once('gabaritLanding.php');
}
function afficherErreur ($erreur) {
    $content = "<p>".$erreur."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
    require_once('gabaritLanding.php');
}
function afficherClient($client){
    $contenu= '';
    require_once('gabaritInfoClient.php');
}

function afficherRechercheClient($listeClient) {
    $contenu="";
}