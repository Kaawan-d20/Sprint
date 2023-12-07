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
    elseif (isset($_POST['DateRDVBtn'])){
        $dateStartOfWeek=$_POST['dateStartOfWeek'];
        $dateEndOfWeek=$_POST['dateEndOfWeek'];
        ctlRDVBetween($dateStartOfWeek, $dateEndOfWeek);
    }
    // ------------------------------------------------------- Directeur -------------------------------------------------------
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
        ctlGestionPersonnelOneSubmit($idEmployee, $name, $firstName, $login, $password, $category);
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

