<?php
require_once 'modele/token.php';
require_once 'token.php';


function afficherErreur ($erreur) {
    $content = "<p>".$erreur."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
}