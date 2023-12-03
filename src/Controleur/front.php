<?php

require_once('controleur/controleur.php');
require_once('modele/modele.php');
require_once('vue/vue.php');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}



try { 
    if (isset($_POST['landingSubmitBtn'])){
        $username = $_POST['landingLoginField'];
        $password = $_POST['landingPasswordField'];
        ctlLogin($username, $password);
    }
    elseif (isset($_POST['searchClientBtn'])){
        $idClient = $_POST['searchClientByIdField'];
        ctrSearchIdClient($idClient);
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
    else{
        ctlHome();
    }
} 

catch(Exception $e) { 
     $msg = $e->getMessage() ;
     ctlError($msg);
}

