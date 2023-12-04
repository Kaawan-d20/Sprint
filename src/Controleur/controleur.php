<?php
require_once('modele/modele.php');
require_once('vue/vue.php');
//require_once('exception.php');

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
 * @throws Exception si le login ou le mot de passe est incorrect
 * @throws Exception si le login ou le mot de passe est vide
 */
function ctlLogin ($username, $password) {
    if ($username == '' || $password == '') {
        throw new Exception("Veuillez remplir tous les champs");
    }
    $resultConnnect = modConnect($username, $password);
    if (empty($resultConnnect)){
        throw new Exception('Nom d\'utilisateur ou mot de passe incorrect');
    }
    else{
        $_SESSION["active"] = true; // voir si avec un session_close() on peut pas faire un truc
        $_SESSION["login"] = $resultConnnect->LOGIN;
        $_SESSION["type"] = modGetTypeStaff($_SESSION["login"])->idCategorie;
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
 * @throws Exception si l'id est vide
 * @throws Exception si aucun client n'est trouvé
 */
function ctrSearchIdClient($idClient){
    if ($idClient == '') {
        throw new Exception('Veuillez entrer un ID');
    }
    else{
        $client = modGetClientFromId($idClient);
        if (empty($client)){
            throw new Exception('Aucun client trouvé');
        }
        else{
            vueDisplayInfoClient($client, ctrGetAccount($idClient));
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
function cltAdvanceSearchClient($nameClient, $firstNameClient, $dateOfBirth) {
    if ($nameClient == '' | $firstNameClient == '' | $dateOfBirth == '') {
        throw new Exception('Veuillez remplir tous les champs');
    }
    else{
        $listClient = modAdvancedSearchClient($nameClient, $firstNameClient, $dateOfBirth);
        if (empty($listClient)){
            throw new Exception('Aucun client trouvé');
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
/*
function ctlCalendarConseiller($loginEmploye){
    $appointment = modGetAppointmentConseiller($loginEmploye);
    $admin = modGetAdminConseiller($loginEmploye);
    vueDisplayAgendaConseiller($appointment, $admin);
}
*/
function ctrGetAccount($idClient){
    $account = modGetAccounts($idClient);
    return $account;
}


function ctlDebit($idAccount, $amount){
    $decouvert = modGetDecouvert($idAccount)->decouvert;
    $solde = modGetSolde($idAccount)->solde;
    if ($amount > $solde + $decouvert){
        throw new Exception('Vous ne pouvez pas débiter plus que le solde et le découvert');
    }
    modDebit($idAccount, $amount);
    $idClient = modGetIdClientFromAccount($idAccount)->idClient;
    $client = modGetClientFromId($idClient);
    vueDisplayInfoClient($client, ctrGetAccount($idClient));
}

function ctlCredit($idAccount, $amount){
    modCredit($idAccount, $amount);
    $idClient = modGetIdClientFromAccount($idAccount)->idClient;
    $client = modGetClientFromId($idClient);
    vueDisplayInfoClient($client, ctrGetAccount($idClient));
}

/**
 * Fonction qui permet d'afficher les erreurs
 */
function ctlError($error) {
    vueDisplayError($error);
}
