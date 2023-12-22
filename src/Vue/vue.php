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
function vueDisplayHomeConseiller($appointments, $TAS, $dateOfWeek, $username, $fullName){
    $navbar = vueGenerateNavBar();
    $weekEvents = array("", "", "", "", "", "", "");
    // $weekEvents represente pour chaque entrée de 0 à 6, en chaine de caracteres, les eventHTML du jour correspondant
    foreach ($appointments as $appointment) {
        $appointmentDate = date_create_from_format("Y-m-d H:i:s", $appointment->HORAIREDEBUT);
        $weekNumber = date_format($appointmentDate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAppointementHTML($appointment);
    }
    foreach ($TAS as $TA) {
        $TADate = date_create_from_format("Y-m-d H:i:s", $TA->HORAIREDEBUT);
        $weekNumber = date_format($TADate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAdminHTML($TA);
    }
    require_once('gabaritConseillerHomePage.php');
}

function vueDisplayHomeAgent($appointments, $TAS, $dateOfWeek, $username) {
    $navbar = vueGenerateNavBar();
    $weekEvents = array("", "", "", "", "", "", "");
    // $weekEvents represente pour chaque entrée de 0 à 6, en chaine de caracteres, les eventHTML du jour correspondant
    foreach ($appointments as $appointment) {
        $appointmentDate = date_create_from_format("Y-m-d H:i:s", $appointment->HORAIREDEBUT);
        $weekNumber = date_format($appointmentDate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAppointementHTML($appointment);
    }
    foreach ($TAS as $TA) {
        $TADate = date_create_from_format("Y-m-d H:i:s", $TA->HORAIREDEBUT);
        $weekNumber = date_format($TADate, "N");
        $weekEvents[$weekNumber -1] .= vueGenerateAdminHTML($TA);
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

function vueGenerateAdminHTML($TA) {
    $identiteEmploye = $TA->PRENOM.' '.$TA->NOM;
    $heureDebut = (substr($TA->HORAIREDEBUT, 11, 5));
    $heureFin = (substr($TA->HORAIREFIN, 11, 5));
    return '<div class="event" data-conseiller="'. $identiteEmploye .'" data-color="'. $TA->COLOR .'">
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
                '. $identiteEmploye .'
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
 * Fonction qui affiche la page du client
 * Ne retourne rien
 * @param object $client c'est les données du client
 */
function vueDisplayInfoClient($client, $listAccounts, $listContract, $listOperationsAccount, $listRDVClients){
    $navbar = vueGenerateNavBar();
    // pour faire le select pour le débit / crédit
    $optionSelect = "";
    $events = "";
    // pour faire la liste des comptes
    if ($_SESSION["type"] == 2) {
        $listA = '<div class="accountCell header">Supression</div>';
        $typeClass = 'conseiller';
    }
    else{
        $listA = '';
        $typeClass = 'agent';
    }
    debug($listRDVClients);
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
        $selectOptions .= '<option value="'.$color.'"'.$selected.' class="'.$color.'-text'.'>'.$color.'</option>';
    }
    $etat1=$employee->IDCATEGORIE==1 ? "selected": "";
    $etat2=$employee->IDCATEGORIE==2 ? "selected": "";
    $etat3=$employee->IDCATEGORIE==3 ? "selected": "";
    $row='<form action="index.php" method="post" class="employeTableContentWrapper" onformchange="displayValidBtn">
            <input  type="number" class="employeCell content" name="idEmployee" value="'.$employee->IDEMPLOYE.'" readonly="true">
            <select name="idCategorie" class="employeCell content">
                <option value="1" '.$etat1.' >Directeur</option>
                <option value="2" '.$etat2.' >Conseiller</option>
                <option value="3" '.$etat3.' >Agent d\'accueil</option>
            </select>
            <input type="text" name="nameEmployee" class="employeCell content" value="'.$employee->NOM.'">
            <input type="text" name="firstNameEmployee" class="employeCell content" value="'.$employee->PRENOM.'">
            <input type="text" name="loginEmployee" class="employeCell content" value="'.$employee->LOGIN.'">
            <input type="password" name="passwordEmployee" class="employeCell content" value="">
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
    $selectOptions = "";
    foreach ($colors as $color) {
        $selectOptions .= '<option value="'.$color.'" class="'.$color.'-text'.'>'.$color.'</option>';
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
                        <input type="password" name="passwordEmployee" placeholder="Mot de passe" class="gestionPersonnelAddInput" required>
                        <select name="colorEmployee" class="gestionPersonnelAddInput" required>
                            '.$selectOptions.'
                        </select>
                        <button type="submit" name="AddPersonnelSubmitBtn" class="gestionPersonnelAddInput" required>
                            <i class="fa-solid fa-check"></i> Valider ajout
                        </button>
                    </div>
                </form>';
    require_once('gabaritGestion.php');
}

function vueDisplayGestionServicesAll($listTypeAccount, $listTypeContract) {
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


function vueDisplayGestionServicesAdd(){
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

/**
 * Fonction qui affiche la page d'erreur
 * Ne retourne rien
 * @param string $error
 */
function vueDisplayError ($error) {
    $content = "<p>".$error."</p><p><a href=\"index.php\"/> Revenir à l'acceuil </a></p>";
    require_once('gabaritErreur.php');
}


/** NON UTILISE */ 
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
    $selectOptions = "";
    foreach ($colors as $color) {
        $selected = ($identity->COLOR == $color) ? "selected" : "";
        $selectOptions .= '<option value="'.$color.'"'.$selected.' class="'.$color.'-text'.'">'.$color.'</option>';
    }
    $content='<div class="modInfoWrapper">
                <form action="index.php" method="post">
                    <h1>Modifier info personnel</h1>
                    <label for="loginEmployee"  class="visually-hidden">Login :</label>
                    <input type="text" name="loginEmployee" id="loginEmployee" class="modInfoField" value="'.$identity->LOGIN.'" placeholder="Login" required>
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
                <input type="number" name="monthCost" placeholder="Cout Mensuel" step="0.01" class="addContractField" required>
                <input type="hidden" name="idClient" value="'.$idClient.'">
                <button type="submit" name="createContractBtn" class="addContractField cta">
                    Valider la création
                </button>
                </form></div>';
    require_once('gabaritGestion.php');
}


function vueDisplayAddAccount($idClient, $listTypeAccount, $listeClient){
    $navbar = vueGenerateNavBar();
    debug($listTypeAccount);
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
                    <input type="number" name="monthCost" placeholder="Découvert" step="0.01" class="addContractField">
                    <input type="hidden" name="idClient" value="'.$idClient.'">
                    <button type="submit" name="createAccountBtn" class="addContractField cta">
                        Valider la création
                    </button>
                </form></div>'; 
    require_once('gabaritGestion.php');
}


function vueDisplayAddAppointement($listConseillers, $listClients, $listMotifs, $date, $rdvArray, $clientActuel = "") {
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
            </div>
                <script>
                    function changeConseiller(select) {
                        let option =select.options[select.selectedIndex];
                        console.log(option);
                        let value = option.dataset.conseiller;
                        console.log(value);
                        document.getElementById("appointementsConseillerField").value = value;
                    }
                </script>';
    require_once('gabaritGestion.php');

}

function vueDisplayAddAppointementConseiller($listClients, $listMotifs, $date, $rdvArray) {
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
            </div>
            <script>
                let isAdmin = false;
                function toggleTA() {
                    console.log(isAdmin);
                    isAdmin = ! isAdmin ;
                    console.log(isAdmin);
                    if (isAdmin) {
                        document.querySelectorAll(".admin").forEach(function (item) {
                            item.classList.remove("hidden")
                        });
                        document.querySelectorAll(".appointement").forEach(function (item) {
                            item.classList.add("hidden")
                        });
                    } else {
                        document.querySelectorAll(".appointement").forEach(function (item) {
                            item.classList.remove("hidden")
                        });
                        document.querySelectorAll(".admin").forEach(function (item) {
                            item.classList.add("hidden")
                        });
                    }
                }
            </script>';
    require_once('gabaritGestion.php');
}