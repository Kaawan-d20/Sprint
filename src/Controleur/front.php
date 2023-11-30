<?php

require_once('controleur/controleur.php');
require_once('modele/modele.php');
require_once('vue/vue.php');

try { 
    CtlAcceuil();
} 

catch(Exception $e) { 
     $msg = $e->getMessage() ;
     CtlErreur($msg);
}
    