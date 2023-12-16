<?php

require_once('controleur/controleur.php');
require_once('modele/modele.php');
require_once('vue/vue.php');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}



try {
    // ------------------------------------------------------- Landing Page -------------------------------------------------------
    if (isset($_POST['landingSubmitBtn'])){
        $username = $_POST['landingLoginField'];
        $password = $_POST['landingPasswordField'];
        ctlLogin($username, $password);
    }
    // ------------------------------------------------------- General -------------------------------------------------------
    elseif (isset($_POST['searchClientBtn'])){
        $idClient = $_POST['searchClientByIdField'];
        ctlSearchIdClient($idClient);
    }
    elseif (isset($_POST['advancedSearchBtn'])){
        vueDisplayAdvanceSearchClient();
    }
    elseif (isset($_POST['advanceSearchClient'])){
        $name = $_POST['searchNameClientField'];
        $firstName = $_POST['searchFirstNameClientField'];
        $dateOfBirth = $_POST['searchBirthClientField'];
        cltAdvanceSearchClient($name, $firstName, $dateOfBirth);
    }
    elseif (isset($_POST['disconnection'])){
        ctlLogout();
    }
    elseif (isset($_POST['debitBtn'])){
        $idAccount = $_POST['debitAccountSelector'];
        $amount = $_POST['amountInput'];
        ctlDebit($idAccount, $amount);
    }
    elseif (isset($_POST['creditBtn'])){
        $idAccount = $_POST['debitAccountSelector'];
        $amount = $_POST['amountInput'];
        ctlCredit($idAccount, $amount);
    }
    elseif (isset($_POST['infoClientFromAdvancedBtn'])){
        $idClient = $_POST['idClient'];
        ctlSearchIdClient($idClient);
    }
    elseif (isset($_POST['weekSelectorPrevious'])){
        ctlUpdateCalendar($_POST['previousWeekDate']);
    }
    elseif (isset($_POST['weekSelectorNext'])){
        ctlUpdateCalendar($_POST['nextWeekDate']);
    }
    elseif (isset($_POST["weekSelectorDateField"])){
        ctlUpdateCalendar($_POST['weekSelectorDateField']);
    }
    elseif (isset($_POST['settingBtn'])){
        ctlSetting();
    }
    elseif (isset($_POST['ModifSettingOneBtn'])){
        $login = $_POST['loginEmployee'];
        $password = $_POST['passwordEmployee'];
        $color = $_POST['colorEmployee'];
        ctlSettingSubmit($_SESSION["idEmploye"], $login, $password, $color);
    }
    elseif (isset($_POST['deleteAccountBtn'])){
        $idAccount = $_POST['idAccount'];
        ctlDeleteAccount($idAccount);
    }
    elseif (isset($_POST['deleteContractBtn'])){
        $idContract = $_POST['idContract'];
        ctlDeleteContract($idContract);
    }
     // ------------------------------------------------------- Directeur -------------------------------------------------------
    // ------------------------------------------------------- Statistique -------------------------------------------------------
    elseif (isset($_POST['searchStatClient2'])){
        $dateStart=$_POST['datedebut'];
        $dateEnd=$_POST['datefin'];
        ctlStatsDisplay($dateStart, $dateEnd);
    }
    elseif (isset($_POST['searchStatClient1'])){
        $dateStart=$_POST['date'];
        ctlStatsDisplay("","",$dateStart);
    }
    // ------------------------------------------------------- Gestion Personnel -------------------------------------------------------
    elseif (isset($_POST['GestionPersonnelAllBtn'])){
        ctlGestionPersonnelAll();
    }
    elseif (isset($_POST['GestionPersonnelOneBtn'])){
        $idEmployee = $_POST['idEmployee'];
        ctlGestionPersonnelOne($idEmployee);
    }
    elseif (isset($_POST['ModifPersonnelOneBtn'])){
        $idEmployee = $_POST['idEmployee'];
        $name = $_POST['nameEmployee'];
        $firstName = $_POST['firstNameEmployee'];
        $login = $_POST['loginEmployee'];
        $password = $_POST['passwordEmployee'];
        $category = $_POST['idCategorie'];
        $color = $_POST['colorEmployee'];
        ctlGestionPersonnelOneSubmit($idEmployee, $name, $firstName, $login, $password, $category, $color);
    }
    elseif (isset($_POST['GestionPersonnelAddBtn'])){
        ctlGestionPersonnelAdd();
    }
    elseif (isset($_POST['AddPersonnelSubmitBtn'])){
        $name = $_POST['nameEmployee'];
        $firstName = $_POST['firstNameEmployee'];
        $login = $_POST['loginEmployee'];
        $password = $_POST['passwordEmployee'];
        $category = $_POST['idCategorie'];
        $color = $_POST['colorEmployee'];
        ctlGestionPersonnelAddSubmit($name, $firstName, $login, $password, $category, $color);
    }
    elseif (isset($_POST['GestionPersonnelDeleteBtn'])){
        $idEmployee = $_POST['idEmployee'];
        ctlGestionPersonnelDelete($idEmployee);
    }
    // ------------------------------------------------------- Gestion Services -------------------------------------------------------
    elseif (isset($_POST['GestionServicesAllBtn'])){
        ctlGestionServiceslAll();
    }
    elseif (isset($_POST['GestionAccountOneBtn'])){
        $idAccount = $_POST['idAccount'];
        ctlGestionAccountOne($idAccount);
    }
    elseif (isset($_POST['ModifAccountOneBtn'])){
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
    elseif (isset($_POST["GestionContractOneBtn"])){
        $idContract = $_POST['idContract'];
        ctlGestionContractOne($idContract);
    }
    elseif (isset($_POST['ModifContractOneBtn'])){
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
    elseif (isset($_POST['GestionServicesAddBtn'])){
        ctlGestionServicesAdd();
    }
    elseif (isset($_POST['AddServiceSubmitBtn'])){
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
    elseif (isset($_POST['GestionAccountDeleteBtn'])){
        $idAccount = $_POST['idAccount'];
        ctlGestionAccountDelete($idAccount);
    }
    elseif (isset($_POST['GestionContractDeleteBtn'])){
        $idContract = $_POST['idContract'];
        ctlGestionContractDelete($idContract);
    }
    // ------------------------------------------------------- Conseiller -------------------------------------------------------
    // ------------------------------------------------------- Création Contrat -------------------------------------------------------
    elseif (isset($_POST['addContractBtn'])){
        $idClient = $_POST['idClient'];
        ctlAddContract($idClient);
    }
    elseif (isset($_POST['createContractBtn'])){
        $idClient = $_POST['idClient'];
        $idClient2 = $_POST['idClient2'];
        $monthCost = $_POST['monthCost'];
        $idTypeContract = $_POST['idTypeContract'];
        ctlCreateContract($idClient, $monthCost, $idTypeContract, $idClient2);
    }
    // ------------------------------------------------------- Création Compte -------------------------------------------------------
    elseif (isset($_POST['addAccountBtn'])){
        $idClient = $_POST['idClient'];
        ctlAddAccount($idClient);
    }
    elseif (isset($_POST['createAccountBtn'])){
        $idClient = $_POST['idClient'];
        $idClient2 = $_POST['idClient2'];
        $monthCost = $_POST['monthCost'];
        $idTypeAccount = $_POST['idTypeAccount'];
        ctlCreateAccount($idClient, $monthCost, $idTypeAccount, $idClient2);
    }
    // ------------------------------------------------------- Agent -------------------------------------------------------
    elseif (isset($_POST['addClientBtn'])){
        ctlDisplayNewClientForm();
    }
    elseif (isset($_POST['createClientBtn'])){
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
    // ------------------------------------------------------- Client -------------------------------------------------------
    // ------------------------------------------------------- Default -------------------------------------------------------
    else{
        ctlHome();
    }
} 

catch(Exception $e) { 
     $msg = $e->getMessage() ;
     ctlError($msg);
}