<?php

require_once('controleur/controleur.php');
require_once('modele/modele.php');
require_once('vue/vue.php');

try { 
    if (isset($_POST['connexion'])){
        $username = $_POST['loginField'];
        $password = $_POST['passwordField'];
        login($username, $password);
    }
    CtlAcceuil();
} 

catch(Exception $e) { 
     $msg = $e->getMessage() ;
     ctlErreur($msg);
}
    