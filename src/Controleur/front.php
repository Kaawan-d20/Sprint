<?php

require_once('controleur/controleur.php');
require_once('modele/modele.php');
require_once('vue/vue.php');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}


try {
    // ------------------------------------------------------- Theme -------------------------------------------------------
    if(!isset($_COOKIE["Theme"])) { // Si le cookie pour le thème n'existe pas
        setcookie("Theme", "light", [
            'expires' => time() + (86400 * 30),
            'path' => '/',
            'samesite' => 'Lax', // You can use 'Strict', 'Lax', or 'None'
        ]);
    }
    // ------------------------------------------------------- Landing Page -------------------------------------------------------
    if (isset($_POST['landingSubmitBtn'])){ // Si le bouton de connexion est cliqué
        $username = $_POST['landingLoginField'];
        $password = $_POST['landingPasswordField'];
        ctlLogin($username, $password);
    }
    // ------------------------------------------------------- Nav Bar -------------------------------------------------------
    elseif (isset($_POST['searchClientBtn'])){ // Si le bouton de recherche de client par id (datalist) est cliqué
        $idClient = $_POST['searchClientByIdField'];
        ctlSearchIdClient($idClient);
    }
    elseif (isset($_POST['advancedSearchBtn'])){ // Si le bouton de recherche avancée de client est cliqué
        vueDisplayAdvanceSearchClient();
    }
    elseif (isset($_POST['disconnection'])){ // Si le bouton de déconnexion est cliqué
        ctlLogout();
    }
    elseif (isset($_POST['addClientBtn'])){ // Si le bouton d'ajout d'un client est cliqué
        ctlDisplayNewClientForm();
    }
    elseif (isset($_POST['createClientBtn'])){ // Si le bouton de validation d'ajout d'un client est cliqué
        $civilite = $_POST['civiClient'];
        $name = $_POST['nameClient'];
        $firstName = $_POST['firstNameClient'];
        $dateOfBirth = $_POST['dateOfBirthClient'];
        $address = $_POST['adressClient'];
        $phone = $_POST['phoneClient'];
        $email = $_POST['emailClient'];
        $profession = $_POST['professionClient'];
        $situation = $_POST['situationClient'];
        $idEmployee = $_POST['idEmployee'];
        ctlAddClient($civilite, $name, $firstName, $dateOfBirth, $address, $phone, $email, $profession, $situation, $idEmployee);
    }
    // ------------------------------------------------------- Recherche Client -------------------------------------------------------
    elseif (isset($_POST['advanceSearchClient'])){ // Si le bouton de recherche avancée de client (nom, prénom, date de naissance) est cliqué
        $name = $_POST['searchNameClientField'];
        $firstName = $_POST['searchFirstNameClientField'];
        $dateOfBirth = $_POST['searchBirthClientField'];
        cltAdvanceSearchClient($name, $firstName, $dateOfBirth);
    }
    elseif (isset($_POST['infoClientFromAdvancedBtn'])){ // Si le bouton d'information client (synthése) est cliqué
        $idClient = $_POST['idClient'];
        ctlSearchIdClient($idClient);
    }
    // ------------------------------------------------------- Informations Client -------------------------------------------------------
    elseif (isset($_POST['debitBtn'])){ // Si le bouton de débit est cliqué
        $idAccount = $_POST['debitAccountSelector'];
        $amount = $_POST['amountInput'];
        ctlDebit($idAccount, $amount);
    }
    elseif (isset($_POST['creditBtn'])){// Si le bouton de crédit est cliqué
        $idAccount = $_POST['debitAccountSelector'];
        $amount = $_POST['amountInput'];
        ctlCredit($idAccount, $amount);
    }
    elseif (isset($_POST['deleteAccountBtn'])){ // Si le bouton de suppression de compte est cliqué
        $idAccount = $_POST['idAccount'];
        ctlDeleteAccount($idAccount);
    }
    elseif (isset($_POST['deleteContractBtn'])){ // Si le bouton de suppression de contrat est cliqué
        $idContract = $_POST['idContract'];
        ctlDeleteContract($idContract);
    }
    elseif (isset($_POST['modifOverdraftBtn'])){ // Si le bouton de modification du découvert autorisé est cliqué
        $idAccount = $_POST['idAccount'];
        $overdraft = $_POST['overdraft'];
        ctlModifOverdraft($idAccount, $overdraft);
    }
    // ------------------------------------------------------- Agenda -------------------------------------------------------
    elseif (isset($_POST['weekSelectorPrevious'])){ // Si le bouton de semaine précédente est cliqué
        ctlUpdateCalendar($_POST['previousWeekDate']);
    }
    elseif (isset($_POST['weekSelectorNext'])){ // Si le bouton de semaine suivante est cliqué
        ctlUpdateCalendar($_POST['nextWeekDate']);
    }
    elseif (isset($_POST['weekSelectorPreviousConseiller'])){
        ctlUpdateCalendarConseiller($_POST['previousWeekDate']);
    }
    elseif (isset($_POST['weekSelectorNextConseiller'])){
        ctlUpdateCalendarConseiller($_POST['nextWeekDate']);
    }
    elseif (isset($_POST["weekSelectorDateField"])){
        ctlUpdateCalendar($_POST['weekSelectorDateField']);
    }
    elseif (isset($_POST["weekSelectorDateFieldConseiller"])){
        ctlUpdateCalendarConseiller($_POST['weekSelectorDateFieldConseiller']);
    }
    // ------------------------------------------------------- Création Compte -------------------------------------------------------
    elseif (isset($_POST['addAccountBtn'])){ // Si le bouton d'ajout de compte est cliqué
        $idClient = $_POST['idClient'];
        ctlAddAccount($idClient);
    }
    elseif (isset($_POST['createAccountBtn'])){ // Si le bouton de validation d'ajout de compte est cliqué
        $idClient = $_POST['idClient'];
        $idClient2 = $_POST['idClient2'];
        $monthCost = $_POST['monthCost'];
        $idTypeAccount = $_POST['idTypeAccount'];
        ctlCreateAccount($idClient, $monthCost, $idTypeAccount, $idClient2);
    }
    // ------------------------------------------------------- Création Contrat -------------------------------------------------------
    elseif (isset($_POST['addContractBtn'])){ // Si le bouton d'ajout de contrat est cliqué
        $idClient = $_POST['idClient'];
        ctlAddContract($idClient);
    }
    elseif (isset($_POST['createContractBtn'])){ // Si le bouton de validation d'ajout de contrat est cliqué
        $idClient = $_POST['idClient'];
        $idClient2 = $_POST['idClient2'];
        $monthCost = $_POST['monthCost'];
        $idTypeContract = $_POST['idTypeContract'];
        ctlCreateContract($idClient, $monthCost, $idTypeContract, $idClient2);
    }
    
    // ------------------------------------------------------- Paramètres -------------------------------------------------------
    elseif (isset($_POST['settingBtn'])){ // Si le bouton de paramètres est cliqué
        ctlSetting();
    }
    elseif (isset($_POST['ModifSettingOneBtn'])){ // Si le bouton de modification des paramètres (formulaire) est cliqué
        $login = $_POST['loginEmployee'];
        $password = $_POST['passwordEmployee'];
        $color = $_POST['colorEmployee'];
        ctlSettingSubmit($_SESSION["idEmploye"], $login, $password, $color);
    }
     // ------------------------------------------------------- Directeur -------------------------------------------------------
    // ------------------------------------------------------- Statistique -------------------------------------------------------
    elseif (isset($_POST['searchStatClient2'])){ // Si le bouton de recherche de statistique entre deux dates est cliqué
        $dateStart=$_POST['datedebut'];
        $dateEnd=$_POST['datefin'];
        ctlStatsDisplay($dateStart, $dateEnd);
    }
    elseif (isset($_POST['searchStatClient1'])){ // Si le bouton de recherche de statistique à partir d'une date est cliqué
        $dateStart=$_POST['date'];
        ctlStatsDisplay("","",$dateStart);
    }
    // ------------------------------------------------------- Gestion Personnel -------------------------------------------------------
    elseif (isset($_POST['GestionPersonnelAllBtn'])){ // Si le bouton de gestion du personnel est cliqué
        ctlGestionPersonnelAll();
    }
    elseif (isset($_POST['ModifPersonnelOneBtn'])){ // Si le bouton de modification d'un employé est cliqué
        $idEmployee = $_POST['idEmployee'];
        $name = $_POST['nameEmployee'];
        $firstName = $_POST['firstNameEmployee'];
        $login = $_POST['loginEmployee'];
        $password = $_POST['passwordEmployee'];
        $category = $_POST['idCategorie'];
        $color = $_POST['colorEmployee'];
        ctlGestionPersonnelOneSubmit($idEmployee, $name, $firstName, $login, $password, $category, $color);
    }
    elseif (isset($_POST['GestionPersonnelAddBtn'])){ // Si le bouton d'ajout d'un employé est cliqué
        ctlGestionPersonnelAdd();
    }
    elseif (isset($_POST['AddPersonnelSubmitBtn'])){ // Si le bouton de validation d'ajout d'un employé est cliqué
        $name = $_POST['nameEmployee'];
        $firstName = $_POST['firstNameEmployee'];
        $login = $_POST['loginEmployee'];
        $password = $_POST['passwordEmployee'];
        $category = $_POST['idCategorie'];
        $color = $_POST['colorEmployee'];
        ctlGestionPersonnelAddSubmit($name, $firstName, $login, $password, $category, $color);
    }
    elseif (isset($_POST['GestionPersonnelDeleteBtn'])){ // Si le bouton de suppression d'un employé est cliqué
        $idEmployee = $_POST['idEmployee'];
        ctlGestionPersonnelDelete($idEmployee);
    }
    // ------------------------------------------------------- Gestion Services -------------------------------------------------------
    elseif (isset($_POST['GestionServicesAllBtn'])){ // Si le bouton de gestion des services est cliqué
        ctlGestionServiceslAll();
    }
    elseif (isset($_POST['ModifAccountOneBtn'])){ // Si le bouton modifier un compte est cliqué
        $idAccount = $_POST['idAccount'];
        $name = $_POST['nameAccount'];
        $document = $_POST['documentAccount'];
        $idMotif = $_POST['idMotif'];
        if (isset($_POST['activeAccount'])) {
            $active = 1;
        }
        else {
            $active = 0;
        }
        ctlGestionAccountOneSubmit($idAccount, $name, $active, $document, $idMotif);
    }
    elseif (isset($_POST['ModifContractOneBtn'])){ // Si le bouton modifier un contrat est cliqué
        $idContract = $_POST['idContract'];
        $name = $_POST['nameContract'];
        $document = $_POST['documentContract'];
        $idMotif = $_POST['idMotif'];
        if (isset($_POST['activeContract'])) {
            $active = 1;
        }
        else {
            $active = 0;
        }
        ctlGestionContractOneSubmit($idContract, $name, $active, $document, $idMotif);
    }
    elseif (isset($_POST['GestionServicesAddBtn'])){ // Si le bouton d'ajout d'un service est cliqué
        ctlGestionServicesAdd();
    }
    elseif (isset($_POST['AddServiceSubmitBtn'])){ // Si le bouton de validation d'ajout d'un service est cliqué
        $name = $_POST['nameService'];
        $type = $_POST['typeService'];
        $document = $_POST['documentService'];
        if (isset($_POST['activeService'])) {
            $active = 1;
        }
        else {
            $active = 0;
        }
        ctlGestionServicesAddSubmit($name, $type, $active, $document);
    }
    elseif (isset($_POST['GestionAccountDeleteBtn'])){ // Si le bouton de suppression d'un compte est cliqué
        $idAccount = $_POST['idAccount'];
        ctlGestionAccountDelete($idAccount);
    }
    elseif (isset($_POST['GestionContractDeleteBtn'])){ // Si le bouton de suppression d'un contrat est cliqué
        debug($_POST);
        $idContract = $_POST['idContract'];
        ctlGestionContractDelete($idContract);
    }
    // ------------------------------------------------------- Conseiller -------------------------------------------------------
    
    
    // ------------------------------------------------------- RDV -------------------------------------------------------
    
    elseif (isset($_POST['newRDVbtn'])) { // Si le bouton de prise de rendez-vous de l'agent est cliqué
        $date = $_POST['newRDVdateField'];
        ctlDisplayAddAppointement($date);
    }
    elseif (isset($_POST['newRDVConseillerbtn'])) { // Si le bouton de prise de rendez-vous du conseiller est cliqué
        $date = $_POST['newRDVdateFieldConseiller'];
        ctlDisplayAddAppointementConseiller($date);
    }
    elseif (isset($_POST['addAppointementsBtn'])) { // Si le bouton de validation de prise de rendez-vous est cliqué
        $idClient = $_POST['appointementsClientField'];
        $idEmployee = $_POST['appointementsConseillerField'];
        $date = $_POST['appointementsDateField'];
        $heureDebut = $_POST['appointementsHoraireDebutField'];
        $heureFin = $_POST['appointementsHoraireFinField'];
        $idMotif = $_POST['appointementsMotifField'];
        ctlCreateNewAppointement($idClient, $idEmployee, $date, $heureDebut, $heureFin, $idMotif);
    }
    elseif (isset($_POST['deleteRDVbtn'])) { // Si le bouton de suppression de rendez-vous est cliqué
        $idRDV = $_POST['idRDVField'];
        ctlDeleteAppointment($idRDV);
    }
    // ------------------------------------------------------- TA -------------------------------------------------------
    elseif (isset($_POST['addAdminBtn'])) { // Si le bouton de prise de TA est cliqué
        $idEmployee = $_POST['appointementsConseillerField'];
        $date = $_POST['appointementsDateField'];
        $heureDebut = $_POST['appointementsHoraireDebutField'];
        $heureFin = $_POST['appointementsHoraireFinField'];
        $libelle = $_POST['adminLibelleField'];
        ctlCreateNewTA($idEmployee, $date, $heureDebut, $heureFin, $libelle);
    }
    
    elseif (isset($_POST['deleteTAbtn'])){ // Si le bouton de suppression de TA est cliqué
        $idRDV = $_POST['idTAField'];
        ctlDeleteTA($idRDV);
    }
    // ------------------------------------------------------- Default -------------------------------------------------------
    else{
        ctlHome();
    }
} 

catch(Exception $e) { 
     $msg = $e->getMessage() ;
     ctlError($msg);
}


/*
POUBELLE

elseif (isset($_POST['GestionAccountOneBtn'])){
        $idAccount = $_POST['idAccount'];
        ctlGestionAccountOne($idAccount);
    }

elseif (isset($_POST["GestionContractOneBtn"])){
        $idContract = $_POST['idContract'];
        ctlGestionContractOne($idContract);
    }

*/