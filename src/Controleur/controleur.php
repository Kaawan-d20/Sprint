<?php
require_once 'modele/modele.php';
require_once 'vue/vue.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * Fonction qui affiche la page d'accueil en fonction de la catégorie de l'utilisateur
 * si l'utilisateur n'est pas connecté, affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 */
function ctlHome (){
    if (!isset($_SESSION["type"])){
        vueDisplayLogin();
    }
    elseif ($_SESSION["type"] == 1){
        vueDisplayHomeDirecteur();
    }
    elseif ($_SESSION["type"] == 2){
        vueDisplayHomeConseiller();
    }
    elseif ($_SESSION["type"] == 3){
        vueDisplayHomeAgent();
    }
    
}
/**
 * Fonction qui permet de se connecter
 * C'est ici que l'on initialise la session 
 * @param string $username c'est le nom d'utilisateur qui est le login dans la base de données
 * @param string $password c'est le mot de passe de l'utilisateur mais il est hashé et salé
 * @throws incorrectLoginException si le login ou le mot de passe est incorrect
 * @throws isEmptyException si le login ou le mot de passe est vide
 */
function ctlLogin ($username, $password) {
    if ($username == '' || $password == '') {
        throw new isEmptyException();
    }
    $resultConnnect = modConnect($username, $password);
    if (empty($resutatConnnect)){
        throw new incorrectLoginException();
    }
    else{
        $_SESSION["active"] = true; // voir si avec un session_close() on peut pas faire un truc
        $_SESSION["login"] = $resultConnnect;
        $_SESSION["type"] = modGetTypeStaff($resultConnnect);
        ctlHome();
    }
}

/**
 * Fonction qui permet de se déconnecter
 * Ne prend pas de paramètres et ne retourne rien
 */
function ctlLogout() {
    session_destroy();
    vueDisplayLogin();
}



/**
 * Fonction qui permet de chercher un client en fonction de son idClient
 * @param int $idClient c'est l'id du client
 * @throws isEmptyException si l'id est vide
 * @throws notFoundClientException si aucun client n'est trouvé
 */
function ctrSearchIdClient($idClient){
    if ($idClient == '') {
        throw new isEmptyException('Veuillez entrer un ID');
    }
    else{
        $client = 'test';//modGetClientFromId($idClient);
        if (empty($client)){
            throw new notFoundClientException();
        }
        else{
            vueDisplayInfoClient($client);
        }
    }
}
/**
 * Fonction qui permet de chercher un client en fonction de son nom, prénom et date de naissance
 * @param string $nomClient c'est le nom du client
 * @param string $prenomClient c'est le prénom du client
 * @param string $dateNaissance c'est la date de naissance du client
 * @throws isEmptyException si un des champs est vide
 * @throws notFoundClientException si aucun client n'est trouvé
 */
function cltAdvanceSearchClient($nameClient, $firstNameClient, $dateOfBirth) {
    if ($nameClient == '' | $firstNameClient == '' | $dateOfBirth == '') {
        throw new isEmptyException();
    }
    else{
        $listClient = $firstNameClient;//modAdvancedSearchClient($nameClient, $firstNameClient, $dateOfBirth);
        if (empty($listClient)){
            throw new notFoundClientException();
        }
        else{
            vueDisplayAdvanceSearchClient($listClient);
        }
    }
}
/**
 * Fonction qui permet d'obtenir l'agenda d'un conseiller
 * pas encore tester
 * @param int $loginEmploye c'est login du conseiller
 */
function ctlCalendarConseiller($loginEmploye){
    $appointment = modGetAppointmentConseiller($loginEmploye);
    $admin = modGetAdminConseiller($loginEmploye);
    vueDisplayAgendaConseiller($appointment, $admin);
}

function ctrGetAccount($idClient){
    $account = modGetAccounts($idClient);
    echo $account;
}


/**
 * Fonction qui permet d'afficher les erreurs
 */
function ctlError($error) {
    vueAfficherErreur($error) ;
}
