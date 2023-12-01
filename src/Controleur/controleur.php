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
        vueAfficherLogin();
    }
    elseif ($_SESSION["type"] == 1){
        vueAfficherAccueilDirecteur();
    }
    elseif ($_SESSION["type"] == 2){
        vueAfficherAccueilConseiller();
    }
    elseif ($_SESSION["type"] == 3){
        vueAfficherAccueilAgent();
    }
    
}
/**
 * Fonction qui permet de se connecter
 * C'est ici que l'on initialise la session 
 * @param string $username c'est le nom d'utilisateur qui est le login dans la base de données
 * @param string $password c'est le mot de passe de l'utilisateur mais il est hashé et salé
 * @throws incorrectLoginException si le login ou le mot de passe est incorrect
 * @throws estVideException si le login ou le mot de passe est vide
 */
function ctlLogin ($username, $password) {
    if ($username == '' || $password == '') {
        echo 'fonction cont login';
        throw new estVideException();
    }
    $resutatConnnect = modConnect($username, $password);
    if (empty($resutatConnnect)){
        throw new incorrectLoginException();
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
 * @throws estvideException si l'id est vide
 * @throws clientNonTrouverException si aucun client n'est trouvé
 */
function ctrChercherClient($idClient){
    if ($idClient == '') {
        throw new estVideException('Veuillez entrer un ID');
    }
    else{
        $client = modChercherClient($idClient);
        if (empty($client)){
            throw new clientNonTrouverException();
        }
        else{
            modGetClientFromId($idClient);
            vueAfficherClient($client);
        }
    }
}
/**
 * Fonction qui permet de chercher un client en fonction de son nom, prénom et date de naissance
 * @param string $nomClient c'est le nom du client
 * @param string $prenomClient c'est le prénom du client
 * @param string $dateNaissance c'est la date de naissance du client
 * @throws estVideException si un des champs est vide
 * @throws clientNonTrouverException si aucun client n'est trouvé
 */
function cltRechercheAvanceeClient($nomClient, $prenomClient, $dateNaissance) {
    if ($prenomClient == '' | $nomClient == '' | $dateNaissance == '') {
        throw new estVideException();
    }
    else{
        $listeClient = modRechercheAvancéeClient($nomClient, $prenomClient, $dateNaissance);
        if (empty($listeClient)){
            throw new clientNonTrouverException();
        }
        else{
            vueAfficherRechercheClient($listeClient);
        }
    }
}
/**
 * Fonction qui permet d'obtenir l'agenda d'un conseiller
 * @param int $idConseiller c'est l'id du conseiller
 */
function ctlAgendaConseiller($idConseiller){
    $agenda = modGetAgendaConseiller($idConseiller);
    vueAfficherAgendaConseiller($agenda);
}

/**
 * Fonction qui permet d'afficher les erreurs
 */
function ctlErreur($erreur) {
    vueAfficherErreur($erreur) ;
}
