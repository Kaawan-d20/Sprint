<?php
require_once 'modele/modele.php';
require_once 'vue/vue.php';

function ctlAcceuil (){
    afficherAccueil();
}
function ctlLogin () {
    afficherLogin();
}
function login ($username, $password) {
    if ($username == '' || $password == '') {
        echo 'fonction cont login';
        throw new Exception("Un des champs est vide");
    }
    $resutatConnnect = modConnect($username, $password);
    if (empty($resutatConnnect)){
        throw new Exception("Nom d'utilisateur ou mot de passe incorrect");
    }
    else{
        session_start();
        $_SESSION["acitf"] = true;
        $_SESSION["id"] = $resutatConnnect;
        $_SESSION["type"] = modGetTypeStaff($resutatConnnect);
        afficherAccueil();
    }
}

function chercherClient($idClient){
    if ($idClient == '') {
        throw new Exception('Veuillez entrer un ID');
    }
    else{
        $client = modChercherClient($idClient);
        if (empty($client)){
            throw new Exception('Aucun client trouvé');
        }
        else{
            modGetClientFromId($idClient)
            afficherClient($client);
            
        }
    }
}

function rechercheAvanceeClient($nomClient, $prenomClient, $dateNaissance) {
    if ($prenomClient == '' | $nomClient == '' | $dateNaissance == '') {
        throw new Exception('Veuillez remplir tous les champs');
    }
    else{
        $listeClient = modRechercheAvancéeClient($nomClient, $prenomClient, $dateNaissance);
        if (empty($listeClient)){
            throw new Exception('Aucun client trouvé');
        }
        else{
            afficherRechercheClient($listeClient);
        }
    }
}

function ctlErreur($erreur) {
    afficherErreur($erreur) ;
}
