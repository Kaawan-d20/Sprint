<?php

/**
 * Fonction qui affiche la page d'accueil du directeur
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
 */
function vueDisplayHomeDirecteur(){
    $content="";
    $stat = ctlGetStats();
    require_once('gabaritDirecteurHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil du conseiller
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
 */
function vueDisplayHomeConseiller(){
    $content="";
    require_once('gabaritConseillerHomePage.php');
}
/**
 * Fonction qui affiche la page d'accueil de l'agent d'accueil
 * Ne retourne rien
 * @param string $firstName prénom de l'Agent
 * @param string $lastName nom de famille de l'Agent
 * @param array $rendezVous liste des Rendez Vous de la semaine
 * @param array $adminTasks liste des tâches admins de la semaine
 */
// function vueDisplayHomeAgent($firstName, $lastName, $rendezVous, $adminTasks){
function vueDisplayHomeAgent() {
    // $username = $firstName . $lastName;
    // $appointements = array_merge($rendezVous, $adminTasks);
    require_once('gabaritAgentHomePage.php');
}
/**
 * Fonction qui affiche la page de login
 * Ne prend pas de paramètres et ne retourne rien
 */
function vueDisplayLogin(){
    $content="";
    require_once('gabaritLanding.php');
}


/**
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param string $client c'est les données du client
 */
function vueDisplayInfoClient($client, $listAccounts, $listContract){

    // pour faire le select pour le débit / crédit
    $optionSelect = "";
    $listA="";
    foreach ($listAccounts as $account) {
        $optionSelect .= "<option value=\"".$account->idCompte."\">".$account->intitule."</option>";
        $listA .= "<p>".$account->intitule." : ".$account->solde."€</p>";
    }
    $listC="";
    foreach ($listContract as $contract) {
        $listC .= "<p>".$contract->intitule." : ".$contract->tarifmensuel."€</p>";
    }
    // pour faire la synthèse
    $idClient = $client->IDCLIENT;
    $nameConseiller = $client->LOGIN;
    $nameClient = $client->NOM;
    $naissance = $client->DATENAISSANCE;
    $creation = $client->DATECREATION;
    $firstNameClient = $client->PRENOM;
    $addressClient = $client->ADRESSE;
    $phoneClient = $client->NUMTEL;
    $emailClient = $client->EMAIL;
    $profession = $client->PROFESSION;
    $situation = $client->SITUATIONFAMILIALE;
    $civi = $client->CIVILITEE;
    require_once('gabaritInfoClient.php');
}
/**
 * Fonction qui affiche la page de resultat de recherche d'un client
 * Ne retourne rien
 * @param array $listClient c'est la liste des clients
 * @param string $link c'est le lien pour la synthèse
 */
function vueDisplayAdvanceSearchClient($listClient="") {
    
    if ($listClient == "") {
        $content = "";
    }
    else{
        $content="<form action=\"index.php\" method=\"post\"><table>";
        foreach ($listClient as $client) {
            $content="<form action=\"index.php\" method=\"post\">";
            $content .= "<input type=\"number\" name=\"idClient\" value=\"".$client->idClient."\" class=\"hidden\">";
            $content .= "<tr><td name=\"idClient\">".$client->idClient."</td><td>".$client->nom."</td><td>".$client->prenom."</td><td>".$client->dateNaissance."</td><td><input type=\"submit\" name=\"infoClientFromAdvancedBtn\" value=\"Synthèse\"></td></tr>";
            $content .= "</form>";
        }
        $content .= "</table>";
    }
    require_once('gabaritRechercheClient.php');
}



/**
 * Fonction qui affiche la page d'erreur
 * Ne retourne rien
 * @param string $error
 */
function vueDisplayError ($error) {
    $content = "<p>".$error."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
    require_once('gabaritErreur.php');
}