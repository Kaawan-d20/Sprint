<?php

/**
 * Fonction qui affiche la page d'accueil du directeur
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
 */
function vueDisplayHomeDirecteur($stat){
    $content="";
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

function vueDisplayHomeAgent($appointments, $TA, $dateOfWeek, $username) {
    $weekEvents = array("", "", "", "", "", "", "");
    // $weekEvents represente pour chaque entrée de 0 à 6, en chaine de caracteres, les eventHTML du jour correspondant
    foreach ($appointments as $appointment) {
        $appointmentDate = date_create_from_format("Y-m-d H:i:s", $appointment->HORAIREDEBUT);
        $weekNumber = date_format($appointmentDate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAppointementHTML($appointment);
    }
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

function vueGenerateAppointementHTML($appointment) {
    $heureDebut = (substr($appointment->HORAIREDEBUT, 11, 5));
    $heureFin = (substr($appointment->HORAIREFIN, 11, 5)); 
        return '<div class="event" data-conseiller="'. $appointment->identiteEmploye .'" dataset-color="'. 'lush-green' .'">
        <h2>'. $appointment->INTITULE .'</h2>
        <p>'. $appointment->identiteClient .'</p>
        <div class="eventDetails">
            <div>
                <p class="eventStartTime">'. $heureDebut .'</p>
                <p class="eventEndTime">'. $heureFin .'</p>
            </div>
            <div class="eventConseiller lush-green">
                <i class="fa-solid fa-user-tie"></i>
                '. $appointment->identiteEmploye .'
            </div>
        </div>
    </div>';
}


/**
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param string $client c'est les données du client
 */
function vueDisplayInfoClient($client, $listAccounts, $listContract,$listOperationsAccount){

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
    $nameConseiller = $client->NOMCONSEILLER." ".$client->PRENOMCONSEILLER;
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
    //Pour l'afficher des comptes avec les opérations
    $chteumeul = "<select id='comptes' onchange='displayOperations()'>";
    $cht = "";
    $compteur = 1;
    foreach ($listAccounts as $account) {
        $chteumeul .= "<option value=\"".$compteur."\">".$account->intitule." : ".$account->solde."</option>";
        $cht .= "<div id=\"".$compteur."\" class='hidden'>";
        $listOperations = $listOperationsAccount["$account->idCompte"];
        foreach ($listOperations as $operation) {
            $cht .= "<div><span>".$operation->DATEOPERATION."</span><h2>".$operation->LIBELLE."</h2><span>".$operation->IDOPERATION."</span><span>".$operation->SOURCE."</span>";
            if ($operation->ISCREDIT == 0) {
                $cht .= "<i class='fa-solid fa-minus'></i>";
            } else {
                $cht .= "<i class='fa-solid fa-plus'></i>";
            }
            $cht .= "<span>".$operation->MONTANT."</span></div>";
        }
        $cht .= "</div>";
        $compteur+=1;
    }
    $chteumeul .= "</select>";
    $content = $chteumeul.$cht;
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
            $content .= "<tr>
                            <td name=\"idClient\">".$client->idClient."</td>
                            <td>".$client->nom."</td><td>".$client->prenom."</td>
                            <td>".$client->dateNaissance."</td>
                            <td><input type=\"submit\" name=\"infoClientFromAdvancedBtn\" value=\"Synthèse\"></td>
                        </tr>";
            $content .= "</form>";
        }
        $content .= "</table>";
    }
    require_once('gabaritRechercheClient.php');
}

function vueDisplayGestionPersonnelAll($listEmployee) {
    $content="<h1>Gestion du Personnel</h1>
                <h2>Liste des employers</h2>";
    foreach ($listEmployee as $employee) {
        if ($employee->IDCATEGORIE == 1) {
            $category = "Directeur";
        }
        elseif ($employee->IDCATEGORIE == 2) {
            $category = "Conseiller";
        }
        elseif ($employee->IDCATEGORIE == 3) {
            $category = "Agent d'acceuil";
        }
        
        $content .= "<form action=\"index.php\" method=\"post\">
                        <p>
                            Id de l'employe : ".$employee->IDEMPLOYE.", 
                            Type d'employe : ".$category.", 
                            Nom de l'employé : ".$employee->NOM.", 
                            Prénom de l'employé : ".$employee->PRENOM.",  
                            Login de l'employé : ".$employee->LOGIN.",
                            Couleur de l'employé : ".$employee->COLOR.", 
                            <input type=\"hidden\" name=\"idEmployee\" value=\"".$employee->IDEMPLOYE ."\">
                            <input type=\"submit\" value=\"Modifier l'employe.\" name=\"GestionPersonnelOneBtn\">
                            <input type=\"submit\" value=\"Supprimer l'employe.\" name=\"GestionPersonnelDeleteBtn\">
                        </p>
                    </form>";
    }
    $content .= "<form action=\"index.php\" method=\"post\">
                    <p>
                        <input type=\"submit\" value=\"Ajouter un employé.\" name=\"GestionPersonnelAddBtn\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
}


function vueDisplayGestionPersonnelOne($employee) {
    
        $etat1=$employee->IDCATEGORIE==1 ? "selected": "";
        $etat2=$employee->IDCATEGORIE==2 ? "selected": "";
        $etat3=$employee->IDCATEGORIE==3 ? "selected": "";
        $content="<h1>Modifier info employé</h1>
                    <form action=\"index.php\" method=\"post\">
                        <p>
                            <select name=\"idCategorie\" >
                                <option value=\"1\" ".$etat1." >Directeur</option>
                                <option value=\"2\" ".$etat2." >Conseiller</option>
                                <option value=\"3\" ".$etat3." >Agent d'acceuil</option>
                            </select>
                            <input type=\"hidden\" name=\"idEmployee\" value=\"$employee->IDEMPLOYE\">
                            <input type=\"text\" name=\"nameEmployee\" value=\"$employee->NOM\">
                            <input type=\"text\" name=\"firstNameEmployee\" value=\"$employee->PRENOM\">
                            <input type=\"text\" name=\"loginEmployee\" value=\"$employee->LOGIN\">
                            <input type=\"text\" name=\"passwordEmployee\" value=\"$employee->PASSWORD\">
                            <input type=\"text\" name=\"colorEmployee\" value=\"$employee->COLOR\">
                            <input type=\"submit\" name=\"ModifPersonnelOneBtn\" value=\"Valider modification\">
                        </p>
                    </form>";
    
    require_once('gabaritGestion.php');

}




function vueDisplayGestionPersonnelAdd(){
    $content="<h1>Ajouter un employé</h1>
                <form action=\"index.php\" method=\"post\">
                    <p>
                        <select name=\"idCategorie\" >
                            <option value=\"1\" >Directeur</option>
                            <option value=\"2\" >Conseiller</option>
                            <option value=\"3\" >Agent d'acceuil</option>
                        </select>
                        <input type=\"text\" name=\"nameEmployee\" placeholder=\"Nom\">
                        <input type=\"text\" name=\"firstNameEmployee\" placeholder=\"Prénom\">
                        <input type=\"text\" name=\"loginEmployee\" placeholder=\"Login\">
                        <input type=\"text\" name=\"passwordEmployee\" placeholder=\"Mot de passe\">
                        <input type=\"text\" name=\"colorEmployee\" placeholder=\"Couleur\">
                        <input type=\"submit\" name=\"AddPersonnelSubmitBtn\" value=\"Valider ajout\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
}









function vueDisplayGestionServicesAll($listTypeAccount, $listTypeContract) {
    $content ="<h1>Gestion des services</h1>
                <h2>Liste des type de compte</h2>";
    foreach ($listTypeAccount as $typeAccount) {
        $content .= "<form action=\"index.php\" method=\"post\">
                        <p>
                            Intitulé du type de compte : ".$typeAccount->NOM.",
                            Actif : ".$typeAccount->ACTIF.",
                            Document : ".$typeAccount->DOCUMENT."
                            <input type=\"hidden\" name=\"idAccount\" value=\"".$typeAccount->IDTYPECOMPTE."\">
                            <input type=\"submit\" value=\"Modifier le type de compte.\" name=\"GestionAccountOneBtn\">
                            <input type=\"submit\" value=\"Supprimer le type de contrat.\" name=\"GestionAccountDeleteBtn\">

                        </p>
                    </form>";
    }
    $content .="<h2>Liste des type de Contrat</h2>";
    foreach ($listTypeContract as $typeContract) {
        $content .= "<form action=\"index.php\" method=\"post\">
                        <p>
                            Intitulé du type de contrat : ".$typeContract->NOM.",
                            Actif : ".$typeContract->ACTIF.",
                            Document : ".$typeContract->DOCUMENT."
                            <input type=\"hidden\" name=\"idContract\" value=\"".$typeContract->IDTYPECONTRAT."\">
                            <input type=\"submit\" value=\"Modifier le type de contrat.\" name=\"GestionContractOneBtn\">
                            <input type=\"submit\" value=\"Supprimer le type de contrat.\" name=\"GestionContractDeleteBtn\">
                        </p>
                    </form>";
    }
    $content .= "<form action=\"index.php\" method=\"post\">
                    <p>
                        <input type=\"submit\" value=\"Ajouter un Service.\" name=\"GestionServicesAddBtn\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
}


function vueDisplayGestionServicesAdd(){
    $content="<h1>Ajouter un service</h1>
                <form action=\"index.php\" method=\"post\">
                    <p>
                        <select name=\"typeService\" >
                            <option value=\"1\" >Type de compte</option>
                            <option value=\"2\" >Type de contrat</option>
                        </select>
                        <input type=\"text\" name=\"nameService\" placeholder=\"Nom\">
                        <input type=\"text\" name=\"documentService\" placeholder=\"Document\">
                        <input type=\"checkbox\" name=\"activeService\" >
                        <input type=\"submit\" name=\"AddServiceSubmitBtn\" value=\"Valider ajout\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
}

function vueDisplayGestionAccountOne($account) {
    $etat=$account->ACTIF==1 ? "checked": "";
    $content="<h1>Modifier info type compte</h1>
                <form action=\"index.php\" method=\"post\">
                    <p>
                        <input type=\"text\" name=\"nameAccount\" value=\"".$account->NOM."\">
                        <input type=\"text\" name=\"documentAccount\" value=\"".$account->DOCUMENT."\">
                        <input type=\"checkbox\" name=\"activeAccount\" ". $etat ."  >
                        <input type=\"hidden\" name=\"idAccount\" value=\"".$account->IDTYPECOMPTE."\">
                        <input type=\"hidden\" name=\"idMotif\" value=\"".$account->IDMOTIF."\">

                        <input type=\"submit\" name=\"ModifAccountOneBtn\" value=\"Valider modification\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
}


function vueDisplayGestionContractOne($contract) {
    $etat=$contract->ACTIF==1 ? "checked": "";
    $content="<h1>Modifier info type contrat</h1>
                <form action=\"index.php\" method=\"post\">
                    <p>
                        <input type=\"text\" name=\"nameContract\" value=\"".$contract->NOM."\">
                        <input type=\"text\" name=\"documentContract\" value=\"".$contract->DOCUMENT."\">
                        <input type=\"checkbox\" name=\"activeContract\" ".$etat.">
                        <input type=\"hidden\" name=\"idContract\" value=\"".$contract->IDTYPECONTRAT."\">
                        <input type=\"hidden\" name=\"idMotif\" value=\"".$contract->IDMOTIF."\">

                        <input type=\"submit\" name=\"ModifContractOneBtn\" value=\"Valider modification\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
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


function vueDisplayAgendaConseiller($appointment, $admin){
    $bla = json_encode($appointment);
    echo json_encode($admin);
    require_once('gabaritAgentHomePage.php');
}









function vueDisplayRDVBetween(){

}