<?php
require_once 'modele/modele.php';
require_once 'vue/vue.php';
/**
 * Fonction qui affiche la page d'accueil en fonction de la catégorie de l'utilisateur
 * si l'utilisateur n'est pas connecté, affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 */
function ctlAcceuil (){
    if (!isset($_SESSION)){
        afficherLogin();
    }
    elseif ($_SESSION["type"] == 1){
        afficherAccueilDirecteur();
    }
    elseif ($_SESSION["type"] == 2){
        afficherAccueilConseiller();
    }
    elseif ($_SESSION["type"] == 3){
        afficherAccueilAgent();
    }
    
}
/**
 * Fonction qui permet de se connecter
 * C'est ici que l'on initialise la session 
 * @param string $username c'est le nom d'utilisateur qui est le login dans la base de données
 * @param string $password c'est le mot de passe de l'utilisateur mais il est hashé et salé
 * @throws Exception
 */
function login ($username, $password) {
    if ($username == '' || $password == '') {
        echo 'fonction cont login';
        throw new Exception("Un des champs est vide");
    }
    $resutatConnnect = modConnect($username, $password);
    if (empty($resutatConnnect)){
        throw new Exception("Nom d'utilisateur ou mot de passe incorrect");
    }
    else{
        session_start();
        $_SESSION["acitf"] = true;
        $_SESSION["id"] = $resutatConnnect;
        $_SESSION["type"] = modGetTypeStaff($resutatConnnect);
        ctlAcceuil();
    }
}
/**
 * Fonction qui permet de chercher un client en fonction de son idClient
 * @param int $idClient c'est l'id du client
 * @throws Exception si l'id est vide
 * @throws Exception si aucun client n'est trouvé
 */
function chercherClient($idClient){
    if ($idClient == '') {
        throw new Exception('Veuillez entrer un ID');
    }
    else{
        $client = modChercherClient($idClient);
        if (empty($client)){
            throw new Exception('Aucun client trouvé');
        }
        else{
            modGetClientFromId($idClient);
            afficherClient($client);
        }
    }
}
/**
 * Fonction qui permet de chercher un client en fonction de son nom, prénom et date de naissance
 * @param string $nomClient c'est le nom du client
 * @param string $prenomClient c'est le prénom du client
 * @param string $dateNaissance c'est la date de naissance du client
 * @throws Exception si un des champs est vide
 * @throws Exception si aucun client n'est trouvé
 */
function rechercheAvanceeClient($nomClient, $prenomClient, $dateNaissance) {
    if ($prenomClient == '' | $nomClient == '' | $dateNaissance == '') {
        throw new Exception('Veuillez remplir tous les champs');
    }
    else{
        $listeClient = modRechercheAvancéeClient($nomClient, $prenomClient, $dateNaissance);
        if (empty($listeClient)){
            throw new Exception('Aucun client trouvé');
        }
        else{
            afficherRechercheClient($listeClient);
        }
    }
}
/**
 * Fonction qui permet d'afficher les erreurs
 */
function ctlErreur($erreur) {
    afficherErreur($erreur) ;
}
