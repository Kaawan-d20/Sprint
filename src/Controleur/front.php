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
    elseif (isset($_POST['rechercherClientAgent'])){
        $idClient = $_POST['searchClientField'];
        ctrChercherClient($idClient);
    }
    elseif (isset($_POST['rechercheAvancée'])){
        vueAfficherRechercheClient();
    }
    elseif (isset($_POST['rechercherAvanceeClient'])){
        $nom = $_POST['searchNomClientField'];
        $prenom = $_POST['searchPrenomClientField'];
        $dateNaissance = $_POST['searchNaissanceClientField'];
        cltRechercheAvanceeClient($nom, $prenom, $dateNaissance);
    }
    else{
        ctlAcceuil();
    }
} 

catch(Exception $e) { 
     $msg = $e->getMessage() ;
     ctlErreur($msg);
}

