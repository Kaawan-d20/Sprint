<?php
require_once('modele/modele.php');
require_once('vue/vue.php');
//require_once('exception.php');

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fonction qui affiche la page d'accueil en fonction de la catégorie de l'utilisateur
 * Si l'utilisateur n'est pas connecté, affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
 */
function ctlHome (){
    if (!isset($_SESSION["type"])){
        vueDisplayLogin();
    }
    elseif ($_SESSION["type"] == 1){
        $stat = ctlGetStats();
        vueDisplayHomeDirecteur($stat);
    }
    elseif ($_SESSION["type"] == 2){
        vueDisplayHomeConseiller();
    }
    elseif ($_SESSION["type"] == 3){
        ctlUpdateCalendar(new DateTime("now"));
    }
    
}
/**
 * Fonction qui permet de se connecter
 * C'est ici que l'on initialise la session 
 * @param string $username c'est le nom d'utilisateur qui est le login dans la base de données
 * @param string $password c'est le mot de passe de l'utilisateur mais il est hashé et salé
 * @throws Exception si le login ou le mot de passe est incorrect
 * @throws Exception si le login ou le mot de passe est vide
 * @return void
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
        $_SESSION["idEmploye"] = $resultConnnect->IDEMPLOYE;
        $_SESSION["type"] = modGetTypeStaff($_SESSION["idEmploye"]);
        ctlHome();
    }
}

/**
 * Fonction qui permet de se déconnecter
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
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
 * @return void
 */
function ctlSearchIdClient($idClient){
    if ($idClient == '') {
        throw new Exception('Veuillez entrer un ID');
    }
    else{
        $client = modGetClientFromId($idClient);
        if (empty($client)){
            throw new Exception('Aucun client trouvé');
        }
        else{
            vueDisplayInfoClient($client, ctrGetAccount($idClient), ctrGetContracts($idClient));
        }
    }
}

/**
 * Fonction qui permet de chercher un client en fonction de son nom, prénom et date de naissance
 * @param string $nameClient c'est le nom du client
 * @param string $firstNameClient c'est le prénom du client
 * @param string $dateOfBirth c'est la date de naissance du client
 * @throws Exception si tous les champs sont vides
 * @throws Exception si aucun client n'est trouvé
 */
function cltAdvanceSearchClient($nameClient, $firstNameClient, $dateOfBirth) {
    if (empty($nameClient) && empty($firstNameClient) && empty($dateOfBirth)) { // aucun champ rempli
        throw new Exception('Veuillez remplir tous les champs');
    }
    elseif (!empty($nameClient) && !empty($firstNameClient) && !empty($dateOfBirth)){ // il y a tout de rempli
        $listClient=modAdvancedSearchClientABC($nameClient, $firstNameClient, $dateOfBirth);
    }
    elseif (!empty($nameClient) && !empty($firstNameClient) && empty($dateOfBirth)){ // il y a que le nom et le prénom
        $listClient=modAdvancedSearchClientAB($nameClient, $firstNameClient);
    }
    elseif (!empty($nameClient) && empty($firstNameClient) && !empty($dateOfBirth)){ // il y a que le nom et la date de naissance
        $listClient=modAdvancedSearchClientAC($nameClient, $dateOfBirth);
    }
    elseif (empty($nameClient) && !empty($firstNameClient) && !empty($dateOfBirth)){ // il y a que le prénom et la date de naissance
        $listClient=modAdvancedSearchClientBC($firstNameClient, $dateOfBirth);
    }
    elseif (!empty($nameClient) && empty($firstNameClient) && empty($dateOfBirth)){ // il y a que le nom
        $listClient=modAdvancedSearchClientA("Staline");
    }
    elseif (empty($nameClient) && !empty($firstNameClient) && empty($dateOfBirth)){ // il y a que le prénom
        $listClient=modAdvancedSearchClientB($firstNameClient);
    }
    elseif (empty($nameClient) && empty($firstNameClient) && !empty($dateOfBirth)){ // il y a que la date de naissance
        $listClient=modAdvancedSearchClientC($dateOfBirth);
    }
    if (empty($listClient)){
        throw new Exception('Aucun client trouvé');
    }
    else{
        vueDisplayAdvanceSearchClient($listClient);
    }
}

/**
 * Fonction qui permet d'obtenir l'agenda d'un conseiller
 * pas encore tester
 * @param int $idEmploye c'est login du conseiller
 */

 function ctlCalendarConseiller($loginEmploye="GayBoi"){
    $appointment = modGetAppointmentConseiller($loginEmploye);
    $rdv = new ArrayObject();
    foreach ($appointment as $event) {
        $thisRDV = new ArrayObject();
        $thisRDV->append(modGetIntituleMotif($event->idMotif));
        $infoClient = modGetClientFromId($event->idClient);
        $thisRDV->append($infoClient->NOM);
        $thisRDV->append($infoClient->PRENOM);
        $thisRDV->append($infoClient->CIVILITEE);
        $employe = modGetEmployeFromId($event->IDEMPLOYE);
        $thisRDV->append($employe->PRENOM);
        $date = new DateTime($event->date);
        $thisRDV->append($date->format('Y/m/d'));
        $thisRDV->append($date->format('H:i'));


    }
    $admin = modGetAdminConseiller($loginEmploye);
    vueDisplayAgendaConseiller($appointment, $admin);
}

/**
 * Fonction qui permet d'obtenir la liste des compte d'un client
 * @param int $idClient c'est l'id du client
 * @return array c'est la liste des comptes du client (c'est un tableau d'objet)
 */
function ctrGetAccount($idClient){
    $account = modGetAccounts($idClient);
    return $account;
}

/**
 * Fonction qui permet d'obtenir la liste des contrat d'un client
 * @param int $idClient c'est l'id du client
 * @return array c'est la liste des contrat du client (c'est un tableau d'objet)
 */
function ctrGetContracts($idClient){
    $contracts = modGetContracts($idClient);
    return $contracts;
}

/**
 * Fonction qui permet de débiter un compte
 * @param int $idAccount c'est l'id du compte
 * @param string $amount c'est le montant à débiter
 * @throws Exception si le montant est supérieur au solde et au découvert
 * @return void
 */
function ctlDebit($idAccount, $amount){
    $decouvert = modGetDecouvert($idAccount);
    $solde = modGetSolde($idAccount);
    if ($amount > $solde + $decouvert){
        throw new Exception('Vous ne pouvez pas débiter plus que le solde et le découvert');
    }
    modDebit($idAccount, $amount);
    $idClient = modGetIdClientFromAccount($idAccount)->idClient;
    $client = modGetClientFromId($idClient);
    vueDisplayInfoClient($client, ctrGetAccount($idClient),ctrGetContracts($idClient));
}
/**
 * Fonction qui permet de créditer un compte
 * @param int $idAccount c'est l'id du compte
 * @param string $amount c'est le montant à créditer
 * @return void
*/
function ctlCredit($idAccount, $amount){
    modCredit($idAccount, $amount);
    $idClient = modGetIdClientFromAccount($idAccount)->idClient;
    $client = modGetClientFromId($idClient);
    vueDisplayInfoClient($client, ctrGetAccount($idClient),ctrGetContracts($idClient));
}

/**
 * Fonction qui permet d'afficher les erreurs
 * @param string $error c'est le message d'erreur
 * @return void
 */
function ctlError($error) {
    vueDisplayError($error);
}
/**
 * Fonction qui permet d'afficher les statistiques
 * @return array c'est un tableau (map) avec les statistiques
 */
function ctlGetStats(){
    $stat = array();
    $stat['nbClient'] = modGetNumberClients();
    $stat['nbAccount'] = modGetNumberAccounts();
    $stat['nbContract'] = modGetNumberContracts();
    $stat['nbConseiller'] = modGetNumberCounselors();
    $stat['nbAgent'] = modGetNumberAgents();
    $stat['nbTypeAccount'] = modGetNumberAccountTypes();
    $stat['nbTypeContract'] = modGetNumberContractTypes();
    $stat['nbAccountActive'] = modGetNumberActiveAccounts();
    $stat['nbAccountInactif'] = modGetNumberInactiveAccounts();
    $stat['nbAccountDecouvert'] = modGetNumberOverdraftAccounts();
    $stat['nbAccoutNonDecouvert'] = modGetNumberNonOverdraftAccounts();
    return $stat;
}

function ctlGestionPersonnelAll(){
    $listEmploye = modGetAllEmployes();
    vueDisplayGestionPersonnelAll($listEmploye);
}
function ctlGestionPersonnelOne($idEmploye){
    $employee = modGetEmployeFromId($idEmploye);
    vueDisplayGestionPersonnelOne($employee);
}

function ctlGestionPersonnelOneSubmit($idEmployee, $name, $firstName, $login, $password, $category, $color){
    modModifEmploye($idEmployee, $name, $firstName, $login, $password, $category, $color);
    ctlGestionPersonnelAll();
}


function ctlGestionServiceslAll(){
    $listTypeAccount = modGetAllAccountTypes();
    $listTypeContract = modGetAllContractTypes();
    vueDisplayGestionServicesAll($listTypeAccount, $listTypeContract);
}


function ctlGestionAccountOne($idAccount){
    $account = modGetTypeAccount($idAccount);
    vueDisplayGestionAccountOne($account);
}



function ctlGestionContractOne($idContract){
    $contract = modGetContractFromId($idContract);
    vueDisplayGestionContractOne($contract);
}

function ctlGestionAccountOneSubmit($idAccount, $name, $active){
    modModifTypeAccount($idAccount, $name, $active);
    ctlGestionServiceslAll();
}

function ctlGestionContractOneSubmit($idContract, $name, $active){
    modModifTypeContract($idContract, $name, $active);
    ctlGestionServiceslAll();
}


function ctlGestionMotifAll(){
    $listMotif = modGetAllMotif();
    vueDisplayGestionMotifAll($listMotif);
}

function ctlGestionMotifOne($idMotif){
    $motif = modGetMotifFromId($idMotif);
    vueDisplayGestionMotifOne($motif);
}

function ctlGestionMotifOneSubmit($idMotif, $name, $document){
    modModifMotif($idMotif, $name, $document);
    ctlGestionMotifAll();
}

























function ctlGetIntituleCategorie($idCategorie){
    $intitule = modGetIntituleCategorie($idCategorie);
    return $intitule;
}


/**
 * Fonction qui renvoie les informations d'un employé
 * @param int $idEmploye c'est l'id de l'employé
 * @return object $employee c'est les informations de l'employé
 */
function ctlGetInfoEmploye($idEmploye) {
    $employee = modGetEmployeFromId($idEmploye);
    return $employee;
}

function ctlRDVBetween($dateStartOfWeek, $dateEndOfWeek){
    $listRDV = modGetAllAppoinmentsBetween($dateStartOfWeek->format('Y-m-d'), $dateEndOfWeek->format('Y-m-d'));
    // $listTA = modGetAllAdminBetween($dateStartOfWeek->format('Y-m-d'), $dateEndOfWeek->format('Y-m-d'));
    $array = new ArrayObject();
    $array->append($listRDV);
    // $array->append($listTA);
    $array->append([]);
    $array->append($dateStartOfWeek);
    return $array;
}

function ctlUpdateCalendar($targetDate) {
    $targetDate = ($targetDate instanceof DateTime) ? $targetDate : date_create($targetDate);
    debug("ctlUpdateCalendar(".json_encode($targetDate).")");
    $array = ctlRDVBetween(getMondayOfWeek($targetDate), getSundayOfWeek($targetDate));
    $identity = modGetEmployeFromId($_SESSION["idEmploye"])->NOM;
    vueDisplayHomeAgent($array[0], $array[1], $array[2], $identity);
}


function getMondayOfWeek($date) {
    $date = ($date instanceof DateTime) ? $date : date_create($date);
    $dayOfWeek = date_format($date, 'N');
    if ($dayOfWeek == 1) {
        return $date;
    } else {
        $mondayOfWeek = date_create(date('Y-m-d', strtotime('previous monday', $date->getTimestamp())));
        return $mondayOfWeek;
    }
}

function getSundayOfWeek($date) {
    $date = ($date instanceof DateTime) ? $date : date_create($date);
    $dayOfWeek = date_format($date, 'N');
    if ($dayOfWeek == 7) {
        return $date;
    } else {
        $sundayOfWeek = date_create(date('Y-m-d', strtotime('next sunday', $date->getTimestamp())));
        return $sundayOfWeek;
    }
}


function debug($what = "debugString") {
    echo("<script>console.log(". json_encode($what) .")</script>");
}