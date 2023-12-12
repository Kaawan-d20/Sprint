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
     // ------------------------------------------------------- Directeur -------------------------------------------------------
    // ------------------------------------------------------- Statistique -------------------------------------------------------
    elseif (isset($_POST['seachStatClient'])){
        $dateStart=$_POST['datedebut'];
        $dateEnd=$_POST['datefin'];
        ctlStatClientBetween($dateStart, $dateEnd);
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
     // ------------------------------------------------------- Statistique -------------------------------------------------------
     elseif (isset($_POST['seachStatClient'])){
        $dateStart=$_POST['datedebut'];
        $dateEnd=$_POST['datefin'];
        ctlStatClientBetween($dateStart, $dateEnd);
    }
    // ------------------------------------------------------- Conseiller -------------------------------------------------------
    // ------------------------------------------------------- Agent -------------------------------------------------------
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