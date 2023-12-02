<?php

if (!isset($_SESSION)){
    session_start();
}
/**
 * Fonction qui affiche la page d'accueil du directeur
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueDisplayHomeDirecteur(){
    $content="";
    require_once('gabaritDirecteurHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil du conseiller
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueDisplayHomeConseiller(){
    $content="";
    require_once('gabaritConseillerHomePage.php');
}
/**
 * Fonction qui affiche la page d'accueil de l'agent d'accueil
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueDisplayHomeAgent(){
    $content="";
    require_once('gabaritAccueilHomePage.php');
}
/**
 * Fonction qui affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueDisplayLogin(){
    $content="";
    require_once('gabaritLanding.php');
}


/**
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param string $client c'est les données du client
 */
function vueDisplayInfoClient($client){
    $content= '';
    $content .= $client;
    require_once('gabaritInfoClient.php');
}
/**
 * Fonction qui affiche la page de resultat de recherche d'un client
 * Ne retourne rien
 * @param array $listClient c'est la liste des clients
 */
function vueDisplayAdvanceSearchClient($listClient="") {
    /*$content="<table>";
    foreach ($listeClient as $client) {
        $content .= "<tr><td>".$client['id']."</td><td>".$client['nom']."</td><td>".$client['prenom']."</td><td>".$client['dateNaissance']."</td>";
    }
    $content .= "</table>";*/
    $content = $listClient;
    $content .= "test avancée";
    require_once('gabaritRechercheClient.php');
}













function vueDisplayAgendaConseiller($appointment, $admin){

}



/**
 * Fonction qui affiche la page d'erreur
 * Ne retourne rien
 * @param string $error
 */
function vueDisplayError ($error) {
    $content = "<p>".$error."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
    require_once('gabaritLanding.php');
}