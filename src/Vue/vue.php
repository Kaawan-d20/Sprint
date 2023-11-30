<?php
/**
 * Fonction qui affiche la page d'accueil du directeur
 * Ne prend pas de paramètres et ne retourne rien
 */
function afficherAccueilDirecteur(){
    $contenu="";
    require_once('gabaritDirecteurHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil du conseiller
 * Ne prend pas de paramètres et ne retourne rien
 */
function afficherAccueilConseiller(){
    $contenu="";
    require_once('gabaritConseillerHomePage.php');
}
/**
 * Fonction qui affiche la page d'accueil de l'agent d'accueil
 * Ne prend pas de paramètres et ne retourne rien
 */
function afficherAccueilAgent(){
    $contenu="";
    require_once('gabaritAccueilHomePage.php');
}
/**
 * Fonction qui affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 */
function afficherLogin(){
    $contenu="";
    require_once('gabaritLanding.php');
}


/**
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param string $client c'est les données du client
 */
function afficherClient($client){
    $contenu= '';
    require_once('gabaritInfoClient.php');
}
/**
 * Fonction qui affiche la page de resultat de recherche d'un client
 * Ne retourne rien
 * @param array $listeClient c'est la liste des clients
 */
function afficherRechercheClient($listeClient) {
    $contenu="<table>";
    foreach ($listeClient as $client) {
        $contenu .= "<tr><td>".$client['id']."</td><td>".$client['nom']."</td><td>".$client['prenom']."</td><td>".$client['dateNaissance']."</td>";
    }
    $contenu .= "</table>";
    require_once('gabaritRechercheClient.php');
}


/**
 * Fonction qui affiche la page d'erreur
 * Ne retourne rien
 * @param string $erreur
 */
function afficherErreur ($erreur) {
    $content = "<p>".$erreur."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
    require_once('gabaritLanding.php');
}