<?php

if (!isset($_SESSION)){
    session_start();
}
/**
 * Fonction qui affiche la page d'accueil du directeur
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueAfficherAccueilDirecteur(){
    $contenu="";
    require_once('gabaritDirecteurHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil du conseiller
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueAfficherAccueilConseiller(){
    $contenu="";
    require_once('gabaritConseillerHomePage.php');
}
/**
 * Fonction qui affiche la page d'accueil de l'agent d'accueil
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueAfficherAccueilAgent(){
    $contenu="";
    require_once('gabaritAccueilHomePage.php');
}
/**
 * Fonction qui affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueAfficherLogin(){
    $contenu="";
    require_once('gabaritLanding.php');
}


/**
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param string $client c'est les données du client
 */
function vueAfficherClient($client){
    $contenu= '';
    $contenu .= $client;
    echo 'echo';
    require_once('gabaritInfoClient.php');
}
/**
 * Fonction qui affiche la page de resultat de recherche d'un client
 * Ne retourne rien
 * @param array $listeClient c'est la liste des clients
 */
function vueAfficherRechercheClient($listeClient="") {
    /*$contenu="<table>";
    foreach ($listeClient as $client) {
        $contenu .= "<tr><td>".$client['id']."</td><td>".$client['nom']."</td><td>".$client['prenom']."</td><td>".$client['dateNaissance']."</td>";
    }
    $contenu .= "</table>";*/
    $contenu = $listeClient;
    $contenu .= "test avancée";
    require_once('gabaritRechercheClient.php');
}


/**
 * Fonction qui affiche la page d'erreur
 * Ne retourne rien
 * @param string $erreur
 */
function vueAfficherErreur ($erreur) {
    $content = "<p>".$erreur."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
    require_once('gabaritLanding.php');
}