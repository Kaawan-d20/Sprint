<?php
require_once('modele/modele.php');
require_once('vue/vue.php');
require_once('Exception.php');

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fonction qui affiche la page d'accueil en fonction de la catégorie de l'utilisateur
 * Si l'utilisateur n'est pas connecté, affiche la page de login
 * @return void
 */
function ctlHome (){
    if (!isset($_SESSION["type"])){
        vueDisplayLogin();
    }
    elseif ($_SESSION["type"] == 1){
        $stat = ctlGetStats();
        vueDisplayHomeDirecteur($stat, $_SESSION["name"]);
    }
    elseif ($_SESSION["type"] == 2){
        ctlUpdateCalendarConseiller(new DateTime("now"));
    }
    elseif ($_SESSION["type"] == 3){
        ctlUpdateCalendar(new DateTime("now"));
    }
}

/**
 * Fonction qui permet de se connecter
 * C'est ici que l'on initialise la session 
 * @param string $username C'est le nom d'utilisateur qui est le login dans la base de données
 * @param string $password C'est le mot de passe de l'utilisateur mais il est haché et salé
 * @throws incorrectLoginException Si le login ou le mot de passe est incorrect
 * @return void
 */
function ctlLogin($username, $password){
    $BDPWD = modGetPassword($username);
    if (password_verify($password, $BDPWD)){
        $resultConnect = modGetEmployeFromLogin($username);
        $_SESSION["idEmploye"] = $resultConnect->IDEMPLOYE;
        $_SESSION["type"] = $resultConnect->IDCATEGORIE;
        $_SESSION["name"] = $resultConnect->NOM;
        $_SESSION["firstName"] = $resultConnect->PRENOM;
        $_SESSION["listClient"] = modGetAllClients();
        ctlHome();
    }
    else{
        throw new incorrectLoginException();
    }

}

/**
 * Fonction qui permet de se déconnecter
 * @return void
 */
function ctlLogout() {
    session_destroy();
    vueDisplayLogin();
}

/**
 * Fonction qui permet d'afficher les erreurs
 * @param string $error C'est le message d'erreur
 * @return void
 */
function ctlError($error) {
    vueDisplayError($error);
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- SERVICE ----------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model de récupérer les informations des type de compte et de contrat puis appel la page de gestion des services
 * @return void
 */
function ctlGestionServicesAll(){
    $listTypeAccount = modGetAllAccountTypes();
    $listTypeContract = modGetAllContractTypes();
    vueDisplayGestionServicesAll($listTypeAccount, $listTypeContract);
}

/**
 * Fonction qui demande à la vue d'afficher la page de création d'un type de compte ou de contrat
 * @return void
 */
function ctlGestionServicesAdd(){
    vueDisplayGestionServicesAdd();
}

/**
 * Fonction qui demande au model d'ajouter un type de compte ou de contrat puis appel la page de gestion des services
 * @param string $name C'est le nom du type de compte ou de contrat
 * @param int $type C'est le type de service (1 pour compte, 2 pour contrat)
 * @param int $active C'est si le service est actif ou non (1 pour actif, 0 pour inactif)
 * @param string $document C'est le document du service
 * @throws existingTypeAccountException Si le type de compte existe déjà
 * @throws existingTypeContractException Si le type de contrat existe déjà
 * @return void
 */
function ctlGestionServicesAddSubmit($name, $type, $active, $document){
    if ($type == 1){
    
        if (in_array($name, ctlGetTypeAccount())){
            throw new existingTypeAccountException();
        }
        modAddTypeAccount($name, $active, $document);
    }
    elseif ($type == 2){
        if (in_array($name, ctlGetTypeContract())){
            throw new existingTypeContractException();
        }
        modAddTypeContract($name, $active, $document);
    }
    ctlGestionServicesAll();
}



// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- TYPE COMPTE ------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model de modifier un type de compte puis appel la page de gestion des services
 * @param int $idAccount C'est l'id du type de compte
 * @param string $name C'est le nom du type de compte
 * @param int $active C'est si le type de compte est actif ou non (1 pour actif, 0 pour inactif)
 * @param string $document C'est le document du type de compte
 * @param int $idMotif C'est l'id du motif de modification
 * @return void
 */
function ctlGestionAccountOneSubmit($idAccount, $name, $active, $document, $idMotif){
    modModifTypeAccount($idAccount, $name, $active, $document, $idMotif);
    ctlGestionServicesAll();
}

/**
 * Fonction qui demande au model de supprimer un type de compte puis appel la page de gestion des services
 * @param int $idAccount C'est l'id du type de compte
 * @return void
 */
function ctlGestionAccountDelete($idAccount){
    modDeleteTypeAccount($idAccount);
    ctlGestionServicesAll();
}

/**
 * Fonction qui demande au model de récupérer les informations des type de compte
 * @return array C'est la liste des nom de types de compte
 */
function ctlGetTypeAccount(){
    $listTypeAccount = modGetAllAccountTypes();
    $list = array();
    foreach ($listTypeAccount as $typeAccount){
        array_push($list, $typeAccount->NOM);
    }
    return $list;
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- TYPE CONTRAT -----------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model de supprimer un type de contrat puis appel la page de gestion des services
 * @param int $idContract C'est l'id du type de contrat
 * @return void
 */
function ctlGestionContractDelete($idContract){
    modDeleteTypeContract($idContract);
    ctlGestionServicesAll();
}

/**
 * Fonction qui demande au model de modifier un type de contrat puis appel la page de gestion des services
 * @param int $idContract C'est l'id du type de contrat
 * @param string $name C'est le nom du type de contrat
 * @param int $active C'est si le type de contrat est actif ou non (1 pour actif, 0 pour inactif)
 * @param string $document C'est le document du type de contrat
 * @param int $idMotif C'est l'id du motif de modification
 * @return void
 */
function ctlGestionContractOneSubmit($idContract, $name, $active, $document, $idMotif){
    modModifTypeContract($idContract, $name, $active, $document, $idMotif);
    ctlGestionServicesAll();
}

/**
 * Fonction qui demande au model de récupérer les informations des type de contrat
 * @return array C'est la liste des nom de types de contrat
 */
function ctlGetTypeContract(){
    $listTypeContract = modGetAllContractTypes();
    $list = array();
    foreach ($listTypeContract as $typeContract){
        array_push($list, $typeContract->NOM);
    }
    return $list;
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- COMPTE -----------------------------------------------------
// ------------------------------------------------------------------------------------------------------




/**
 * Fonction qui permet de débiter un compte
 * @param int $idAccount C'est l'id du compte
 * @param string $amount C'est le montant à débiter
 * @param int $idClient C'est l'id du client
 * @throws soldeInsuffisantException Si le montant est supérieur au solde et au découvert
 * @return void
 */
function ctlDebit($idAccount, $amount, $idClient){
    $account = modGetInfoAccount($idAccount);
    if ($amount > $account->solde + $account->decouvert){
        throw new soldeInsuffisantException();
    }
    modDebit($idAccount, $amount);
    ctlSearchIdClient($idClient);
}

/**
 * Fonction qui permet de créditer un compte
 * @param int $idAccount C'est l'id du compte
 * @param string $amount C'est le montant à créditer
 * @param int $idClient C'est l'id du client
 * @return void
*/
function ctlCredit($idAccount, $amount, $idClient){
    modCredit($idAccount, $amount);
    ctlSearchIdClient($idClient);
}

/**
 * Fonction qui demande au model de récupérer tout les operation de tout les comptes d'un client
 * @param int $idClient C'est l'id du client
 * @return array C'est un tableau avec les opérations de tout les comptes du client (map idCompte => opérations (IDOPERATION, IDCOMPTE, SOURCE, LIBELLE, DATEOPERATION, MONTANT, ISCREDIT) (tableau d'objets))
 */
function ctlGetOperation($idClient){
    $accounts=modGetAccounts($idClient);
    $array = array();
    foreach ($accounts as $account){
        $array["$account->idCompte"]=(modGetOperations($account->idCompte));
    }
    return $array;
}

/**
 * Fonction qui demande au model de modifier le découvert d'un compte puis appel la synthèse du client
 * @param int $idAccount C'est l'id du compte
 * @param string $overdraft C'est le découvert du compte
 * @param int $idClient C'est l'id du client
 * @return void
*/
function ctlModifOverdraft($idAccount, $overdraft, $idClient){
    modModifOverdraft($idAccount, $overdraft);
    ctlSearchIdClient($idClient);
}

/**
 * Fonction qui demande au model de récupérer la liste des types de compte actif
 * Puis demande à la vue d'afficher la page de création d'un compte
 * @param int $idClient C'est l'id du client
 * @return void
 */
function ctlAddAccount($idClient){
    $listTypeAccount = modGetAllAccountTypesEnable();
    $listAllClient = $_SESSION["listClient"];
    vueDisplayAddAccount($idClient, $listTypeAccount, $listAllClient);
}

/**
 * Fonction qui demande au model de créer un compte puis appel la synthèse du client
 * @param int $idClient C'est l'id du client
 * @param string $overdraft C'est le découvert du compte
 * @param int $idTypeAccount C'est l'id du type de compte
 * @param int $idClient2 C'est l'id du deuxième client (si c'est un compte joint)
 * @throws clientIncorrectException Si le client veut créer un compte avec lui même
 * @throws existingAccountException Si le client a déjà un compte de ce type
 * @throws existingAccountException Si le deuxième client a déjà un compte de ce type
 * @return void
 */
function ctlCreateAccount($idClient, $overdraft, $idTypeAccount, $idClient2=""){
    if ($idClient2 == $idClient){
        throw new clientIncorrectException();
    }
    $listAccount = modGetAccounts($idClient);
    foreach ($listAccount as $account){
        if ($account->idtypecompte == $idTypeAccount){
            throw new existingAccountException();
        }
    }
    if ($idClient2 == ""){
        modAddAccountToClientOne($idClient, $overdraft, $idTypeAccount);
    }
    else{
        $listAccount = modGetAccounts($idClient2);
        foreach ($listAccount as $account){
            if ($account->idtypecompte == $idTypeAccount){
                throw new existingAccountException('Compte déjà existant pour la deuxième personne');
            }
        }
        modAddAccountToClientTwo($idClient, $idClient2, $overdraft, $idTypeAccount);
    }
    ctlSearchIdClient($idClient);
}

/**
 * Fonction qui demande au model de supprimer un compte puis appel la synthèse du client
 * @param int $idAccount C'est l'id du compte
 * @param int $idClient C'est l'id du client
 * @return void
 */
function ctlDeleteAccount($idAccount, $idClient){
    modDeleteAccount($idAccount);
    ctlSearchIdClient($idClient);
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- CONTRAT ----------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model de récupérer la liste des types de contrat actif
 * Puis demande à la vue d'afficher la page de création d'un contrat
 * @param int $idClient C'est l'id du client
 * @return void
 */
function ctlAddContract($idClient){
    $listTypeContract = modGetAllContractTypesEnable();
    $listAllClient = $_SESSION["listClient"];
    vueDisplayAddContract($idClient, $listTypeContract, $listAllClient);
}

/**
 * Fonction qui demande au model de créer un contrat puis appel la synthèse du client
 * @param int $idClient C'est l'id du client
 * @param string $monthCost C'est le coût mensuel du contrat
 * @param int $idTypeContract C'est l'id du type de contrat
 * @param int $idClient2 C'est l'id du deuxième client (si c'est un compte joint)
 * @throws clientIncorrectException Si le client veut créer un compte avec lui même
 * @return void
 */
function ctlCreateContract($idClient, $monthCost, $idTypeContract, $idClient2=""){
    if ($idClient2 == $idClient){
        throw new clientIncorrectException();
    }
    elseif ($idClient2 == ""){
        modAddContractToClientOne($idClient, $monthCost, $idTypeContract);
    }
    else{
        modAddContractToClientTwo($idClient, $idClient2, $monthCost, $idTypeContract);
    }
    ctlSearchIdClient($idClient);
}

/**
 * Fonction qui demande au model de supprimer un contrat puis appel la synthèse du client
 * @param int $idContract C'est l'id du contrat
 * @return void
*/
function ctlDeleteContract($idContract, $idClient){
    modDeleteContract($idContract);
    ctlSearchIdClient($idClient);
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- CLIENT -----------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui permet de chercher un client en fonction de son idClient
 * @param int $idClient C'est l'id du client
 * @throws isEmptyException Si l'id est vide
 * @throws notFoundClientException Si aucun client n'est trouvé
 * @return void
 */
function ctlSearchIdClient($idClient){
    if ($idClient == '') {
        throw new isEmptyException();
    }
    $client = modGetClientFromId($idClient);
    if (empty($client)){
        throw new notFoundClientException();
    }
    vueDisplayInfoClient($client, modGetAccounts($idClient), modGetContracts($idClient), ctlGetOperation($idClient), modGetAppointmentsClient($idClient), modGetAllCounselors());
}

/**
 * Fonction qui permet de chercher un client en fonction de son nom, prénom et date de naissance
 * @param string $nameClient C'est le nom du client
 * @param string $firstNameClient C'est le prénom du client
 * @param string $dateOfBirth C'est la date de naissance du client
 * @throws isEmptyException Si tous les champs sont vides
 * @throws notFoundClientException Si aucun client n'est trouvé
 * @return void
 */
function cltAdvanceSearchClient($nameClient, $firstNameClient, $dateOfBirth) {
    if (empty($nameClient) && empty($firstNameClient) && empty($dateOfBirth)) { // Aucun champ rempli
        throw new isEmptyException('Veuillez remplir au moins un champ');
    }
    elseif (!empty($nameClient) && !empty($firstNameClient) && !empty($dateOfBirth)){ // Il y a tout de rempli
        $listClient=modAdvancedSearchClientABC($nameClient, $firstNameClient, $dateOfBirth);
    }
    elseif (!empty($nameClient) && !empty($firstNameClient) && empty($dateOfBirth)){ // Il y a que le nom et le prénom
        $listClient=modAdvancedSearchClientAB($nameClient, $firstNameClient);
    }
    elseif (!empty($nameClient) && empty($firstNameClient) && !empty($dateOfBirth)){ // Il y a que le nom et la date de naissance
        $listClient=modAdvancedSearchClientAC($nameClient, $dateOfBirth);
    }
    elseif (empty($nameClient) && !empty($firstNameClient) && !empty($dateOfBirth)){ // Il y a que le prénom et la date de naissance
        $listClient=modAdvancedSearchClientBC($firstNameClient, $dateOfBirth);
    }
    elseif (!empty($nameClient) && empty($firstNameClient) && empty($dateOfBirth)){ // Il y a que le nom
        $listClient=modAdvancedSearchClientA($nameClient);
    }
    elseif (empty($nameClient) && !empty($firstNameClient) && empty($dateOfBirth)){ // Il y a que le prénom
        $listClient=modAdvancedSearchClientB($firstNameClient);
    }
    elseif (empty($nameClient) && empty($firstNameClient) && !empty($dateOfBirth)){ // Il y a que la date de naissance
        $listClient=modAdvancedSearchClientC($dateOfBirth);
    }
    if (empty($listClient)){
        throw new notFoundClientException();
    }
    else{
        vueDisplayAdvanceSearchClient($listClient);
    }
}

/**
 * Fonction qui demande à la vue d'afficher la page de création d'un client
 * @return void
 */
function ctlDisplayNewClientForm()  {
    vueDisplayCreateClient(modGetAllCounselors());
}

/**
 * Fonction qui demande au model de créer un client puis appel la page d'accueil
 * Update la liste des clients dans la session
 * @param int $idEmployee C'est l'id de l'employé qui a créé le client
 * @param string $name C'est le nom du client
 * @param string $firstName C'est le prénom du client
 * @param string $dateOfBirth C'est la date de naissance du client
 * @param string $address C'est l'adresse du client
 * @param string $phone C'est le numéro de téléphone du client
 * @param string $email C'est l'email du client
 * @param string $profession C'est la profession du client
 * @param string $situation C'est la situation du client
 * @param string $civilite C'est la civilité du client
 * @return void
 */
function ctlAddClient($idEmployee, $name, $firstName, $dateOfBirth, $address, $phone, $email, $profession, $situation, $civilite){
    modCreateClient($idEmployee, $name, $firstName, $dateOfBirth, $address, $phone, $email, $profession, $situation, $civilite);
    $_SESSION["listClient"] = modGetAllClients();
    ctlHome();
}

/**
 * Fonction qui demande au model de modifier un client puis appel la synthèse du client
 * @param int $idClient C'est l'id du client
 * @param int $idConseiller C'est l'id du conseiller du client
 * @param string $profession C'est la profession du client
 * @param string $situation C'est la situation du client
 * @param string $address C'est l'adresse du client
 * @param string $phone C'est le numéro de téléphone du client
 * @param string $email C'est l'email du client
 * @param string $naissance C'est la date de naissance du client
 * @return void
 */
function ctlEditClient($idClient, $idConseiller, $profession, $situation, $address, $phone, $email, $naissance){
    modModifClient($idClient, $idConseiller, $profession, $situation, $address, $phone, $email, $naissance);
    ctlSearchIdClient($idClient);
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- EMPLOYE ----------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui de demander au model la liste des employés et de l'envoyer à la vue
 * @return void
 */
function ctlGestionPersonnelAll(){
    $listEmploye = modGetAllEmployes();
    vueDisplayGestionPersonnelAll($listEmploye);
}

/**
 * Fonction qui demande à la vue d'afficher la page de création d'un employé
 * @return void
 */
function ctlGestionPersonnelAdd(){
    vueDisplayGestionPersonnelAdd();
}

/**
 * Fonction qui demande au model de modifier un employé puis appel la page de gestion du personnel
 * @param int $idEmployee C'est l'id de l'employé
 * @param string $name C'est le nom de l'employé
 * @param string $firstName C'est le prénom de l'employé
 * @param string $login C'est le login de l'employé
 * @param string $password C'est le mot de passe de l'employé (haché dans le JS en SHA256)
 * @param int $category C'est la catégorie de l'employé
 * @param string $color C'est la couleur de l'employé
 * @return void
 */
function ctlGestionPersonnelOneSubmit($idEmployee, $name, $firstName, $login, $password, $category, $color){
    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]); // Hachage et salage du mot de passe
    modModifEmploye($idEmployee, $name, $firstName, $login, $password, $category, $color);
    ctlGestionPersonnelAll();
}

/**
 * Fonction qui demande au model d'ajouter un employé puis appel la page de gestion du personnel
 * @param string $name C'est le nom de l'employé
 * @param string $firstName C'est le prénom de l'employé
 * @param string $login C'est le login de l'employé
 * @param string $password C'est le mot de passe de l'employé (haché dans le JS en SHA256)
 * @param int $category C'est la catégorie de l'employé
 * @param string $color C'est la couleur de l'employé
 * @return void
 */
function ctlGestionPersonnelAddSubmit($name, $firstName, $login, $password, $category, $color){
    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]); // Hachage et salage du mot de passe
    modAddEmploye($category, $name, $firstName, $login, $password, $color);
    ctlGestionPersonnelAll();
}

/**
 * Fonction qui demande au model de supprimer un employé puis appel la page de gestion du personnel
 * @param int $idEmployee C'est l'id de l'employé
 * @return void
 */
function ctlGestionPersonnelDelete($idEmployee){
    modDeleteEmploye($idEmployee);
    ctlGestionPersonnelAll();
}

/**
 * Fonction qui demande à la vue d'afficher la page de modification des paramètres d'un employé
 * @return void
 */
function ctlSetting(){
    $identity = modGetEmployeFromId($_SESSION["idEmploye"]);
    vueDisplaySetting($identity);
}

/**
 * Fonction qui demande au model de modifier les paramètres d'un employé puis appel la page d'accueil
 * @param int $idEmploye C'est l'id de l'employé
 * @param string $login C'est le login de l'employé
 * @param string $password C'est le mot de passe de l'employé
 * @param string $color C'est la couleur de l'employé
 * @return void
 */
function ctlSettingSubmit($idEmploye, $login, $password, $color){
    if ($password == ''){
        $password = modGetEmployeFromId($idEmploye)->PASSWORD;
    }
    else{
        $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]); // Hachage et salage du mot de passe
    }
    modModifEmployeSetting($idEmploye, $login, $password, $color);
    ctlHome();
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- AGENDA -----------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model de récupérer les RDV et les tâches administratives entre deux dates
 * @param DateTime $dateStartOfWeek C'est la date de début de la semaine
 * @param DateTime $dateEndOfWeek C'est la date de fin de la semaine
 * @return ArrayObject C'est un tableau avec les RDV, les tâches administratives et la date de début de la semaine
 */
function ctlRDVBetween($dateStartOfWeek, $dateEndOfWeek){
    $dateStartOfWeekString = $dateStartOfWeek->format('Y-m-d') . " 00:00:00";
    $dateEndOfWeekString = $dateEndOfWeek->format('Y-m-d') . " 23:59:59";

    $listRDV = modGetAllAppointmentsBetween($dateStartOfWeekString, $dateEndOfWeekString);
    $listTA = modGetAllTABetween($dateStartOfWeekString, $dateEndOfWeekString);

    $event = array_merge($listRDV, $listTA);
    usort($event, function($a, $b) {
        return $a->HORAIREDEBUT > $b->HORAIREDEBUT;
    });

    $array = new ArrayObject();
    $array->append($event);
    $array->append($dateStartOfWeek);
    return $array;
}

/**
 * Fonction qui permet de récupérer les évènements d'une semaine
 * Fonction qui demande à une fonction du contrôleur la liste des RDV et des tâches administratives pour une semaine
 * @param DateTime|string $targetDate C'est la date de la semaine
 * @return void
 */
function ctlUpdateCalendarConseiller($targetDate) {
    $targetDate = ($targetDate instanceof DateTime) ? $targetDate : date_create($targetDate);
    $array = ctlRDVBetween(getMondayOfWeek($targetDate), getSundayOfWeek($targetDate));
    $fullName = $_SESSION["firstName"]." ".$_SESSION["name"];
    vueDisplayHomeConseiller($array[0], $array[1], $_SESSION["name"], $fullName);
}

/**
 * Fonction qui permet de récupérer les évènements d'une semaine
 * Fonction qui demande à une fonction du contrôleur la liste des RDV et des tâches administratives pour une semaine
 * @param DateTime|string $targetDate C'est la date de la semaine
 * @return void
 */
function ctlUpdateCalendar($targetDate) {
    $targetDate = ($targetDate instanceof DateTime) ? $targetDate : date_create($targetDate);
    $array = ctlRDVBetween(getMondayOfWeek($targetDate), getSundayOfWeek($targetDate));
    vueDisplayHomeAgent($array[0], $array[1], $_SESSION["name"]);
}

/**
 * Fonction qui renvoie la date du lundi de la semaine de la date donnée
 * @param DateTime $date C'est la date
 * @return DateTime C'est la date du lundi de la semaine
 */
function getMondayOfWeek($date) {
    $dayOfWeek = date_format($date, 'N');
    if ($dayOfWeek == 1) {
        return $date;
    } else {
        $mondayOfWeek = date_create(date('Y-m-d', strtotime('previous monday', $date->getTimestamp())));
        return $mondayOfWeek;
    }
}

/**
 * Fonction qui renvoie la date du dimanche de la semaine de la date donnée
 * @param DateTime $date C'est la date
 * @return DateTime C'est la date du dimanche de la semaine
 */
function getSundayOfWeek($date) {
    $dayOfWeek = date_format($date, 'N');
    if ($dayOfWeek == 7) {
        return $date;
    } else {
        $sundayOfWeek = date_create(date('Y-m-d', strtotime('next sunday', $date->getTimestamp())));
        return $sundayOfWeek;
    }
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- RDV --------------------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model la liste des conseillers, des clients et des motifs
 * Puis demande à la vue d'afficher la page de création d'un rendez-vous
 * Version Agent d'accueil
 * @param DateTime|string $date C'est la date du rendez-vous
 * @return void
 */
function ctlDisplayAddAppointment($date) {
    $dateTime = ($date instanceof DateTime) ? $date : date_create($date);
    $listConseillers = modGetAllCounselors();
    $listClients = modGetAllClients();
    $listMotifs = modGetAllMotif();
    $rdvArray = ctlRDVBetween($dateTime, $dateTime)[0];
    vueDisplayAddAppointment($listConseillers, $listClients, $listMotifs, $date, $rdvArray);
}

/**
 * Fonction qui demande au model la liste des conseillers, des clients et des motifs
 * Puis demande à la vue d'afficher la page de création d'un rendez-vous
 * Version Conseiller
 * @param DateTime|string $date C'est la date du rendez-vous
 * @return void
 */
function ctlDisplayAddAppointmentConseiller($date) {
    $dateTime = ($date instanceof DateTime) ? $date : date_create($date);
    $listClients = modGetAllClientsByCounselors($_SESSION["idEmploye"]);
    $listMotifs = modGetAllMotif();
    $rdvArray = ctlRDVBetween($dateTime, $dateTime)[0];
    vueDisplayAddAppointmentConseiller($listClients, $listMotifs, $date, $rdvArray);
}

/**
 * Fonction qui demande au model de créer un rendez-vous puis appel la page d'accueil
 * C'est ici que l'on vérifie si le rendez-vous est possible ou non
 * @param int $idClient C'est l'id du client
 * @param int $idEmployee C'est l'id du conseiller
 * @param DateTime|string $date C'est la date du rendez-vous
 * @param string $heureDebut C'est l'heure de début du rendez-vous
 * @param string $heureFin C'est l'heure de fin du rendez-vous
 * @param int $idMotif C'est l'id du motif du rendez-vous
 * @throws appointmentHoraireException Si le rendez-vous est impossible car il y a un autre rendez-vous ou une tâche administrative à ce moment là
 * @throws HoraireException Si l'heure de début est supérieur ou égale à l'heure de fin
 * @throws notFoundClientException Si le client n'existe pas
 * @return void
 */
function ctlCreateNewAppointment($idClient, $idEmployee, $date, $heureDebut, $heureFin, $idMotif) {
    if ($heureDebut > $heureFin) {
        throw new HoraireException();
    }
    if (empty(modGetClientFromId($idClient))){
        throw new notFoundClientException();
    }
    $horaireDebut= $date.' '.$heureDebut.':00';
    $horaireFin= $date.' '.$heureFin.':00';
    $debutCall = $date . ' 00:00:00';
    $finCall = $date . ' 23:59:59';
    $listAppointment = modGetAppointmentsBetweenCounselor($idEmployee,$debutCall,$finCall);
    $listTA = modGetTABetweenCounselor($idEmployee,$debutCall,$finCall);
    foreach ($listAppointment as $appointment){
        if ($horaireDebut < $appointment->HORAIREDEBUT && $appointment->HORAIREDEBUT < $horaireFin){
            throw new appointmentHoraireException();
        }
        if ($horaireDebut < $appointment->HORAIREFIN && $appointment->HORAIREFIN < $horaireFin){
            throw new appointmentHoraireException();
        }
        if ($horaireDebut >= $appointment->HORAIREDEBUT && $horaireFin <= $appointment->HORAIREFIN){
            throw new appointmentHoraireException();
        }
    }
    foreach ($listTA as $TA){
        if ($horaireDebut < $TA->HORAIREDEBUT && $TA->HORAIREDEBUT < $horaireFin){
            throw new appointmentHoraireException();
        }
        if ($horaireDebut < $TA->HORAIREFIN && $TA->HORAIREFIN < $horaireFin){
            throw new appointmentHoraireException();
        }
        if ($horaireDebut >= $TA->HORAIREDEBUT && $horaireFin <= $TA->HORAIREFIN){
            throw new appointmentHoraireException();
        }
    }
    modAddAppointment($idMotif, $idClient, $idEmployee, $horaireDebut, $horaireFin);
    ctlHome();
}

/**
 * Fonction qui demande au model de supprimer un rendez-vous puis appel la page d'accueil
 * @param int $idAppointment C'est l'id du rendez-vous
 * @return void
 */
function ctlDeleteAppointment($idAppointment) {
    modDeleteAppointment($idAppointment);
    ctlHome();
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- Tache administrative -------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui demande au model de créer une tâche administrative puis appel la page d'accueil
 * C'est ici que l'on vérifie si la tâche administrative est possible ou non
 * @param int $idEmployee C'est l'id du conseiller
 * @param DateTime|string $date C'est la date de la tâche administrative
 * @param string $heureDebut C'est l'heure de début de la tâche administrative
 * @param string $heureFin C'est l'heure de fin de la tâche administrative
 * @param string $libelle C'est le libellé de la tâche administrative
 * @throws TAHoraireException Si la tâche administrative est impossible car il y a un autre rendez-vous ou une tâche administrative à ce moment là
 * @return void
 */
function ctlCreateNewTA($idEmployee, $date, $heureDebut, $heureFin, $libelle) {
    if ($heureDebut < $heureFin) {
        $horaireDebut= $date.' '.$heureDebut.':00';
        $horaireFin= $date.' '.$heureFin.':00';
        $debutCall = $date . ' 00:00:00';
        $finCall = $date . ' 23:59:59';
        $listAppointment = modGetAppointmentsBetweenCounselor($idEmployee,$debutCall,$finCall);
        $listTA = modGetTABetweenCounselor($idEmployee,$debutCall,$finCall);
        foreach ($listAppointment as $appointment){
            if ($horaireDebut < $appointment->HORAIREDEBUT && $appointment->HORAIREDEBUT < $horaireFin){
                throw new TAHoraireException();
            }
            if ($horaireDebut < $appointment->HORAIREFIN && $appointment->HORAIREFIN < $horaireFin){
                throw new TAHoraireException();
            }
            if ($horaireDebut >= $appointment->HORAIREDEBUT && $horaireFin <= $appointment->HORAIREFIN){
                throw new TAHoraireException();
            }
        }
        foreach ($listTA as $TA){
            if ($horaireDebut < $TA->HORAIREDEBUT && $TA->HORAIREDEBUT < $horaireFin){
                throw new TAHoraireException();
            }
            if ($horaireDebut < $TA->HORAIREFIN && $TA->HORAIREFIN < $horaireFin){
                throw new TAHoraireException();
            }
            if ($horaireDebut >= $TA->HORAIREDEBUT && $horaireFin <= $TA->HORAIREFIN){
                throw new TAHoraireException();
            }
        }
        modCreateTA($idEmployee, $horaireDebut, $horaireFin, $libelle);
        ctlHome();
    }
}

/**
 * Fonction qui demande au model de supprimer une tâche administrative puis appel la page d'accueil
 * @param int $idTA C'est l'id de la tâche administrative
 * @return void
 */
function ctlDeleteTA($idTA) {
    modDeleteTA($idTA);
    ctlHome();
}


// ------------------------------------------------------------------------------------------------------
// ----------------------------------------- STATISTIQUES -----------------------------------------------
// ------------------------------------------------------------------------------------------------------


/**
 * Fonction qui permet d'afficher les statistiques
 * @return array C'est un tableau (map) avec les statistiques
 */
function ctlGetStats($dateStart="", $dateEnd="", $date=""){
    if ($dateStart == ''){
        $dateStart = (new DateTime('monday this week'))->format('Y-m-d');
    }
    if ($dateEnd == ''){
        $dateEnd = (new DateTime('sunday this week'))->format('Y-m-d');
    }
    if ($date == ''){
        $date = (new DateTime('today'))->format('Y-m-d');
    }
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
    $stat['nbContractActive'] = modGetNumberActiveContracts();
    $stat['nbContractInactif'] = modGetNumberInactiveContracts();
    $stat['nbAccountDecouvert'] = modGetNumberOverdraftAccounts();
    $stat['nbAccountNonDecouvert'] = modGetNumberNonOverdraftAccounts();
    $stat['sumAccount'] = modSumAllSolde();
    $stat['AppointmentBetween'] = modGetNumberAppointmentsBetween($dateStart, $dateEnd);
    $stat['ContractBetween'] = modGetNumberContractsBetween($dateStart, $dateEnd);
    $stat['nbClientAt'] = modGetNumberClientsAt($date);
    return $stat;
}

/**
 * Fonction qui demande la génération des statistiques et l'envoie à la vue
 * @param string $dateStart C'est la date de début
 * @param string $dateEnd C'est la date de fin
 * @param string $date C'est une date
 */
function ctlStatsDisplay($dateStart="", $dateEnd="", $date=""){
    if ($dateStart == ''){
        $dateStart = (new DateTime('monday this week'))->format('Y-m-d');
    }
    if ($dateEnd == ''){
        $dateEnd = (new DateTime('sunday this week'))->format('Y-m-d');
    }
    if ($date == ''){
        $date = (new DateTime('today'))->format('Y-m-d');
    }
    $stat = ctlGetStats($dateStart, $dateEnd, $date);
    vueDisplayHomeDirecteur($stat, $_SESSION["name"]);
}





/**
 * Fonction qui permet d'afficher tout ce que l'on veut
 * @param string $element C'est ce que l'on veut afficher
 */
function debug($element = "debugString") {
    echo("<script>console.log(". json_encode($element) .")</script>");
}




/*
POUBELLE

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

function ctlGetIntituleCategorie($idCategorie){
    $intitule = modGetIntituleCategorie($idCategorie);
    return $intitule;
}


function ctlGestionPersonnelOne($idEmploye){
    $employee = modGetEmployeFromId($idEmploye);
    vueDisplayGestionPersonnelOne($employee);
}


function ctlGestionAccountOne($idAccount){
    $account = modGetTypeAccount($idAccount);
    vueDisplayGestionAccountOne($account);
}



function ctlGestionContractOne($idContract){
    $contract = modGetContractFromId($idContract);
    vueDisplayGestionContractOne($contract);
}




function ctlLogin ($username, $password) {
    if ($username == '' || $password == '') {
        throw new isEmptyException();
    }
    $resultConnnect = modConnect($username, $password);
    if (empty($resultConnnect)){
        throw new incorrectLoginException();
    }
    else{
        $_SESSION["idEmploye"] = $resultConnnect->IDEMPLOYE;
        $_SESSION["type"] = $resultConnnect->IDCATEGORIE;
        $_SESSION["name"] = $resultConnnect->NOM;
        $_SESSION["firstName"] = $resultConnnect->PRENOM;
        $_SESSION["listClient"] = modGetAllClients();
        ctlHome();
    }
}


function ctlGetContracts($idClient){
    $contracts = modGetContracts($idClient);
    return $contracts;
}


function ctlGetAccount($idClient){
    $account = modGetAccounts($idClient);
    return $account;
}

function ctlGetInfoEmploye($idEmploye) {
    $employee = modGetEmployeFromId($idEmploye);
    return $employee;
}


function ctlRDVDate($date) {
    $date = ($date instanceof DateTime) ? $date : date_create($date);

    $dateStart = $date->format('Y-m-d');
    $dateStart .= ' 00:00:00';
    $dateEnd = $date->format('Y-m-d');
    $dateEnd .= ' 23:59:59';

    $listRDV = modGetAllAppointmentsBetween($dateStart, $dateEnd);
    $listTA = modGetAllTABetween($dateStart, $dateEnd);

    $event = array_merge($listRDV, $listTA);
    usort($event, function($a, $b) {
        return $a->HORAIREDEBUT > $b->HORAIREDEBUT;
    });
    return $event;
}

*/