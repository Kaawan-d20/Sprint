<?php

require_once('controleur/controleur.php');
require_once('modele/modele.php');
require_once('vue/vue.php');
if (!isset($_SESSION)){
    session_start();
}



try { 
    if (isset($_POST['landingSubmitBtn'])){
        $username = $_POST['landingLoginField'];
        $password = $_POST['landingPasswordField'];
        ctlLogin($username, $password);
    }
    elseif (isset($_POST['searchClientBtn'])){
        $idClient = $_POST['searchClientField'];
        ctrSearchIdClient($idClient);
    }
    elseif (isset($_POST['advanceSearch'])){
        vueDisplayAdvanceSearchClient();
    }
    elseif (isset($_POST['advanceSearchClient'])){
        $name = $_POST['searchNameClientField'];
        $firstName = $_POST['searchFirstNameClientField'];
        $dateOfBirth = $_POST['searchBirthClientField'];
        cltAdvanceSearchClient($name, $firstName, $dateOfBirth);
    }
    elseif (isset($_POST['deconnexion'])){
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

