<?php

$colors = [
    "sunny-orange",
    "turquoise-cyan",
    "berry-red",
    "lush-green",
    "lavender",
    "lemon-yellow",
    "royal-purple",
    "ocean-blue",
    "coral-pink"
];

/**
 * Fonction qui affiche la page d'accueil du directeur
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
 */
function vueDisplayHomeDirecteur($stat, $username){
    $navbar = vueGenerateNavBar();
    $content="";
    require_once('gabaritDirecteurHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil du conseiller
 * Ne prend pas de paramètres et ne retourne rien
 * @return void
 */
function vueDisplayHomeConseiller($username){
    $navbar = vueGenerateNavBar();
    $content="";
    require_once('gabaritConseillerHomePage.php');
}

function vueDisplayHomeAgent($appointments, $TA, $dateOfWeek, $username) {
    $navbar = vueGenerateNavBar();
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
        return '<div class="event" data-conseiller="'. $appointment->identiteEmploye .'" dataset-color="'. $appointment->COLOR .'">
        <h2>'. $appointment->INTITULE .'</h2>
        <p>'. $appointment->identiteClient .'</p>
        <div class="eventDetails">
            <div>
                <p class="eventStartTime">'. $heureDebut .'</p>
                <p class="eventEndTime">'. $heureFin .'</p>
            </div>
            <div class="eventConseiller '.$appointment->COLOR.'">
                <i class="fa-solid fa-user-tie"></i>
                '. $appointment->identiteEmploye .'
            </div>
        </div>
    </div>';
}


function vueGenerateNavBar() {
    if ($_SESSION["type"] == 3) {
        debug(3);
    }
    $navbarHTML = 
    '<div class="navWrapper">
        <nav>
            <form action="index.php" method="post">
                    <button class="squareIconButton" name="homeBtn" title="Retour à l\'Accueil" onclick="javascript:location.reload();">
                        <i class="fa-solid fa-house"></i>
                    </button>
            </form>
            <div class="searchWrapper">
                <form action="index.php" method="post">
                    <label for="searchClientField" class="visually-hidden">Chercher un client</label>
                    <input type="number" name="searchClientByIdField" id="searchClientByIdField" placeholder="Id du client" class="searchField" required="required">
                    <button class="searchButton" name="searchClientBtn" title="Recherche par ID">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="advancedSearchandAccountWrapper">
                <form action="index.php" method="post">
                    <button class="squareIconButton" name="advancedSearchBtn" title="Recherche avancée">
                        <i class="fa-regular fa-chart-bar"></i>
                    </button>
                </form>
                <form action="index.php" method="post">
                    <button type="submit" name="addClientBtn" class="squareIconButton">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </form>
                <div class="dropdown">
                    <button class="accountButton">
                        <i class="fa-solid fa-user"></i>
                        '. $_SESSION["name"] .'
                    </button>
                    <div class="dropdownContent">
                        <form action="index.php" method="post">
                            <button class="dropdownButton" onclick="toggleTheme()" type="button" id="themeSwitcherBtn">
                                Thème
                                <i class="fa-solid fa-moon" id="themeSwitcherIcon"></i>
                            </button>
                            <button type="submit" class="dropdownButton" name="settingBtn" >
                                Paramètres
                                <i class="fa-solid fa-user-gear"></i>
                            </button>
                            <button class="dropdownButton disconnectionBtn" name="disconnection">
                                Se déconnecter
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <script>
    let isLightTheme = true;
    /** switch beetween light and dark theme */ 
    function toggleTheme() {
        let icon = document.getElementById("themeSwitcherIcon");
        let btn = document.getElementById("themeSwitcherBtn");
        if (isLightTheme) {
            document.body.classList.add("dark");
            document.body.classList.remove("light");
            icon.classList.add("fa-sun")
            icon.classList.remove("fa-moon")
            btn.setAttribute("title", "Activer le thème Clair")

            isLightTheme = false;
        } else {
            document.body.classList.add("light");
            document.body.classList.remove("dark");
            icon.classList.add("fa-moon")
            icon.classList.remove("fa-sun")
            btn.setAttribute("title", "Activer le thème Sombre")

            isLightTheme = true;
        }
    }
    </script>';
    return $navbarHTML;
    
}

/**
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param string $client c'est les données du client
 */
function vueDisplayInfoClient($client, $listAccounts, $listContract,$listOperationsAccount){
    $navbar = vueGenerateNavBar();
    // pour faire le select pour le débit / crédit
    $optionSelect = "";
    $listA="";
    foreach ($listAccounts as $account) {
        $optionSelect .= "<option value=\"".$account->idCompte."\">".$account->NOM.': '. $account->solde ."€</option>";
        $listA .= '<div class="accountCell content">'.$account->NOM.'</div>
            <div class="accountCell content">'.$account->solde.'€</div>
            <div class="accountCell content">'.$account->decouvert.'€</div>';
    }
    $listC="";
    foreach ($listContract as $contract) {
        $listC .= '<div class="contractCell content">'.$contract->NOM.'</div>
            <div class="contractCell content">'.$contract->tarifmensuel.'€</div>';
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
    $filterBtns = "";
    $operationDisplay = "";
    foreach ($listAccounts as $account) {
        $filterBtns .= vueGenerateAccountFilterBtnHTML($account);
        $operationDisplay .= '<div class="operationsListWrapper hidden" id="account'. $account->idCompte .'">';
        $listOperations = $listOperationsAccount["$account->idCompte"];
        // Pour afficher les opérations dans l'ordre chronologique inverse
        $operationsHTML = "";
        foreach ($listOperations as $operation) {
            $operationsHTML = vueGenerateAccountOperationHTML($operation) . $operationsHTML;
        }
        
        $operationDisplay .= $operationsHTML. "</div>";
        debug($operationDisplay);
    }
    require_once('gabaritInfoClient.php');
}

function vueGenerateAccountOperationHTML ($operation) {
    $sign = ($operation->ISCREDIT == 0) ? "minus red" : "plus green";
    $operationHTML ='<div class="operationCard">
                        <div>
                            <h2>'.$operation->LIBELLE.':</h2>
                            <span class="number">
                                <i class="fa-solid fa-'.$sign.'"></i>
                                '.$operation->MONTANT.'€
                            </span>
                        </div>
                        <span class="date">'.$operation->DATEOPERATION.'</span>
                    </div>';
    return $operationHTML;
}
function vueGenerateAccountFilterBtnHTML ($account) {
    return '<button class="filterBtn lush-green inactive" id="btn'.$account->idCompte.'" data-id="'.$account->idCompte.'" onclick="toggleFilter(this)">
                <i class="fa-regular fa-circle"></i>'.
                $account->NOM.': '.$account->solde.'€'. 
            '</button>';
}

/**
 * Fonction qui affiche la page de resultat de recherche d'un client
 * Ne retourne rien
 * @param array $listClient c'est la liste des clients
 * @param string $link c'est le lien pour la synthèse
 */
function vueDisplayAdvanceSearchClient($listClient="") {
    $navbar = vueGenerateNavBar();
    if ($listClient == "") {
        $content = "";
    }
    else{

        $content='<form action="index.php" method="post">
                    <div class="rechercheOutputTableWrapper">
                        <div class="rechercheCell header">ID Client</div>
                        <div class="rechercheCell header">Nom</div>
                        <div class="rechercheCell header">Prénom</div>
                        <div class="rechercheCell header">Date de Naissance</div>
                        <div class="rechercheCell header"></div>';
        foreach ($listClient as $client) {
           $content .= '<div class="rechercheCell content">'.$client->idClient.'</div>
                        <div class="rechercheCell content">'.$client->nom.'</div>
                        <div class="rechercheCell content">'.$client->prenom.'</div>
                        <div class="rechercheCell content">'.$client->dateNaissance.'</div>
                        <button class="rechercheCell content" type="submit" name="infoClientFromAdvancedBtn">Synthèse<input type="number" name="idClient" value='.$client->idClient.' class="hidden"></button>';
        }
        $content .= '</div></form>';
    }
    require_once('gabaritRechercheClient.php');
}

function vueDisplayGestionPersonnelAll($listEmployee) {
    $navbar = vueGenerateNavBar();
    $content='<h1>Gestion des Employés</h1>
                <div class="employeTableWrapper">
                    <div class="employeTableHeaderWrapper">
                        <div class="employeCell header">Id</div>
                        <div class="employeCell header">Rôle</div>
                        <div class="employeCell header">Nom</div>
                        <div class="employeCell header">Prénom</div>
                        <div class="employeCell header">Login</div>
                        <div class="employeCell header">Mot de Passe</div>
                        <div class="employeCell header">Couleur</div>

                    </div>';
    foreach ($listEmployee as $employee) {
        $content .= vueGenerateGestionEmployeRow($employee);   
    }
    $content .= '</div>
                <form action="index.php" method="post">
                    <button type="submit" name="GestionPersonnelAddBtn" class="cta">
                        Ajouter un employé
                    </button>
                </form>';
    require_once('gabaritGestion.php');
}


function vueGenerateGestionEmployeRow($employee) {
    $colors = [
        "sunny-orange",
        "turquoise-cyan",
        "berry-red",
        "lush-green",
        "lavender",
        "lemon-yellow",
        "royal-purple",
        "ocean-blue",
        "coral-pink"
    ];
    $selectOptions = "";
    foreach ($colors as $color) {
        $selected = ($employee->COLOR == $color) ? "selected" : "";
        $selectOptions .= '<option value="'.$color.'"'.$selected.'>'.$color.'</option>';
    }
    $etat1=$employee->IDCATEGORIE==1 ? "selected": "";
    $etat2=$employee->IDCATEGORIE==2 ? "selected": "";
    $etat3=$employee->IDCATEGORIE==3 ? "selected": "";
    $row='<form action="index.php" method="post" class="employeTableContentWrapper" onformchange="displayValidBtn">
            <input  type="number" class="employeCell content" name="idEmployee" value="'.$employee->IDEMPLOYE.'" readonly="true">
            <select name="idCategorie" class="employeCell content">
                <option value="1" '.$etat1.' >Directeur</option>
                <option value="2" '.$etat2.' >Conseiller</option>
                <option value="3" '.$etat3.' >Agent d\'acceuil</option>
            </select>
            <input type="text" name="nameEmployee" class="employeCell content" value="'.$employee->NOM.'">
            <input type="text" name="firstNameEmployee" class="employeCell content" value="'.$employee->PRENOM.'">
            <input type="text" name="loginEmployee" class="employeCell content" value="'.$employee->LOGIN.'">
            <input type="password" name="passwordEmployee" class="employeCell content" value="'.$employee->PASSWORD.'">
            <select name="colorEmployee" class="employeCell content">
                '.$selectOptions.'
            </select>
            <button type="submit" name="ModifPersonnelOneBtn" class="employeBtn"><i class="fa-solid fa-pen-to-square"></i>Valider</button>
            <button type="submit" name="GestionPersonnelDeleteBtn" class="employeBtn red"><i class="fa-solid fa-trash-can"></i>Supprimer</button>
        </form>';
    return $row;
}




function vueDisplayGestionPersonnelAdd(){
    $navbar = vueGenerateNavBar();
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
    $navbar = vueGenerateNavBar();
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
    $navbar = vueGenerateNavBar();
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
    $navbar = vueGenerateNavBar();
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
    $navbar = vueGenerateNavBar();
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
    $navbar = vueGenerateNavBar();
    $content = "<p>".$error."</p><p><a href=\"index.php\"/> Revenir au forum </a></p>";
    require_once('gabaritErreur.php');
}


function vueDisplayAgendaConseiller($appointment, $admin){
    $navbar = vueGenerateNavBar();
    $bla = json_encode($appointment);
    echo json_encode($admin);
    require_once('gabaritAgentHomePage.php');
}





function vueDisplayCreateClient($listConseiller) {
    $navbar = vueGenerateNavBar();
    $optionSelect = '<label for="idEmployee" class="visually-hidden">Conseiller</label><select name="idEmployee" id="idEmployee" required>';
    foreach ($listConseiller as $conseiller) {
        $optionSelect .= "<option value=\"".$conseiller->idEmploye."\">".$conseiller->identiteEmploye."</option>";
    }
    $optionSelect .= "</select>";
    $content = '<div class="clientCreationWrapper"> 
                    <h1>Création d\'un client</h1>
                    <form action="index.php" method="post" class="clientCreationForm" required>
                        <div>
                            <select name="civiClient" >
                                <option value="M." >M.</option>
                                <option value="Mme." >Mme.</option>
                                <option value="Mx." >Other</option>
                            </select>
                            <input type="text" name="nameClient" placeholder="Nom" required>
                            <input type="text" name="firstNameClient" placeholder="Prénom" required>
                        </div>

                        <label for="dateOfBirthClient" class="visually-hidden">Date de naissance:</label>
                        <input type="date" name="dateOfBirthClient" id="dateOfBirthClient" placeholder="Date de naissance" required>
                        <label for="adressClient" class="visually-hidden">Adresse</label>
                        <input type="text" name="adressClient" id="adressClient" placeholder="Adresse" required>
                        <label for="phoneClient" class="visually-hidden">Numéro de téléphone</label>
                        <input type="tel" name="phoneClient" id="phoneClient" placeholder="Numéro de téléphone" pattern="((\+|00)?[1-9]{2}|0)[1-9]( ?[0-9]){8}" required>
                        <label for="emailClient" class="visually-hidden">Email</label>
                        <input type="mail" name="emailClient" id="emailClient" placeholder="Email" required>
                        <label for="professionClient" class="visually-hidden">Profession</label>
                        <input type="text" name="professionClient" id="professionClient" placeholder="Profession" required>
                        <label for="situationClient" class="visually-hidden">Situation familiale</label>
                        <input type="text" name="situationClient" id="situationClient" placeholder="Situation familiale" required>
                            '.$optionSelect.'
                        <input type="submit" name="createClientBtn" value="Creer le Client" class="cta" required>
                    </form>
                </div>';
    require_once('gabaritGestion.php');
}

function vueDisplaySetting($identity) {
    $navbar = vueGenerateNavBar();
    $selectOptions = '';
    $colors = [
        "sunny-orange",
        "turquoise-cyan",
        "berry-red",
        "lush-green",
        "lavender",
        "lemon-yellow",
        "royal-purple",
        "ocean-blue",
        "coral-pink"
    ];
    foreach ($colors as $color) {
        $selectOptions .= '<option value="'.$color.'">'.$color.'</option>';
    }
    $content='<div class="modInfoWrapper">
                <form action="index.php" method="post">
                    <h1>Modifier info personnel</h1>
                    <label for="loginEmployee"  class="visually-hidden">Login :</label>
                    <input type="text" name="loginEmployee" id="loginEmployee" class="modInfoField" value="'.$identity->LOGIN.'" required>
                    <div class="loginFormFieldWrapper">
                        <label for="landingPasswordField" class="visually-hidden">Mot de Passe</label>
                        <input type="password" name="passwordEmployee" id="passwordEmployee" class="modInfoPasswordField" placeholder="Password" required>
                        <button onclick="togglePasswordVisibility()" type="button" class="visibilityButton"><i class="fa-solid fa-eye-slash" id="visibilityIcon"></i></button>
                    </div>
                    <label for="colorEmployee" class="visually-hidden">Couleur : </label>
                    <select name="colorEmployee" id="colorEmployee" class="modInfoField">
                    '.$selectOptions.'
                    </select>
                    <input type="submit" name="ModifSettingOneBtn" value="Valider modification" class="cta modInfoField">
                </form>
            </div>
            <script>
            function togglePasswordVisibility() {
                let passwordField = document.getElementById("passwordEmployee");
                let icon = document.getElementById("visibilityIcon");
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
            </script> ';
    require_once('gabaritGestion.php');
}


function vueDisplayAddContract($idClient, $listTypeContract, $listeClient){
    $optionSelect = "<select name=\"idTypeContract\">";
    foreach ($listTypeContract as $typeContract) {
        $optionSelect .= "<option value=\"".$typeContract->IDTYPECONTRAT."\">".$typeContract->NOM."</option>";
    }
    $optionSelect .= "</select>";

    $datalist = "<input list=\"listClient\" name=\"idClient2\" ><datalist id=\"listClient\">";
    foreach ($listeClient as $client) {
        $datalist .= "<option value=\"".$client->IDCLIENT."\">".$client->NOM." ".$client->PRENOM."</option>";
    }
    $datalist .= "</datalist>";


    $content="<form action=\"index.php\" method=\"post\">
                    <p>
                        ".$optionSelect.$datalist."
                        <input type=\"number\" name=\"monthCost\" placeholder=\"Cout Mensuel\" step=\"0.01\">
                        <input type=\"hidden\" name=\"idClient\" value=\"".$idClient."\">
                        <input type=\"submit\" name=\"createContractBtn\" value=\"Crée contrat\">
                    </p>
                </form>";
    require_once('gabaritGestion.php');
}


function vueDisplayAddAccount($idClient, $listTypeAccount, $listeClient){
    $optionSelect = "<select name=\"idTypeAccount\">";
    foreach ($listTypeAccount as $typeAccount) {
        $optionSelect .= "<option value=\"".$typeAccount->IDTYPECOMPTE."\">".$typeAccount->NOM."</option>";
    }
    $optionSelect .= "</select>";

    $datalist = "<input list=\"listClient\" name=\"idClient2\" ><datalist id=\"listClient\">";
    foreach ($listeClient as $client) {
        $datalist .= "<option value=\"".$client->IDCLIENT."\">".$client->NOM." ".$client->PRENOM."</option>";
    }
    $datalist .= "</datalist>";


    $content="<form action=\"index.php\" method=\"post\">
                    <p>
                        ".$optionSelect.$datalist."
                        <input type=\"number\" name=\"monthCost\" placeholder=\"Découvert\" step=\"0.01\">
                        <input type=\"hidden\" name=\"idClient\" value=\"".$idClient."\">
                        <input type=\"submit\" name=\"createAccountBtn\" value=\"Crée compte\">
                    </p>
                </form>"; 
    require_once('gabaritGestion.php');
}

