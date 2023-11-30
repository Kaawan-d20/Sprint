<?php
require_once 'modele/modele.php';
require_once 'vue/vue.php';

function ctlAcceuil (){
    afficherAccueil();
}

function ctlErreur($erreur){
    afficherErreur($erreur) ;
}
