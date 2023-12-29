<?php

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
 * Fonction qui affiche la page de login
 * @return void
 */
function vueDisplayLogin(){
    $content="";
    require_once('gabaritLanding.php');
}

/**
 * Fonction qui affiche la page d'erreur
 * @param string $error
 * @return void
 */
function vueDisplayError ($error) {
    $content = "<p>".$error."</p><p><a href=\"index.php\"/> Revenir à l’accueil </a></p>";
    require_once('gabaritErreur.php');
}

/**
 * Fonction qui affiche la page d'accueil du directeur
 * @param array $stat c'est les statistiques
 * @param string $username c'est le nom de l'utilisateur qui sera affiché dans la navbar
 * @return void
 */
function vueDisplayHomeDirecteur($stat, $username){
    $navbar = vueGenerateNavBar();
    $content="";
    require_once('gabaritDirecteurHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil du conseiller
 * Déclenche la création de la navbar et du code HTML des events
 * @param array $appointments c'est les rendez-vous
 * @param array $TAS c'est les tâches à accomplir
 * @param DateTime $dateOfWeek c'est les dates de la semaine
 * @param string $username c'est le nom de l'utilisateur qui sera affiché dans la navbar
 * @param string $fullName c'est le nom complet de l'utilisateur qui sera affiché dans la navbar
 * @return void
 */
function vueDisplayHomeConseiller($appointments, $TAS, $dateOfWeek, $username, $fullName){
    $navbar = vueGenerateNavBar();
    $weekEvents = array("", "", "", "", "", "", "");
    $listConseiller = array();
    // $weekEvents représente pour chaque entrée de 0 à 6, en chaîne de caractères, les eventHTML du jour correspondant
    foreach ($appointments as $appointment) {
        if (!in_array("$appointment->IDENTITEEMPLOYE", $listConseiller)){
            array_push($listConseiller, "$appointment->IDENTITEEMPLOYE", "$appointment->COLOR");
        }
        $appointmentDate = date_create_from_format("Y-m-d H:i:s", $appointment->HORAIREDEBUT);
        $weekNumber = date_format($appointmentDate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAppointementHTML($appointment);
    }
    foreach ($TAS as $TA) {
        if (!in_array("$TA->IDENTITEEMPLOYE", $listConseiller)){
            array_push($listConseiller, "$TA->IDENTITEEMPLOYE", "$TA->COLOR");
        }
        $TADate = date_create_from_format("Y-m-d H:i:s", $TA->HORAIREDEBUT);
        $weekNumber = date_format($TADate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAdminHTML($TA);
    }
    $filterWrapper="";
    $filterWrapper = vueGenerateCalendarFilter($listConseiller);
    require_once('gabaritConseillerHomePage.php');
}

/**
 * Fonction qui affiche la page d'accueil de l'agent d'accueil
 * Déclenche la création de la navbar et du code HTML des events
 * @param array $appointments c'est les rendez-vous
 * @param array $TAS c'est les tâches à accomplir
 * @param DateTime $dateOfWeek c'est les dates de la semaine
 * @param string $username c'est le nom de l'utilisateur qui sera affiché dans la navbar
 * @return void
 */
function vueDisplayHomeAgent($appointments, $TAS, $dateOfWeek, $username) {
    $navbar = vueGenerateNavBar();
    $weekEvents = array("", "", "", "", "", "", "");
    $listConseiller = array();
    // $weekEvents représente pour chaque entrée de 0 à 6, en chaîne de caractères, les eventHTML du jour correspondant
    foreach ($appointments as $appointment) {
        if (!in_array("$appointment->IDENTITEEMPLOYE", $listConseiller)){
            array_push($listConseiller, "$appointment->IDENTITEEMPLOYE", "$appointment->COLOR");
        }
        $appointmentDate = date_create_from_format("Y-m-d H:i:s", $appointment->HORAIREDEBUT);
        $weekNumber = date_format($appointmentDate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAppointementHTML($appointment);
    }
    foreach ($TAS as $TA) {
        if (!in_array("$TA->IDENTITEEMPLOYE", $listConseiller)){
            array_push($listConseiller, "$TA->IDENTITEEMPLOYE", "$TA->COLOR");
        }
        $TADate = date_create_from_format("Y-m-d H:i:s", $TA->HORAIREDEBUT);
        $weekNumber = date_format($TADate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAdminHTML($TA);
    }
    $filterWrapper="";
    $filterWrapper = vueGenerateCalendarFilter($listConseiller);



    require_once('gabaritAgentHomePage.php');
}

function vueGenerateCalendarFilter($listConseiller){
    $filterWrapper = '<div class="filterWrapper">';
    debug($listConseiller);
    for ($i=0; $i < count($listConseiller); $i+=2) {
        $conseiller = $listConseiller[$i];
        $color = $listConseiller[$i+1];
        $filterWrapper .= '<button class="filterBtn inactive '.$color.'" onclick="filterToggle(this)" title="Selectionner '.$conseiller.'" data-conseiller="'.$conseiller.'" id="'.$conseiller.'"><i class="fa-regular fa-square" aria-hidden="true"></i>'.$conseiller.'</button>';
    }
    $filterWrapper .= '</div>';
    return $filterWrapper;
}


/**
 * Fonction qui génère le code HTML de la navbar
 * @return string Le code HTML de la navbar
 */
function vueGenerateNavBar() {
    $datalist = '<input list="listClient" name="searchClientByIdField" class="searchField" placeholder="Id du client" required><datalist id="listClient">';
    foreach ($_SESSION["listClient"] as $client) {
        $datalist .= '<option value="'.$client->IDCLIENT.'">'.$client->IDCLIENT.' - '.$client->NOM.' '.$client->PRENOM.' - '.$client->DATENAISSANCE.'</option>';
    }
    $datalist .= "</datalist>";
    $navbarHTML = 
    '<div class="navWrapper">
        <nav>
            <form action="index.php" method="post">
                    <button class="squareIconButton" name="homeBtn" title="Retour à l\'Accueil" onclick="javascript:location.reload();">
                        <i class="fa-solid fa-house"></i>
                    </button>
            </form>
            <form action="index.php" method="post" class="searchWrapper">
                <label for="searchClientByIdField" class="visually-hidden">Chercher un client</label>
                '.$datalist.'
                <button class="searchButton" name="searchClientBtn" title="Recherche par ID">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
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
    </div>';
    return $navbarHTML;
    
}

/**
 * Fonction qui affiche la page de résultat de recherche d'un client
 * Ne retourne rien
 * @param array $listClient c'est la liste des clients (optionnel)
 * @return void
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

/**
 * Fonction qui affiche la page de création d'un client
 * @param array $listConseiller c'est la liste des conseillers
 * @return void
 */
function vueDisplayCreateClient($listConseiller) {
    $titre = "Création d'un client - Bank";
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
                        <input type="submit" name="createClientBtn" value="Créer le Client" class="cta" required>
                    </form>
                </div>';
    require_once('gabaritGestion.php');
}


// ------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------- Gestion Personnel -------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------


/**
 * Fonction qui affiche la page de setting d'un employé
 * @param object $identity c'est les données de l'employé
 * @return void
 */
function vueDisplaySetting($identity) {
    $titre = "Paramètres - Bank";
    $navbar = vueGenerateNavBar();
    $selectOptions = '';
    global $colors;
    $selectOptions = "";
    foreach ($colors as $color) {
        $selected = ($identity->COLOR == $color) ? "selected" : "";
        $selectOptions .= '<option value="'.$color.'"'.$selected.' class="'.$color.'-text'.'">'.$color.'</option>';
    }
    $content='<div class="modInfoWrapper">
                <form action="index.php" method="post" id="formPassword">
                    <h1>Modifier info personnel</h1>
                    <label for="loginEmployee"  class="visually-hidden">Login :</label>
                    <input type="text" name="loginEmployee" id="loginEmployee" class="modInfoField" value="'.$identity->LOGIN.'" placeholder="Login" required>
                    <div class="loginFormFieldWrapper">
                        <label for="landingPasswordField" class="visually-hidden">Mot de Passe</label>
                        <input type="password" name="passwordEmployee" id="PasswordField" class="modInfoPasswordField" placeholder="Password" required>
                        <button onclick="togglePasswordVisibility(\'PasswordField\')" type="button" class="visibilityButton"><i class="fa-solid fa-eye-slash" id="visibilityIcon"></i></button>
                    </div>
                    <label for="colorEmployee" class="visually-hidden">Couleur : </label>
                    <select name="colorEmployee" id="colorEmployee" class="modInfoField">
                    '.$selectOptions.'
                    </select>
                    <input type="submit" name="ModifSettingOneBtn" value="Valider modification" id="connectBtn" class="cta modInfoField">
                </form>
            </div>';
    require_once('gabaritGestion.php');
}

/**
 * Fonction qui affiche la page de gestion des employés
 * @param array $listEmployee c'est la liste des employés
 * @return void
 */
function vueDisplayGestionPersonnelAll($listEmployee) {
    $titre = "Gestion du personnel - Bank";
    $navbar = vueGenerateNavBar();
    $content='<div class="employeTableWrapper">
                <h1>Gestion des Employés</h1>
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
    $content .= '<form action="index.php" method="post">
                    <button type="submit" name="GestionPersonnelAddBtn" class="GestionPersonnelAddBtn">
                        <i class="fa-solid fa-plus"></i> Ajouter un employé
                    </button>
                </form></div>';
    require_once('gabaritGestion.php');
}

/**
 * Fonction qui génère le code HTML d'une ligne de la table de gestion des employés
 * @param object $employee C'est les données de l'employé
 * @return string Le code HTML de la ligne d'un employé
 */
function vueGenerateGestionEmployeRow($employee) {
    global $colors;
    $selectOptions = "";
    foreach ($colors as $color) {
        $selected = ($employee->COLOR == $color) ? "selected" : "";
        $selectOptions .= '<option value="'.$color.'"'.$selected.'>'.$color.'</option>';
    }

    $etat1=$employee->IDCATEGORIE==1 ? "selected": "";
    $etat2=$employee->IDCATEGORIE==2 ? "selected": "";
    $etat3=$employee->IDCATEGORIE==3 ? "selected": "";
    $row='<form action="index.php" method="post" class="employeTableContentWrapper" id="formPassword'.$employee->IDEMPLOYE.'">
            <input  type="number" class="employeCell content" name="idEmployee" value="'.$employee->IDEMPLOYE.'" readonly="true">
            <select name="idCategorie" class="employeCell content">
                <option value="1" '.$etat1.' >Directeur</option>
                <option value="2" '.$etat2.' >Conseiller</option>
                <option value="3" '.$etat3.' >Agent d\'accueil</option>
            </select>
            <input type="text" name="nameEmployee" class="employeCell content" value="'.$employee->NOM.'">
            <input type="text" name="firstNameEmployee" class="employeCell content" value="'.$employee->PRENOM.'">
            <input type="text" name="loginEmployee" class="employeCell content" value="'.$employee->LOGIN.'">
            <div class="employeCell content">
            <input type="password" name="passwordEmployee" id="PasswordField'.$employee->IDEMPLOYE.'" class="loginFormField">
            <button onclick="togglePasswordVisibility(\'PasswordField'.$employee->IDEMPLOYE.'\')" type="button" class="visibilityButton"><i class="fa-solid fa-eye-slash" id="visibilityIcon"></i></button>
            </div>
            <select name="colorEmployee" class="employeCell content">
                '.$selectOptions.'
            </select>
            <input type="submit" name="ModifPersonnelOneBtn" id="connectBtn'.$employee->IDEMPLOYE.'" class="employeBtn">
            <button type="submit" name="GestionPersonnelDeleteBtn" class="employeBtn red"><i class="fa-solid fa-trash-can"></i>Supprimer</button>
        </form>';
    return $row;
}

/**
 * Fonction qui affiche la page de création d'un employé
 * @return void
 */
function vueDisplayGestionPersonnelAdd(){
    $titre = "Ajout d'un employé - Bank";
    $navbar = vueGenerateNavBar();
    $selectOptions = '';
    global $colors;
    foreach ($colors as $color) {
        $selectOptions .= '<option value="'.$color.'" class="'.$color.'-text">'.$color.'</option>';
    }
    $content='<form action="index.php" method="post" class="gestionPersonnelAddForm">
                    <div>
                        <h1>Ajouter un employé</h1>
                        <select name="idCategorie" class="gestionPersonnelAddInput" required>
                            <option value="1" >Directeur</option>
                            <option value="2" >Conseiller</option>
                            <option value="3" >Agent d\'accueil</option>
                        </select>
                        <input type="text" name="nameEmployee" placeholder="Nom" class="gestionPersonnelAddInput" required>
                        <input type="text" name="firstNameEmployee" placeholder="Prénom" class="gestionPersonnelAddInput" required>
                        <input type="text" name="loginEmployee" placeholder="Login" class="gestionPersonnelAddInput" required>
                        <input type="password" name="passwordEmployee" id="passwordEmployee" placeholder="Mot de passe" class="gestionPersonnelAddInput" required>
                        <select name="colorEmployee" class="gestionPersonnelAddInput" required>
                            '.$selectOptions.'
                        </select>
                        <input type="submit" name="AddPersonnelSubmitBtn" id="AddPersonnelSubmitBtn" class="hidden">
                        <button type="button" name="AddPersonnelSubmitBtn" class="gestionPersonnelAddInput" onclick="sent(\'passwordEmployee\',\'AddPersonnelSubmitBtn\')">
                            <i class="fa-solid fa-check"></i> Valider ajout
                        </button>
                    </div>
                </form>';
    require_once('gabaritGestion.php');
}


// ------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------- Gestion Service -------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------


/**
 * Fonction qui affiche la page de gestion des services
 * @param array $listTypeAccount c'est la liste des types de compte
 * @param array $listTypeContract c'est la liste des types de contrat
 * @return void
 */
function vueDisplayGestionServicesAll($listTypeAccount, $listTypeContract) {
    $titre = "Gestion des services - Bank";
    $navbar = vueGenerateNavBar();
    $content ='<div class="gestionServiceWrapper">
                <h1>Gestion des services</h1>
                <h2>Liste des type de compte</h2>
                    <div class="gestionServiceTableWrapper">
                        <div class="gestionServiceTableHeaderWrapper">
                            <div class="gestionServiceCell header">ID</div>
                            <div class="gestionServiceCell header">Intitulé</div>
                            <div class="gestionServiceCell header">Actif</div>
                            <div class="gestionServiceCell header">Document</div>
                            <div></div>
                            <div></div>
                        </div>';
    foreach ($listTypeAccount as $typeAccount) {
        $actif = ($typeAccount->ACTIF == 1) ? "checked" : "";
        $content .= '<form action="index.php" method="post" class="gestionServiceTableContentWrapper">
                        <input type="text" name="idAccount" class="gestionServiceCell content" value="'.$typeAccount->IDTYPECOMPTE.'" readonly="true">
                        <input type="text" name="nameAccount" class="gestionServiceCell content" value="'.$typeAccount->NOM.'">
                        <div class="gestionServiceCell content"><input type="checkbox" name="activeAccount" class="gestionServiceCell content" '.$actif.'></div>
                        <input type="text" name="documentAccount" class="gestionServiceCell content" value="'.$typeAccount->DOCUMENT.'">
                        <input type="hidden" name="idMotif" value="'.$typeAccount->IDMOTIF.'" class="gestionPersonnelAddInput">
                        <button type="submit" name="ModifAccountOneBtn" class="employeBtn">
                        <i class="fa-solid fa-pen-to-square"></i> Modifier le type de compte
                        </button>
                        <button type="submit" name="GestionAccountDeleteBtn" class="employeBtn red">
                            <i class="fa-solid fa-trash-can"></i> Supprimer le type de compte
                        </button>
                    </form>';
    }
    $content .='</div>
                <h2>Liste des type de Contrat</h2>
                <div class="gestionServiceTableWrapper">
                    <div class="gestionServiceTableHeaderWrapper">
                        <div class="gestionServiceCell header">ID</div>
                        <div class="gestionServiceCell header">Intitulé</div>
                        <div class="gestionServiceCell header">Actif</div>
                        <div class="gestionServiceCell header">Document</div>
                        <div></div>
                        <div></div>
                    </div>';

    foreach ($listTypeContract as $typeContract) {
        $actif = ($typeContract->ACTIF == 1) ? "checked" : "";
        $content .= '<form action="index.php" method="post" class="gestionServiceTableContentWrapper">
                        <input type="text" name="idContract" class="gestionServiceCell content" value="'.$typeContract->IDTYPECONTRAT.'" readonly="true">
                        <input type="text" name="nameContract" class="gestionServiceCell content" value="'.$typeContract->NOM.'">
                        <div class="gestionServiceCell content"><input type="checkbox" name="activeContract" class="gestionServiceCell content" '.$actif.'></div>
                        <input type="text" name="documentContract" class="gestionServiceCell content" value="'.$typeContract->DOCUMENT.'">
                        <input type="hidden" name="idMotif" value="'.$typeContract->IDMOTIF.'" class="gestionPersonnelAddInput">
                        <button type="submit" name="ModifContractOneBtn" class="employeBtn">
                        <i class="fa-solid fa-pen-to-square"></i>Modifier le type de contrat
                        </button>
                        <button type="submit" name="GestionContractDeleteBtn" class="employeBtn red">
                            <i class="fa-solid fa-trash-can"></i>Supprimer le type de contrat
                        </button>
                    </form>';
    }
    $content .= '</div><form action="index.php" method="post">
                    <p>
                        <button type="submit"  name="GestionServicesAddBtn" class="GestionPersonnelAddBtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un Service
                        </button>
                    </p>
                </form>';
    require_once('gabaritGestion.php');
}

/**
 * Fonction qui affiche la page de création d'un service
 * @return void
 */
function vueDisplayGestionServicesAdd(){
    $titre = "Ajout d'un service - Bank";
    $navbar = vueGenerateNavBar();
    $content='<form action="index.php" method="post" class="gestionPersonnelAddForm">
                    <div>
                        <h1>Ajouter un service</h1>
                        <select name="typeService" class="gestionPersonnelAddInput" required>
                            <option value="1" >Compte</option>
                            <option value="2" >Contrat</option>
                        </select>
                        <input type="text" name="nameService" placeholder="Nom" class="gestionPersonnelAddInput" required>
                        <input type="text" name="documentService" placeholder="Document" class="gestionPersonnelAddInput" required>
                        <div class="gestionPersonnelAddInput">
                            <label for="activeService">Actif:<label>
                            <input type="checkbox" name="activeService" checked>
                        </div>
                        <input type="submit" name="AddServiceSubmitBtn" value="Valider ajout" class="gestionPersonnelAddInput">
                    </div>
                </form>';
    require_once('gabaritGestion.php');
}


// ------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------- Synthèse Client -------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------


/**
 * Fonction qui affiche la page de synthèse d'un client
 * @param object $client c'est les données du client
 * @param array $listAccounts c'est la liste des comptes du client
 * @param array $listContract c'est la liste des contrats du client
 * @param array $listOperationsAccount c'est la liste des opérations des comptes du client
 * @param array $listRDVClients c'est la liste des rendez-vous du client
 * @return void
 */
function vueDisplayInfoClient($client, $listAccounts, $listContract, $listOperationsAccount, $listRDVClients){
    $navbar = vueGenerateNavBar();
    $events = vueAppointementClient($listRDVClients);
    $listC = vueCreateListContract($listContract);
    list($listA, $optionSelect, $typeClass) = vueCreateListAccount($listAccounts);
    list($filterBtns, $operationDisplay) = vueGenerateOperation($listAccounts, $listOperationsAccount);
    list($createAccount, $createContract) = vueGenerateButtonCreate($client->IDCLIENT);

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
    require_once('gabaritInfoClient.php');
}

/**
 * Fonction qui affiche la liste des RDV d'un client sur la synthèse client
 * @param array $listRDVClients c'est la liste des rendez-vous
 * @return string Le code HTML de la liste des rendez-vous
 */
function vueAppointementClient($listRDVClients){
    $events = "";
    foreach ($listRDVClients as $appointements) {
        $events .= vueGenerateAppointementHTML($appointements);
    }
    $events .= ($events == "") ? '<p style="margin-left:1em;">Pas de rendez-vous.</p>' : "";
    return $events;
}

/**
 * Fonction qui génère le code HTML de la liste des contrats d'un client
 * @param array $listContract c'est la liste des contrats du client
 * @return string Le code HTML de la liste des contrats
 */
function vueCreateListContract($listContract){
    $listC = ($_SESSION["type"] == 2) ? '<div class="accountCell header">Suppression</div>' : '';
    foreach ($listContract as $contract) {
        $listC .= '<div class="contractCell content">'.$contract->NOM.'</div>
            <div class="contractCell content">'.$contract->tarifmensuel.'€</div>';
        if ($_SESSION["type"] == 2) {
            $listC.='<div class="contractCell content">
                        <form action="index.php" method="post">
                            <input type="hidden" name="idContract" value="'.$contract->idContrat.'">
                            <input type="submit" value="Supprimer le contrat" name="deleteContractBtn">
                        </form>
                    </div>';
        }
        $listC .= '</div>';
    }
    return $listC;
}

/**
 * Fonction qui génère le code HTML de la liste des comptes d'un client
 * @param array $listContract c'est la liste des comptes du client
 * @return array retourne un tableau avec le code HTML de la liste des comptes et le code HTML de la liste des comptes pour le select et la classe de la div
 */
function vueCreateListAccount($listAccounts){
    $optionSelect = "";
    // pour faire la liste des comptes
    if ($_SESSION["type"] == 2) {
        $listA = '<div class="accountCell header">Suppression</div>';
        $typeClass = 'conseiller';
    }
    else{
        $listA = '';
        $typeClass = 'agent';
    }
    
    foreach ($listAccounts as $account) {
        $optionSelect .= "<option value=\"".$account->idCompte."\">".$account->NOM.': '. $account->solde ."€</option>";
        $listA .= '<div class="accountCell content">'.$account->NOM.'</div>
            <div class="accountCell content">'.$account->solde.'€</div>
            <form action="index.php" method="post" class="accountCell content">
                <input type="number" name="overdraft" value="'.$account->decouvert.'" step="0.01">
                <input type="hidden" name="idAccount" value="'.$account->idCompte.'">
                <button type="submit" name="modifOverdraftBtn">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Modifier le découvert
                </button>
            </form>';
        if ($_SESSION["type"] == 2) {
            $listA.='
            <form action="index.php" method="post" class="accountCell content">
                <input type="hidden" name="idAccount" value="'.$account->idCompte.'">
                <button type="submit" name="deleteAccountBtn" class="red">
                    <i class="fa-solid fa-trash-can"></i>
                    Supprimer le compte
                </button>
            </form>';
        }
    }
    return array($listA, $optionSelect, $typeClass);
}

/**
 * Fonction qui génère le code HTML des opérations d'un compte avec le bouton de filtre
 * @param $listAccounts c'est la liste des comptes
 * @param $listOperationsAccount c'est la liste des opérations des comptes
 * @return array retourne un tableau avec le code HTML des boutons de filtre et le code HTML des opérations
 */
function vueGenerateOperation($listAccounts, $listOperationsAccount){
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
    }
    return array($filterBtns, $operationDisplay);
}

/**
 * Fonction qui génère le code HTML d'un bouton de filtre de compte
 * @param object $account C'est les données du compte
 * @return string Le code HTML du bouton de filtre
 */
function vueGenerateAccountFilterBtnHTML ($account) {
    return '<button class="filterBtn lush-green inactive" id="btn'.$account->idCompte.'" data-id="'.$account->idCompte.'" onclick="toggleFilter(this)">
                <i class="fa-regular fa-circle"></i>'.
                $account->NOM.': '.$account->solde.'€'. 
            '</button>';
}

/**
 * Fonction qui génère le code HTML d'une opération
 * @param object $operation C'est les données de l'opération
 * @return string Le code HTML de l'opération
 */
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

/**
 * Fonction qui génère le code HTML des boutons de création de compte et de contrat
 * @param $idClient c'est l'id du client
 * @return array retourne un tableau avec le code HTML des boutons de création de compte et de contrat
 */
function vueGenerateButtonCreate($idClient){
    if ($_SESSION["type"] == 2) {
        $createAccount='<div>
                            <form action="index.php" method="post">
                                <input type="hidden" name="idClient" value="'.$idClient.'">
                                <div class="btnWrapper">
                                    <button type="submit" name="addAccountBtn" class="btn">
                                        <i class="fa-solid fa-plus"></i> Ajouter un compte
                                    </button>
                                </div>
                            </form>
                        </div>';
        $createContract = '<div>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="idClient" value="'.$idClient.'">
                                    <div class="btnWrapper">
                                        <button type="submit" name="addContractBtn" class="btn">
                                            <i class="fa-solid fa-plus"></i> Ajouter un contrat
                                        </button>
                                    </div>
                                </form>
                            </div>';
    }
    else {
        $createAccount="";
        $createContract="";
    }
    return array($createAccount, $createContract);
}

/**
 * Fonction qui affiche la page de création d'un contrat pour un client
 * @param int $idClient c'est l'id du client
 * @param array $listTypeContract c'est la liste des types de contrat
 * @param array $listeClient c'est la liste des clients
 * @return void
 */
function vueDisplayAddContract($idClient, $listTypeContract, $listeClient){
    $titre = "Création d'un contrat - Bank";
    $navbar = vueGenerateNavBar();
    $optionSelect = '<select name="idTypeContract" class="addContractField">';
    if (count($listTypeContract) == 0) {
        $content = '<div>Aucun type de compte disponible</div>';
        require_once('gabaritGestion.php');
    }
    foreach ($listTypeContract as $typeContract) {
        $optionSelect .= '<option value="'.$typeContract->IDTYPECONTRAT.'">'.$typeContract->NOM.'</option>';
    }
    $optionSelect .= '</select>';

    $datalist = '<div class="addContractField"><label for="idClient2">Bénéficiaire n°2</label><input list="listClient" name="idClient2"><datalist id="listClient">';
    foreach ($listeClient as $client) {
        $datalist .= '<option value="'.$client->IDCLIENT.'">'.$client->IDCLIENT.' '.$client->NOM.' '.$client->PRENOM.'</option>';
    }
    $datalist .= '</datalist></div>';

    $content='<div class="addContractWrapper"><form action="index.php" method="post" class="addContractForm">
                <h1>Ajouter un Type de Contrat</h1>
                '.$optionSelect.$datalist.'
                <input type="number" name="monthCost" placeholder="Coût Mensuel" step="0.01" class="addContractField" required>
                <input type="hidden" name="idClient" value="'.$idClient.'">
                <button type="submit" name="createContractBtn" class="addContractField cta">
                    Valider la création
                </button>
                </form></div>';
    require_once('gabaritGestion.php');
}

/**
 * Fonction qui affiche la page de création d'un compte pour un client
 * @param int $idClient c'est l'id du client
 * @param array $listTypeAccount c'est la liste des types de compte
 * @param array $listeClient c'est la liste des clients
 * @return void
 */
function vueDisplayAddAccount($idClient, $listTypeAccount, $listeClient){
    $titre = "Création d'un compte - Bank";
    $navbar = vueGenerateNavBar();
    if (count($listTypeAccount) == 0) {
        $content = "<div>Aucun type de compte disponible</div>";
        require_once('gabaritGestion.php');
    }
    $optionSelect = '<select name="idTypeAccount" class="addContractField">';
    foreach ($listTypeAccount as $typeAccount) {
        $optionSelect .= "<option value=\"".$typeAccount->IDTYPECOMPTE."\">".$typeAccount->NOM."</option>";
    }
    $optionSelect .= "</select>";

    $datalist = '<div class="addContractField"><label for="idClient2">Bénéficiaire n°2</label><input list="listClient" name="idClient2" ><datalist id="listClient">';
    foreach ($listeClient as $client) {
        $datalist .= "<option value=\"".$client->IDCLIENT."\">".$client->IDCLIENT." ".$client->NOM." ".$client->PRENOM."</option>";
    }
    $datalist .= "</datalist></div>";


    $content='<div class="addContractWrapper"><form action="index.php" method="post" class="addContractForm">
                    <h1>Ajouter un Type de Compte</h1>
                    '.$optionSelect.$datalist.'
                    <input type="number" name="overdraft" placeholder="Découvert" step="0.01" class="addContractField">
                    <input type="hidden" name="idClient" value="'.$idClient.'">
                    <button type="submit" name="createAccountBtn" class="addContractField cta">
                        Valider la création
                    </button>
                </form></div>'; 
    require_once('gabaritGestion.php');
}


// ------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------- APPOINTENT -------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------


/**
 * Fonction qui génère le code HTML d'un RDV
 * @param object $appointment C'est les données du rendez-vous
 * @return string Le code HTML de l'event
 */
function vueGenerateAppointementHTML($appointment) {
    $heureDebut = (substr($appointment->HORAIREDEBUT, 11, 5));
    $heureFin = (substr($appointment->HORAIREFIN, 11, 5)); 
    return '<div class="event" data-conseiller="'. $appointment->IDENTITEEMPLOYE .'" data-color="'. $appointment->COLOR .'">
        <div class="eventTitleCard">
            <h2>'. $appointment->INTITULE .'</h2>
            <i class="fa-solid fa-users"></i>
        </div>
        <form action="index.php" method="post">
            <input type="number" name="searchClientByIdField" id="searchClientByIdField" class="hidden" value="'.$appointment->IDCLIENT.'">
            <input type="submit" name="searchClientBtn" value="'. $appointment->identiteClient .'" class="eventClientInput">
        </form>
        <p class="document">
            Documents a apporter: '.$appointment->DOCUMENT.'
        </p>
        <div class="eventDetails">
            <div>
                <p class="eventStartTime">'. $heureDebut .'</p>
                <p class="eventEndTime">'. $heureFin .'</p>
            </div>
            <div class="eventConseiller '.$appointment->COLOR.'">
                <i class="fa-solid fa-user-tie"></i>
                '. $appointment->IDENTITEEMPLOYE .'
            </div>
        </div>
        <form action="index.php" method="post" class="deleteForm">
            <input type="number" name="idRDVField" id="idRDVField" class="hidden" value="'.$appointment->IDRDV.'">
            <button type="submit" class="deleteRDVbtn" name="deleteRDVbtn">
                <i class="fa-solid fa-trash-can"></i> Supprimer
            </button>
        </form>
    </div>';
}

/**
 * Fonction qui génère le code HTML d'une tâche administrative
 * @param object $TA C'est les données de la tâche administrative
 * @return string Le code HTML de l'event
 */
function vueGenerateAdminHTML($TA) {
    $heureDebut = (substr($TA->HORAIREDEBUT, 11, 5));
    $heureFin = (substr($TA->HORAIREFIN, 11, 5));
    return '<div class="event" data-conseiller="'. $TA->IDENTITEEMPLOYE .'" data-color="'. $TA->COLOR .'">
        <div class="eventTitleCard">
            <h2>'. $TA->LIBELLE .'</h2>
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <div class="eventDetails">
            <div>
                <p class="eventStartTime">'. $heureDebut .'</p>
                <p class="eventEndTime">'. $heureFin .'</p>
            </div>
            <div class="eventConseiller '.$TA->COLOR.'">
                <i class="fa-solid fa-user-tie"></i>
                '. $TA->IDENTITEEMPLOYE .'
            </div>
        </div>
        <form action="index.php" method="post" class="deleteForm">
        <input type="number" name="idTAField" id="idTAField" class="hidden" value="'.$TA->IDTA.'">
        <button type="submit" class="deleteRDVbtn" name="deleteTAbtn">
            <i class="fa-solid fa-trash-can"></i> Supprimer
        </button>
    </form>
    </div>';
}

/**
 * Fonction qui affiche la page de création d'un évènement
 * @param array $listConseillers c'est la liste des conseillers
 * @param array $listClients c'est la liste des clients
 * @param array $listMotifs c'est la liste des motifs
 * @param string $date c'est la date de l'évènement
 * @param array|ArrayObject $rdvArray c'est la liste des rendez-vous
 * @param string $clientActuel c'est le client actuel (optionnel)
 * @return void
 */
function vueDisplayAddAppointement($listConseillers, $listClients, $listMotifs, $date, $rdvArray) {
    $titre = "Prendre un rendez-vous - Bank";
    $navbar = vueGenerateNavBar();
    $conseillersOption = "";
    $clientOption = "";
    $motifsOption = "";
    $events = "";
    foreach ($rdvArray[0] as $appointment) {
        $events .= vueGenerateAppointementHTML($appointment);
    }
    foreach ($rdvArray[1] as $TA) {
        $events .= vueGenerateAdminHTML($TA);
    }
    foreach ($listClients as $client) {
        $clientOption .= '<option value="'.$client->IDCLIENT.'" data-conseiller="'.$client->IDEMPLOYE.'">'.$client->PRENOM.' '.$client->NOM.'</option>';
    }
    foreach ($listConseillers as $conseiller) {
        $conseillersOption .= '<option value="'.$conseiller->idEmploye.'">'.$conseiller->identiteEmploye.'</option>';
    }
    foreach ($listMotifs as $motif) {
        $motifsOption .= '<option value="'.$motif->IDMOTIF.'">'.$motif->INTITULE.'</option>';
    }
    $content = '
            <div class="addAppointementRDVWrapper">
                <div class="addAppointementWrapper">
                    <form action="index.php" method="post" class="addAppointementForm">
                        <h1>Ajouter un rendez-vous</h1>
                        <div class="field">
                            <label for="appointementsDateField">Date</label>
                            <input type="date" name="appointementsDateField" id="appointementsDateField" value="'.$date.'" required readonly>
                        </div>
                        <div class="field">
                            <label for="appointementsDateField">Horaire de début</label>
                            <input type="time" name="appointementsHoraireDebutField" id="appointementsHoraireDebutField" required>
                        </div>
                        <div class="field">
                            <label for="appointementsDateField">Horaire de fin</label>
                            <input type="time" name="appointementsHoraireFinField" id="appointementsHoraireFinField" required>
                        </div>
                        <select name="appointementsClientField" id="appointementsClientField" class="field" required onChange="changeConseiller(this)">
                            '.$clientOption.'
                        </select>
                        <select name="appointementsConseillerField" id="appointementsConseillerField" class="field"required>
                            '.$conseillersOption.'
                        </select>
                        <select name="appointementsMotifField" id="appointementsMotifField" class="field"required>
                            '.$motifsOption.'
                        </select>
                        <button type="submit" name="addAppointementsBtn" class="cta field">
                            Valider
                        </button>
                    </form>
                </div>
                <div class="addAppointementWrapper">
                    <div class="events">
                        '.$events.'
                    </div>
                </div>
            </div>';
    require_once('gabaritGestion.php');

}

/**
 * Fonction qui affiche la page de création d'un évènement pour un conseiller
 * @param array $listClients c'est la liste des clients
 * @param array $listMotifs c'est la liste des motifs
 * @param string $date c'est la date de l'évènement
 * @param array|ArrayObject $rdvArray c'est la liste des rendez-vous
 * @return void
 */
function vueDisplayAddAppointementConseiller($listClients, $listMotifs, $date, $rdvArray) {
    $titre = "Prendre un rendez-vous - Bank";
    $navbar = vueGenerateNavBar();
    $clientOption = "";
    $motifsOption = "";
    $events = "";
    foreach ($rdvArray[0] as $appointment) {
        $events .= vueGenerateAppointementHTML($appointment);
    }
    foreach ($rdvArray[1] as $TA) {
        $events .= vueGenerateAdminHTML($TA);
    }
    foreach ($listClients as $client) {
        $clientOption .= '<option value="'.$client->IDCLIENT.'" data-conseiller="'.$client->IDEMPLOYE.'">'.$client->PRENOM.' '.$client->NOM.'</option>';
    }
    foreach ($listMotifs as $motif) {
        $motifsOption .= '<option value="'.$motif->IDMOTIF.'">'.$motif->INTITULE.'</option>';
    }
    $content = '
            <div class="addAppointementRDVWrapper">
                <div class="addAppointementWrapper">
                    <form action="index.php" method="post" class="addAppointementForm">
                        <h1>Ajouter un rendez-vous</h1>
                        <div class="field">
                            <label for="TAToggle">Tâche administrative :</label>
                            <input type="checkbox" name="TAToggle" id="TAToggle" onClick="toggleTA()">
                        </div>
                        <div class="field">
                            <label for="appointementsDateField">Date</label>
                            <input type="date" name="appointementsDateField" id="appointementsDateField" value="'.$date.'" required readonly>
                        </div>
                        <div class="field">
                            <label for="appointementsDateField">Horaire de début</label>
                            <input type="time" name="appointementsHoraireDebutField" id="appointementsHoraireDebutField" required>
                        </div>
                        <div class="field">
                            <label for="appointementsDateField">Horaire de fin</label>
                            <input type="time" name="appointementsHoraireFinField" id="appointementsHoraireFinField" required>
                        </div>
                        <select name="appointementsClientField" id="appointementsClientField" class="field appointement" required>
                            '.$clientOption.'
                        </select>
                        <select name="appointementsConseillerField" id="appointementsConseillerField" class="field hidden" readonly>
                            <option value="'.$_SESSION['idEmploye'].'">'.$_SESSION['name'].'</option>
                        </select>
                        <select name="appointementsMotifField" id="appointementsMotifField" class="field appointement">
                            '.$motifsOption.'
                        </select>
                        <div class="field admin hidden">
                            <input type="text" name="adminLibelleField" id="adminLibelleField" placeholder="Motif">
                        </div>
                        <button type="submit" name="addAppointementsBtn" class="cta field appointement">
                            Valider
                        </button>
                        <button type="submit" name="addAdminBtn" class="cta field admin hidden">
                            Valider
                        </button>
                    </form>
                </div>
                <div class="addAppointementWrapper">
                    <div class="events">
                        '.$events.'
                    </div>
                </div>
            </div>';
    require_once('gabaritGestion.php');
}































/*
POUBELLE


function vueDisplayGestionAccountOne($account) {
    $navbar = vueGenerateNavBar();
    $etat=$account->ACTIF==1 ? "checked": "";
    $content='<form action="index.php" method="post" class="gestionPersonnelAddForm">
                <div>
                    <h1>Modifier info type compte</h1>
                    <input type="text" name="nameAccount" value="'.$account->NOM.'" class="gestionPersonnelAddInput">
                    <input type="text" name="documentAccount" value="'.$account->DOCUMENT.'" class="gestionPersonnelAddInput">
                    <div class="gestionPersonnelAddInput">
                        <label for="activeAccount">Activé:</label>
                        <input type="checkbox" name="activeAccount" id="activeAccount" '.$etat.'>
                    </div>
                    <input type="hidden" name="idAccount" value="'.$account->IDTYPECOMPTE.' "class="gestionPersonnelAddInput">
                    <input type="hidden" name="idMotif" value="'.$account->IDMOTIF.'" class="gestionPersonnelAddInput">
                    <input type="submit" name="ModifAccountOneBtn" value="Valider modification" class="gestionPersonnelAddInput">
                </div>
            </form>';
    require_once('gabaritGestion.php');
}


function vueDisplayGestionContractOne($contract) {
    $navbar = vueGenerateNavBar();
    $etat=$contract->ACTIF==1 ? "checked": "";
    $content='<form action="index.php" method="post" class="gestionPersonnelAddForm">
                <div>
                    <h1>Modifier info type Contrat</h1>
                    <input type="text" name="nameAccount" value="'.$contract->NOM.'" class="gestionPersonnelAddInput">
                    <input type="text" name="documentAccount" value="'.$contract->DOCUMENT.'" class="gestionPersonnelAddInput">
                    <div class="gestionPersonnelAddInput">
                        <label for="activeAccount">Activé:</label>
                        <input type="checkbox" name="activeAccount" id="activeAccount" '.$etat.'>
                    </div>
                    <input type="hidden" name="idAccount" value="'.$contract->IDTYPECONTRAT.' "class="gestionPersonnelAddInput">
                    <input type="hidden" name="idMotif" value="'.$contract->IDMOTIF.'" class="gestionPersonnelAddInput">
                    <input type="submit" name="ModifAccountOneBtn" value="Valider modification" class="gestionPersonnelAddInput">
                </div>
            </form>';
    require_once('gabaritGestion.php');
}

function vueDisplayAgendaConseiller($appointment, $admin){
    $navbar = vueGenerateNavBar();
    $bla = json_encode($appointment);
    echo json_encode($admin);
    require_once('gabaritAgentHomePage.php');
}



*/


/*
ANCIENNE VERSION


function vueDisplayInfoClient($client, $listAccounts, $listContract, $listOperationsAccount, $listRDVClients){
    $navbar = vueGenerateNavBar();
    // pour faire le select pour le débit / crédit
    $optionSelect = "";
    $events = "";
    // pour faire la liste des comptes
    if ($_SESSION["type"] == 2) {
        $listA = '<div class="accountCell header">Suppression</div>';
        $typeClass = 'conseiller';
    }
    else{
        $listA = '';
        $typeClass = 'agent';
    }
    foreach ($listRDVClients as $appointements) {
        $events .= vueGenerateAppointementHTML($appointements);
    }
    $events .= ($events == "") ? '<p style="margin-left:1em;">Pas de rendez-vous.</p>' : "";
    foreach ($listAccounts as $account) {
        $optionSelect .= "<option value=\"".$account->idCompte."\">".$account->NOM.': '. $account->solde ."€</option>";
        $listA .= '<div class="accountCell content">'.$account->NOM.'</div>
            <div class="accountCell content">'.$account->solde.'€</div>
            <form action="index.php" method="post" class="accountCell content">
                <input type="number" name="overdraft" value="'.$account->decouvert.'" step="0.01">
                <input type="hidden" name="idAccount" value="'.$account->idCompte.'">
                <button type="submit" name="modifOverdraftBtn">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Modifier le découvert
                </button>
            </form>';
        if ($_SESSION["type"] == 2) {
            $listA.='
            <form action="index.php" method="post" class="accountCell content">
                <input type="hidden" name="idAccount" value="'.$account->idCompte.'">
                <button type="submit" name="deleteAccountBtn" class="red">
                    <i class="fa-solid fa-trash-can"></i>
                    Supprimer le compte
                </button>
            </form>';
        }
    }  
    // pour faire la liste des contrats
    $listC="";
    $listC .= ($_SESSION["type"] == 2) ? '<div class="accountCell header">Supression</div>' : '';
    foreach ($listContract as $contract) {
        $listC .= '<div class="contractCell content">'.$contract->NOM.'</div>
            <div class="contractCell content">'.$contract->tarifmensuel.'€</div>';
        if ($_SESSION["type"] == 2) {
            $listC.='<div class="contractCell content">
                        <form action="index.php" method="post">
                            <input type="hidden" name="idContract" value="'.$contract->idContrat.'">
                            <input type="submit" value="Supprimer le contrat" name="deleteContractBtn">
                        </form>
                    </div>';
        }
        $listC .= '</div>';
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
    }
    if ($_SESSION["type"] == 2) {
        $createAccount='<div>
                            <form action="index.php" method="post">
                                <input type="hidden" name="idClient" value="'.$idClient.'">
                                <div class="btnWrapper">
                                    <button type="submit" name="addAccountBtn" class="btn">
                                        <i class="fa-solid fa-plus"></i> Ajouter un compte
                                    </button>
                                </div>
                            </form>
                        </div>';
        $createContract = '<div>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="idClient" value="'.$idClient.'">
                                    <div class="btnWrapper">
                                        <button type="submit" name="addContractBtn" class="btn">
                                            <i class="fa-solid fa-plus"></i> Ajouter un contrat
                                        </button>
                                    </div>
                                </form>
                            </div>';
    }
    else {
        $createAccount="";
        $createContract="";
    }

    require_once('gabaritInfoClient.php');
}


*/