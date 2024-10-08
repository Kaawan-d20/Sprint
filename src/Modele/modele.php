<?php
require_once('modele/token.php');
require_once('token.php');

/**
 * Renvoie le password correspondant au login passé en paramètre
 * Rien si ce login n'est pas présent dans la base de données.
 * @param string $login Le login de l'employé.
 * @return string Le password haché et salé de l'employé.
 */
function modGetPassword($login) {
    $connection = Connection::getInstance()->getConnection();
    $query = "SELECT PASSWORD FROM employe WHERE login=:login";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(":login", $login, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->PASSWORD;
}

/**
 * Renvoie toutes les infos de l'employé dont le login sont en paramètres
 * Rien si celui-ci n'est pas présent dans la base de données.
 * @param string $login Le login de l'employé
 * @return object Les infos de l'employé (IDEMPLOYE, IDCATEGORIE, NOM, PRENOM)
 */
function modGetEmployeFromLogin($login) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDEMPLOYE, IDCATEGORIE, NOM, PRENOM FROM employe WHERE login=:login';
    $prepared = $connection ->prepare($query);
    $prepared -> bindParam(':login', $login, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie toutes les infos de tous les motifs
 * @return array Toutes les infos de tous les motifs (IDMOTIF, INTITULE, DOCUMENT) (tableau d'objets)
 */
function modGetAllMotif() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM motif';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- ACCOUNT ---------------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie tous les comptes du client dont l'id est en paramètre
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idClient L'id du client
 * @return array Les comptes du client (IDCOMPTE, NOM, SOLDE, DECOUVERT, DATECREATION, IDTYPECOMPTE) (tableau d'objets)
 */
function modGetAccounts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT compte.idCompte, typecompte.NOM, solde, decouvert, datecreation, idtypecompte
                FROM compte
                LEFT JOIN possedeCompte ON compte.idCompte=possedeCompte.idCompte
                NATURAL JOIN typecompte
                WHERE idClient=:idClient';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idClient', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie des infos du compte dont l'id est en paramètre.
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idAccount L'id du compte
 * @return object Les infos du compte (SOLED, DECOUVERT, IDCLIENT)
 */
function modGetInfoAccount($idAccount){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT solde, decouvert, idClient
                FROM compte
                LEFT JOIN possedeCompte ON compte.idCompte=possedeCompte.idCompte
                WHERE compte.idCompte=:idAccount';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Crée un compte et l'ajout au client dont l'id est en paramètre
 * @param int $idClient L'id du client
 * @param string $overdraft Le découvert du compte
 * @param int $idTypeAccount L'id du type de compte
 */
function modAddAccountToClientOne($idClient, $overdraft, $idTypeAccount){
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO compte(idTypeCompte, solde, decouvert, dateCreation) VALUES (:idTypeCompte, "0.00", :overdraft, NOW());
              INSERT INTO possedeCompte(idClient, idCompte) VALUES (:idClient, (SELECT LAST_INSERT_ID()))';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeCompte', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':overdraft', $overdraft, PDO::PARAM_STR);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Crée un compte et l'ajout aux deux client dont l'id est en paramètre
 * @param int $idClient L'id du client
 * @param int $idClient2 L'id du deuxième client
 * @param string $overdraft Le découvert du compte
 * @param int $idTypeAccount L'id du type de compte
 */
function modAddAccountToClientTwo($idClient, $idClient2, $overdraft, $idTypeAccount){
    $connection = Connection::getInstance()->getConnection();

    // Première requête création du compte
    $query = 'INSERT INTO compte(idTypeCompte, solde, decouvert, dateCreation) VALUES (:idTypeCompte, "0.00", :overdraft, NOW())';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeCompte', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':overdraft', $overdraft, PDO::PARAM_STR);
    $prepared -> execute();

    // Récupérer l'ID du contrat inséré
    $idCompte = $connection -> lastInsertId();

    // Deuxième requête ajout du compte au premier client
    $query = 'INSERT INTO possedeCompte(idClient, idCompte) VALUES (:idClient, :idCompte)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $prepared -> execute();

    // Troisième requête ajout du compte au deuxième client
    $query = 'INSERT INTO possedeCompte(idClient, idCompte) VALUES (:idClient2, :idCompte)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient2', $idClient2, PDO::PARAM_INT);
    $prepared -> bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $prepared -> execute();

    $prepared -> closeCursor();
}

/**
 * Supprime le compte dont l'id est en paramètre
 * Supprime aussi les opérations et les liens avec les clients
 * @param int $idAccount L'id du compte
 */
function modDeleteAccount($idAccount){
    $connection = Connection::getInstance()->getConnection();
    $query = 'DELETE FROM operation WHERE idCompte=:idAccount;
                DELETE FROM possedeCompte WHERE idCompte=:idAccount;
                DELETE FROM compte WHERE idCompte=:idAccount';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Renvoie les infos des clients dont id est en paramètre est le conseiller
 * @param int $idEmployee l'id du conseiller
 * @return array les infos des clients dont id est en paramètre est le conseiller (IDCLIENT, IDEMPLOYE, NOM, PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE) (tableau d'objets)
 */
function modGetAllClientsByCounselors($idEmployee){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM client WHERE idEmploye=:idEmployee';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmployee', $idEmployee, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Débite le compte dont l'id est en paramètre de la somme (positive) en paramètre
 * Crée une opération affiliée au compte
 * @param int $idAccount L'id du compte à débiter
 * @param string $sum La somme à débiter (positive)
 * @return void
 */
function modDebit($idAccount, $sum) {
    $connection = Connection::getInstance()->getConnection();
    $query = "UPDATE Compte SET solde=solde-:sum WHERE idCompte=:idAccount;
                INSERT INTO `operation`(`IDCOMPTE`, `SOURCE`, `LIBELLE`, `DATEOPERATION`, `MONTANT`, `ISCREDIT`) VALUES (:idAccount, 'Banque', 'Debit', NOW(), :sum, 0)";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Crédite le compte dont l'id est en paramètre de la somme en paramètre 
 * @param int $idAccount L'id du compte à créditer
 * @param string $sum La somme à créditer
 * @return void
 */
function modCredit($idAccount, $sum) {
    $connection = Connection::getInstance()->getConnection();
    $query = "UPDATE Compte SET solde=solde+:sum WHERE idCompte=:idAccount;
                INSERT INTO `operation`(`IDCOMPTE`, `SOURCE`, `LIBELLE`, `DATEOPERATION`, `MONTANT`, `ISCREDIT`) VALUES (:idAccount, 'Banque', 'Crédit', NOW(), :sum, 1)";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Modifie le découvert du compte dont l'id est en paramètre
 * @param int $idAccount L'id du compte
 * @param string $overdraft Le montant du découvert
 * @return void
 */
function modModifOverdraft($idAccount, $overdraft){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE compte SET decouvert=:overdraft WHERE idCompte=:idAccount';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':overdraft', $overdraft, PDO::PARAM_STR);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Renvoie la liste des operations du compte dont l'id est en paramètre
 * @param int $idAccount L'id du compte
 * @return array La liste des operations du compte dont l'id est en paramètre (IDOPERATION, IDCOMPTE, SOURCE, LIBELLE, DATEOPERATION, MONTANT, ISCREDIT) (tableau d'objets)
 */
function modGetOperations($idAccount) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM operation WHERE idCompte=:idCompte';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idCompte', $idAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- TYPE ACCOUNT ----------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie toutes les infos de tous les types de compte
 * @return array Infos de tous les types de compte (IDMOTIF, IDTYPECOMPTE, NOM, ACTIF, INTITULE, DOCUMENT) (tableau d'objets)
 */
function modGetAllAccountTypes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typeCompte NATURAL JOIN motif';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie toutes les informations de tous les types de compte qui sont actifs
 * @return array Infos de tous les types de compte qui sont actifs (IDMOTIF, IDTYPECOMPTE, NOM, ACTIF, INTITULE, DOCUMENT) (tableau d'objets)
 */
function modGetAllAccountTypesEnable() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typeCompte NATURAL JOIN motif WHERE actif=1';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Fonction qui permet de mettre à jour les informations d'un type de compte
 * @param int $idAccount L'id du type de compte
 * @param string $name Le nom du type de compte
 * @param int $active 1 si le type de compte est actif, 0 sinon
 * @param string $document Le document du type de compte
 * @param int $idMotif L'id du motif du type de compte
 * @return void
 */
function modModifTypeAccount($idAccount, $name, $active, $document, $idMotif){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE typecompte SET nom=:name, actif=:active WHERE idtypecompte=:idAccount;
                UPDATE motif SET document=:document WHERE idmotif=:idMotif';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> bindParam(':idMotif', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet d'ajouter un type de compte
 * @param string $name Le nom du type de compte
 * @param int $active 1 si le type de compte est actif, 0 sinon
 * @param string $document Les documents du type de compte
 * @return void
 */
function modAddTypeAccount($name, $active, $document){
    $nameMotif = 'Gestion du compte '.$name;
    $connection = Connection::getInstance()->getConnection();
    $connection -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = 'INSERT INTO `motif`(`INTITULE`, `DOCUMENT`) VALUES (:nameMotif, :document);
    INSERT INTO `typecompte`(`IDMOTIF`, `NOM`, `ACTIF`) VALUES ((SELECT LAST_INSERT_ID()), :name, :active)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':nameMotif', $nameMotif, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet de supprimer un type de compte
 * Supprime aussi les opérations et les liens avec les clients, les RDV, les comptes et les motifs affiliés
 * @param int $idTypeAccount L'id du type de compte
 * @return void
 */
function modDeleteTypeAccount($idTypeAccount){
    $connection = Connection::getInstance()->getConnection();
    $connection -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = 'DELETE FROM `possedecompte` WHERE IDCOMPTE IN (SELECT IDCOMPTE FROM compte WHERE IDTYPECOMPTE=:idtypecompte);
                DELETE FROM `operation` WHERE IDCOMPTE IN (SELECT IDCOMPTE FROM compte WHERE IDTYPECOMPTE=:idtypecompte);
                SET @IDM = (SELECT IDMOTIF FROM typecompte WHERE IDTYPECOMPTE=:idtypecompte);
                DELETE FROM `RDV` WHERE IDMOTIF = @IDM;
                DELETE FROM `compte` WHERE IDTYPECOMPTE=:idtypecompte;
                DELETE FROM `typecompte` WHERE IDTYPECOMPTE=:idtypecompte;
                DELETE FROM `motif` WHERE IDMOTIF = @IDM;';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idtypecompte', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- CONTRACT ------------------------------------------------------------ #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie tous les contrats du client dont l'id est en paramètre
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idClient L'id du client
 * @return array Les contrats du client (IDCONTRAT, IDTYPECONTRAT, TARIFMENSUEL, DATEOUVERTURE, NOM) (tableau d'objets)
 */
function modGetContracts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT contrat.idContrat, idTypeContrat, tarifmensuel, dateouverture, typecontrat.NOM FROM contrat LEFT JOIN possedeContrat ON contrat.idContrat=possedeContrat.idContrat NATURAL JOIN typecontrat WHERE idClient=:idClient';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idClient', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Crée et ajoute un contrat au client dont l'id est en paramètre
 * @param int $idClient L'id du client
 * @param string $monthCost Le coût mensuel du contrat
 * @param int $idTypeContract L'id du type de contrat
 * @return void
 */
function modAddContractToClientOne($idClient, $monthCost, $idTypeContract){
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO contrat(idTypeContrat, TarifMensuel, dateOuverture) VALUES (:idTypeContrat, :monthCost, NOW());
              INSERT INTO possedeContrat(idClient, idContrat) VALUES (:idClient, (SELECT LAST_INSERT_ID()))';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeContrat', $idTypeContract, PDO::PARAM_INT);
    $prepared -> bindParam(':monthCost', $monthCost, PDO::PARAM_STR);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Crée et ajoute un contrat aux deux clients dont l'id est en paramètre
 * @param int $idClient L'id du client
 * @param int $idClient2 L'id du deuxième client
 * @param string $monthCost Le coût mensuel du contrat
 * @param int $idTypeContract L'id du type de contrat
 * @return void
 */
function modAddContractToClientTwo($idClient, $idClient2, $monthCost, $idTypeContract){
    $connection = Connection::getInstance()->getConnection();

    // Première requête
    $query = 'INSERT INTO contrat(idTypeContrat, TarifMensuel, dateOuverture) VALUES (:idTypeContrat, :monthCost, NOW())';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeContrat', $idTypeContract, PDO::PARAM_INT);
    $prepared -> bindParam(':monthCost', $monthCost, PDO::PARAM_STR);
    $prepared -> execute();

    // Récupérer l'ID du contrat inséré
    $idContrat = $connection -> lastInsertId();

    // Deuxième requête
    $query = 'INSERT INTO possedeContrat(idClient, idContrat) VALUES (:idClient, :idContrat)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> bindParam(':idContrat', $idContrat, PDO::PARAM_INT);
    $prepared -> execute();

    // Troisième requête
    $query = 'INSERT INTO possedeContrat(idClient, idContrat) VALUES (:idClient2, :idContrat)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient2', $idClient2, PDO::PARAM_INT);
    $prepared -> bindParam(':idContrat', $idContrat, PDO::PARAM_INT);
    $prepared -> execute();

    $prepared -> closeCursor();
}

/**
 * Supprime le contrat dont l'id est en paramètre
 * Supprime aussi les liens avec les clients
 * @param int $idContract L'id du contrat
 * @return void
 */
function modDeleteContract($idContract){
    $connection = Connection::getInstance()->getConnection();
    $query = 'DELETE FROM possedeContrat WHERE idContrat=:idContract;
                DELETE FROM contrat WHERE idContrat=:idContract';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idContract', $idContract, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- TYPE CONTRACT ----------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie toutes les informations de tous les types de contrat
 * @return array Toutes les infos de tous les types de contrat (IDTYPECONTRAT, NOM, ACTIF, DOCUMENT, IDMOTIF) (tableau d'objets)
 */
function modGetAllContractTypes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typeContrat NATURAL JOIN motif';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie toutes les informations de tous les types de contrat qui sont actifs
 * @return array Toutes les infos de tous les types de contrat qui sont actifs (IDTYPECONTRAT, NOM, ACTIF, DOCUMENT, IDMOTIF) (tableau d'objets)
 */
function modGetAllContractTypesEnable() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typeContrat NATURAL JOIN motif WHERE actif=1';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Fonction qui permet de mettre à jour les informations d'un type de contrat
 * @param int $idContractType L'id du type de contrat
 * @param string $name Le nom du type de contrat
 * @param int $active 1 si le type de contrat est actif, 0 sinon
 * @param string $document Le document du type de contrat
 * @param int $idMotif L'id du motif du type de contrat
 * @return void
 */
function modModifTypeContract($idContractType, $name, $active, $document, $idMotif){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE typecontrat SET nom=:name, actif=:active WHERE idtypecontrat=:idContractType;
                UPDATE motif SET document=:document WHERE idmotif=:idMotif';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idContractType', $idContractType, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> bindParam(':idMotif', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet d'ajouter un type de contrat
 * @param string $name Le nom du type de contrat
 * @param int $active 1 si le type de contrat est actif, 0 sinon
 * @param string $document Le document du type de contrat
 * @return void
 */
function modAddTypeContract($name, $active, $document){
    $nameMotif = 'Gestion du contrat '.$name;
    $connection = Connection::getInstance()->getConnection();
    $connection -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = "INSERT INTO `motif`(`INTITULE`, `DOCUMENT`) VALUES (:nameMotif, :document);
    INSERT INTO `typecontrat`(`IDMOTIF`, `NOM`, `ACTIF`) VALUES ((SELECT LAST_INSERT_ID()), :name, :active)";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':nameMotif', $nameMotif, PDO::PARAM_STR);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet de supprimer un type de contrat
 * Supprime aussi les RDV, les contrats, les motifs affiliés et les liens avec les clients
 * @param int $idTypeContract L'id du type de contrat
 * @return void
 */
function modDeleteTypeContract($idTypeContract){
    $connection = Connection::getInstance()->getConnection();
    $connection -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = 'DELETE FROM `possedecontrat` WHERE IDCONTRAT IN (SELECT IDCONTRAT FROM contrat WHERE IDTYPECONTRAT=:idtypecontrat);
                SET @IDM = (SELECT IDMOTIF FROM typecontrat WHERE IDTYPECONTRAT=:idtypecontrat);
                DELETE FROM `RDV` WHERE IDMOTIF = @IDM;
                DELETE FROM `contrat` WHERE IDTYPECONTRAT=:idtypecontrat;
                DELETE FROM `typecontrat` WHERE IDTYPECONTRAT=:idtypecontrat;
                DELETE FROM `motif` WHERE IDMOTIF = @IDM;';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idtypecontrat', $idTypeContract, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- TACHE ADMIN ------------------------------------------------------------ #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie tous les TA entre la première et la deuxième date mises en paramètres
 * Rien si il n'y en a pas dans la base de données
 * @param int $id L'id du conseiller
 * @param string $dateDebut Date de début
 * @param string $dateFin Date de fin
 * @return array tous les TA entre la première et la deuxième date mises en paramètres (HORAIREDEBUT, HORAIREFIN) (tableau d'objets)
 */
function modGetTABetweenCounselor($idConseiller, $dateDebut, $dateFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT HORAIREDEBUT, HORAIREFIN FROM tacheadmin WHERE horairedebut>=:dateDebut AND horairedebut<=:dateFin AND idEmploye=:idConseiller';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $prepared -> bindParam(':idConseiller', $idConseiller, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie toutes les ta entre la première et la deuxième date mises en paramètres
 * Rien si il n'y en a pas dans la base de données
 * @param string $dateDebut Date de début
 * @param string $dateFin Date de fin
 * @return array tous les ta entre la première et la deuxième date mises en paramètres (IDTA, HORAIREDEBUT, HORAIREFIN, LIBELLE, IDENTITEEMPLOYE, COLOR) (tableau d'objets)
 */
function modGetAllTABetween($dateDebut, $dateFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDTA, HORAIREDEBUT, HORAIREFIN, LIBELLE, CONCAT(employe.PRENOM, " ", employe.NOM) AS IDENTITEEMPLOYE, COLOR  FROM tacheAdmin NATURAL JOIN employe WHERE horairedebut>:dateDebut AND horairedebut<:dateFin';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Crée une TA avec l'id d'employé, l'horaire et le libelle en paramètre
 * @param int $idEmploye L'id de l'employé
 * @param string $horaireDebut L'horaire de début
 * @param string $horaireFin L'horaire de fin
 * @param string $libelle Le libelle
 * @return void
 */
function modCreateTA($idEmploye, $horaireDebut, $horaireFin, $libelle) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO tacheAdmin(idEmploye, horaireDebut, horaireFin, libelle) VALUES (:idEmploye, :horaireDebut, :horaireFin, :libelle)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
    $prepared -> bindParam(':horaireDebut', $horaireDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':horaireFin', $horaireFin, PDO::PARAM_STR);
    $prepared -> bindParam(':libelle', $libelle, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Supprime la TA dont l'id est en paramètre
 * @param int $idTA L'id de la TA
 * @return void
 */
function modDeleteTA($idTA){
    $connection = Connection::getInstance()->getConnection();
    $query = 'DELETE FROM tacheAdmin WHERE idTA=:idTA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTA', $idTA, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- APPOINTMENT ------------------------------------------------------------ #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie tous les rdv entre la première et la deuxième date mises en paramètres
 * Rien si il n'y en a pas dans la base de données
 * @param string $dateDebut Date de début
 * @param string $dateFin Date de fin
 * @return array tous les rdv entre la première et la deuxième date mises en paramètres (IDEMPLOYE, IDRDV, IDENTITEEMPLOYE, COLOR, IDCLIENT, IDENTITECLIENT, HORAIREDEBUT, HORAIREFIN, INTITULE, DOCUMENT) (tableau d'objets)
 */
function modGetAllAppointmentsBetween($dateDebut, $dateFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT rdv.IDEMPLOYE, rdv.IDRDV,
    CONCAT(employe.PRENOM," ", employe.NOM) AS IDENTITEEMPLOYE,
    employe.COLOR,
    rdv.IDCLIENT,
    CONCAT(client.CIVILITEE, " ", client.PRENOM, " ", client.NOM) AS identiteClient,
    rdv.HORAIREDEBUT,
    rdv.HORAIREFIN,
    motif.INTITULE,
    motif.DOCUMENT
    FROM rdv
    JOIN employe ON rdv.IDEMPLOYE=employe.IDEMPLOYE
    JOIN motif ON rdv.IDMOTIF=motif.IDMOTIF
    JOIN client ON rdv.IDCLIENT=client.IDCLIENT WHERE horairedebut>:dateDebut AND horairedebut<=:dateFin';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie tous les rdv entre la première et la deuxième date mises en paramètres du conseiller dont l'id est en paramètre
 * Rien si il n'y en a pas dans la base de données
 * @param int $idConseiller L'id du conseiller
 * @param string $dateDebut Date de début
 * @param string $dateFin Date de fin
 * @return array Tous les rdv entre la première et la deuxième date mises en paramètres du conseiller dont l'id est en paramètre (IDEMPLOYE, IDRDV, IDENTITEEMPLOYE, COLOR, IDCLIENT, IDENTITECLIENT, HORAIREDEBUT, HORAIREFIN, INTITULE, DOCUMENT) (tableau d'objets)
 */
function modGetAppointmentsBetweenCounselor($idConseiller, $dateDebut, $dateFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT rdv.IDEMPLOYE, rdv.IDRDV,
    CONCAT(employe.PRENOM, " ", employe.NOM) AS IDENTITEEMPLOYE,
    employe.COLOR, rdv.IDCLIENT,
    CONCAT(client.CIVILITEE, " ", client.PRENOM, " ", client.NOM) AS identiteClient,
    rdv.HORAIREDEBUT, rdv.HORAIREFIN, motif.INTITULE, motif.DOCUMENT
    FROM rdv
    JOIN employe ON rdv.IDEMPLOYE=employe.IDEMPLOYE
    JOIN motif ON rdv.IDMOTIF=motif.IDMOTIF
    JOIN client ON rdv.IDCLIENT=client.IDCLIENT
    WHERE horairedebut>:dateDebut AND horairedebut<=:dateFin AND rdv.idEmploye=:idConseiller';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $prepared -> bindParam(':idConseiller', $idConseiller, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


/**
 * Renvoie tous les rdv du client dont l'id est en paramètre
 * @param int $id L'id du client
 * @return array Tous les rdv du client dont l'id est en paramètre (IDEMPLOYE, IDRDV, IDENTITEEMPLOYE, COLOR, IDCLIENT, IDENTITECLIENT, HORAIREDEBUT, HORAIREFIN, INTITULE, DOCUMENT) (tableau d'objets)
 */
function modGetAppointmentsClient($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT rdv.IDEMPLOYE, rdv.IDRDV,
    CONCAT(employe.PRENOM, " ", employe.NOM) AS IDENTITEEMPLOYE,
    employe.COLOR, rdv.IDCLIENT,
    CONCAT(client.CIVILITEE, " ", client.PRENOM, " ", client.NOM) AS identiteClient,
    rdv.HORAIREDEBUT, rdv.HORAIREFIN, motif.INTITULE, motif.DOCUMENT
    FROM rdv
    JOIN employe ON rdv.IDEMPLOYE=employe.IDEMPLOYE
    JOIN motif ON rdv.IDMOTIF=motif.IDMOTIF
    JOIN client ON rdv.IDCLIENT=client.IDCLIENT
    WHERE rdv.idClient=:id AND rdv.HORAIREDEBUT>=NOW();';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Crée un nouveau rdv
 * @param int $idMotif L'id du motif
 * @param int $idClient L'id du client
 * @param int $idEmploye L'id de l’employé
 * @param string $horaireDebut L'horaire de début
 * @param string $horaireFin L'horaire de fin 
 */
function modAddAppointment($idMotif, $idClient, $idEmploye, $horaireDebut, $horaireFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO rdv(idMotif, idClient, idEmploye, horaireDebut, horaireFin) VALUES (:idMotif, :idClient, :idEmploye, :horaireDebut, :horaireFin)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idMotif', $idMotif, PDO::PARAM_INT);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
    $prepared -> bindParam(':horaireDebut', $horaireDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':horaireFin', $horaireFin, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Supprime le rdv dont l'id est en paramètre
 * @param int $idAppointment L'id du rdv
 * @return void
 */
function modDeleteAppointment($idAppointment){
    $connection = Connection::getInstance()->getConnection();
    $query = 'DELETE FROM rdv WHERE idRDV=:idAppointment';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAppointment', $idAppointment, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- EMPLOYE ---------------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie toutes les infos de l'employé dont l'id est en paramètre
 * Rien si il n'est pas présent dans la base de données
 * @param string $idEmploye L'id de l'employé
 * @return object Les infos de l'employé (IDEMPLOYE, IDCATEGORIE, NOM, PRENOM, LOGIN, COLOR)
 */
function modGetEmployeFromId($idEmploye) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDEMPLOYE, IDCATEGORIE, NOM, PRENOM, LOGIN, COLOR FROM employe WHERE idEmploye=:idEmploye';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie toutes les infos de tous les employés
 * @return array Des infos de tous les employés (IDEMPLOYE, IDCATEGORIE, NOM, PRENOM, LOGIN, COLOR) (tableau d'objets)
 */
function modGetAllEmployes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDEMPLOYE, IDCATEGORIE, NOM, PRENOM, LOGIN, COLOR FROM employe';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie tous les conseillers
 * @return array Tous les conseillers (IDEMPLOYE, IDENTITEEMPLOYE) (tableau d'objets)
 */
function modGetAllCounselors(){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idEmploye, CONCAT(employe.PRENOM, " ", employe.NOM) AS identiteEmploye FROM employe WHERE idCategorie=2';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie tous les directeurs
 * @return array Tous les directeurs (IDEMPLOYE, IDENTITEEMPLOYE) (tableau d'objets)
 */
function modGetAllDirectors(){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idEmploye, CONCAT(employe.PRENOM, " ", employe.NOM) AS identiteEmploye FROM employe WHERE idCategorie=1';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Fonction qui permet de modifier les paramètres d'un employé
 * @param int $idEmploye L'id de l'employé
 * @param string $login Le login de l'employé
 * @param string $password Le password (salé) de l'employé
 * @param string $color La couleur de l'employé
 * @return void
 */
function modModifEmployeSetting($idEmploye, $login, $password, $color){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE employe SET login=:login, password=:password, color=:color WHERE idEmploye=:idEmploye';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
    $prepared -> bindParam(':login', $login, PDO::PARAM_STR);
    $prepared -> bindParam(':password', $password, PDO::PARAM_STR);
    $prepared -> bindParam(':color', $color, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet d'ajouter un employé
 * @param int $idCategorie L'id de la catégorie de l'employé
 * @param string $name Le nom de l'employé
 * @param string $firstName Le prénom de l'employé
 * @param string $login Le login de l'employé
 * @param string $password Le password (salé) de l'employé
 * @param string $color La couleur de l'employé
 * @return void
 */
function modAddEmploye($idCategorie, $name, $firstName, $login, $password, $color){
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO employe(idCategorie, nom, prenom, login, password, color) VALUES (:idCategorie, :name, :firstName, :login, :password, :color)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> bindParam(':login', $login, PDO::PARAM_STR);
    $prepared -> bindParam(':password', $password, PDO::PARAM_STR);
    $prepared -> bindParam(':color', $color, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Modifie les nom, prénom, login, password et id de catégorie de l'employé dont l'id est en premier paramètre
 * @param int $idEmploye L'id de l'employé
 * @param string $name Le nom de l'employé
 * @param string $firstName Le prénom de l'employé
 * @param string $login Le login de l'employé
 * @param string $password Le password (salé) de l'employé
 * @param int $idCategorie L'id de la catégorie de l'employé
 * @param string $color La couleur de l'employé
 * @return void
 */
function modModifEmploye($idEmploye, $name, $firstName, $login, $password, $idCategorie, $color) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE employe set nom=:name, prenom=:firstName, login=:login, password=:password, idCategorie=:idCategorie, color=:color WHERE idEmploye=:idEmploye';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> bindParam(':login', $login, PDO::PARAM_STR);
    $prepared -> bindParam(':password', $password, PDO::PARAM_STR);
    $prepared -> bindParam(':idCategorie', $idCategorie, PDO::PARAM_INT);
    $prepared -> bindParam(':color', $color, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet de supprimer un employé
 * @param int $idEmployee L'id de l'employé
 * @return void
 */
function modDeleteEmploye($idEmployee){
    $connection = Connection::getInstance()->getConnection();
    $connection -> setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = 'DELETE FROM RDV WHERE IDEMPLOYE=:idEmployee;
                DELETE FROM tacheAdmin WHERE IDEMPLOYE=:idEmployee;
                DELETE FROM employe WHERE idEmploye=:idEmployee';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmployee', $idEmployee, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- CLIENT ----------------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * Renvoie toutes les infos du client dont l'id est en paramètre, 
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idClient L'id du client
 * @return object Les infos du client (IDCLIENT, NOM, PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE, NOMCONSEILLER, PRENOMCONSEILLER, IDEMPLOYE)
 */
function modGetClientFromId($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDCLIENT, client.NOM, client.PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE, employe.NOM AS NOMCONSEILLER, employe.PRENOM AS PRENOMCONSEILLER, client.IDEMPLOYE 
    FROM client JOIN employe ON client.IDEMPLOYE=employe.IDEMPLOYE WHERE idClient=:idClient';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $name Le nom du client
 * @param string $firstName Le prénom du client
 * @param string $birthDate La date de naissance du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientABC($name, $firstName, $birthDate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE nom=:name AND prenom=:firstName AND dateNaissance=:birthDate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $name Le nom du client
 * @param string $firstName Le prénom du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientAB($name, $firstName) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE nom=:name AND prenom=:firstName';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $firstName Le prénom du client
 * @param string $birthDate La date de naissance du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientBC($firstName, $birthDate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE prenom=:firstName AND dateNaissance=:birthDate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $name Le nom du client
 * @param string $birthDate La date de naissance du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientAC($name, $birthDate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE nom=:name AND dateNaissance=:birthDate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $name Le nom du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientA($name) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE nom=:name';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $firstName Le prénom du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientB($firstName) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE prenom=:firstName';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres
 * Rien si il n'est pas présent dans la base de données.
 * @param string $birthDate La date de naissance du client
 * @return array Les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientC($birthDate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE dateNaissance=:birthDate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Renvoie toutes les infos de tous les clients
 * @return array Toutes les infos de tous les clients (IDCLIENT, NOM, PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE, IDEMPLOYE) (tableau d'objets)
 */
function modGetAllClients() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM client';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * Modifie les infos du client dont l'id est en paramètre
 * @param int $idClient L'id du client
 * @param int $idEmploye L'id du conseiller
 * @param string $job La profession
 * @param string $situationFamiliale La situation familiale
 * @param string $address L'adresse
 * @param string $num Le numero de tel
 * @param string $email L'adresse mail
 * @param string $birthDate La date de naissance
 */
function modModifClient($idClient, $idEmploye, $job, $situationFamiliale, $address, $num, $email, $birthDate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE client SET idEmploye=:idEmploye, dateNaissance=:birthDate, adresse=:address, numTel=:num, email=:email, profession=:job, SITUATIONFAMILIALE=:situationFamiliale WHERE idClient=:idClient';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient', $idClient, PDO::PARAM_INT);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
    $prepared -> bindParam(':job', $job, PDO::PARAM_STR);
    $prepared -> bindParam(':situationFamiliale', $situationFamiliale, PDO::PARAM_STR);
    $prepared -> bindParam(':address', $address, PDO::PARAM_STR);
    $prepared -> bindParam(':num', $num, PDO::PARAM_STR);
    $prepared -> bindParam(':email', $email, PDO::PARAM_STR);
    $prepared -> bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Crée un client avec les infos en paramètre
 * @param int $idEmploye L'id du conseiller
 * @param string $name Le nom
 * @param string $firstName Le prénom
 * @param string $birthDate La date de naissance
 * @param string $address L'adresse
 * @param string $num Le numero de tel
 * @param string $email L'adresse mail
 * @param string $job La profession
 * @param string $situationFamiliale La situation familiale
 * @param string $civilite La civilité
 */
function modCreateClient($idEmploye, $name, $firstName, $birthDate, $address, $num, $email, $job, $situationFamiliale, $civilite) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO client(idEmploye, nom, prenom, dateNaissance, dateCreation, adresse, numTel, email, profession, SITUATIONFAMILIALE, civilitee) VALUES (:idEmploye, :name, :firstName, :birthDate, NOW(), :address, :num, :email, :job, :situationFamiliale, :civilite)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmploye', $idEmploye, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $prepared -> bindParam(':birthDate', $birthDate, PDO::PARAM_STR);
    $prepared -> bindParam(':address', $address, PDO::PARAM_STR);
    $prepared -> bindParam(':num', $num, PDO::PARAM_STR);
    $prepared -> bindParam(':email', $email, PDO::PARAM_STR);
    $prepared -> bindParam(':job', $job, PDO::PARAM_STR);
    $prepared -> bindParam(':situationFamiliale', $situationFamiliale, PDO::PARAM_STR);
    $prepared -> bindParam(':civilite', $civilite, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}


# ---------------------------------------------------------------------------------------------------------------------------------------------- #
# ----------------------------------------------------------------- STATSIQUES ----------------------------------------------------------------- #
# ---------------------------------------------------------------------------------------------------------------------------------------------- #


/**
 * Renvoie la somme des soldes de tous les comptes
 * @return string La somme des soldes de tous les comptes
 */
function modSumAllSolde() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT SUM(solde) AS somme FROM Compte';
    $prepared = $connection -> query($query);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result -> somme;
}

/**
 * Renvoie le nombre de contrats
 * @return int Le nombre de contrats
 */
function modGetNumberContracts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbContracts FROM contrat';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbContracts;
}

/**
 * Renvoie le nombre de comptes
 * @return int Le nombre de comptes
 */
function modGetNumberAccounts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAccounts FROM compte';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbAccounts;
}

/**
 * Renvoie le nombre de clients
 * @return int Le nombre de clients
 */
function modGetNumberClients() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbClients FROM client';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbClients;
}

/**
 * Renvoie le nombre de conseillers
 * @return int Le nombre de conseillers
 */
function modGetNumberCounselors() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbCounselors FROM employe WHERE idCategorie=2';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbCounselors;
}

/**
 * Renvoie le nombre d'agents
 * @return int Le nombre d'agents
 */
function modGetNumberAgents() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAgents FROM employe WHERE idCategorie=3';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbAgents;
}

/**
 * Renvoie le nombre de types de contrat
 * @return int Le nombre de types de contrat
 */
function modGetNumberContractTypes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbContractTypes FROM typeContrat';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbContractTypes;
}

/**
 * Renvoie le nombre de types de compte
 * @return int Le nombre de types de compte
 */
function modGetNumberAccountTypes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAccountTypes FROM typeCompte';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbAccountTypes;
}

/**
 * Renvoie le nombre de comptes actifs
 * @return int Le nombre de comptes actifs
 */
function modGetNumberActiveAccounts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbActiveAccounts FROM compte WHERE idTypeCompte IN (SELECT idTypeCompte FROM typeCompte WHERE actif=1)';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbActiveAccounts;
}

/**
 * Renvoie le nombre de comptes inactifs
 * @return int Le nombre de comptes inactifs
 */
function modGetNumberInactiveAccounts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbInactiveAccounts FROM compte WHERE idTypeCompte IN (SELECT idTypeCompte FROM typeCompte WHERE actif=0)';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbInactiveAccounts;
}

/**
 * Renvoie le nombre de contrats actifs
 * @return int Le nombre de contrats actifs
 */
function modGetNumberActiveContracts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbActiveContracts FROM contrat WHERE idTypeContrat IN (SELECT idTypeContrat FROM typeContrat WHERE actif=1)';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbActiveContracts;
}

/**
 * Renvoie le nombre de contrats inactifs
 * @return int Le nombre de contrats inactifs
 */
function modGetNumberInactiveContracts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbInactiveContracts FROM contrat WHERE idTypeContrat IN (SELECT idTypeContrat FROM typeContrat WHERE actif=0)';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbInactiveContracts;
}

/**
 * Renvoie le nombre de comptes étant à découvert (dont le solde est négatif)
 * @return int Le nombre de comptes étant à découvert
 */
function modGetNumberOverdraftAccounts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbOverdraftAccounts FROM compte WHERE solde<0';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbOverdraftAccounts;
}

/**
 * Renvoie le nombre de comptes n'étant pas à découvert (dont le solde est positif ou nul)
 * @return int Le nombre de comptes n'étant pas à découvert
 */
function modGetNumberNonOverdraftAccounts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbNonOverdraftAccounts FROM compte WHERE solde>=0';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbNonOverdraftAccounts;
}

/**
 * Renvoie le nombre de rdv après la dateDebut mais avant la dateFin
 * @param string $dateDebut Date de début
 * @param string $dateFin Date de fin
 * @return int Le nombre de rdv après la dateDebut mais avant la dateFin
 */
function modGetNumberAppointmentsBetween($dateDebut, $dateFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAppointments FROM rdv WHERE horairedebut>:dateDebut AND horairedebut<:dateFin';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbAppointments;
}

/**
 * Renvoie le nombre de contrats souscrit entre la première et la deuxième date mises en paramètres
 * @param string $dateDebut Date de début
 * @param string $dateFin Date de fin
 * @return int Le nombre de contrats souscrit entre la première et la deuxième date mises en paramètres
 */
function modGetNumberContractsBetween($dateDebut, $dateFin) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbContracts FROM contrat WHERE dateouverture>=:dateDebut AND dateouverture<=:dateFin';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':dateDebut', $dateDebut, PDO::PARAM_STR);
    $prepared -> bindParam(':dateFin', $dateFin, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbContracts;
}

/**
 * Renvoie le nombre de client à une date donnée
 * @param string $date La date
 * @return int Le nombre de client à une date donnée
 */
function modGetNumberClientsAt($date){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbClients FROM client WHERE datecreation<=:date';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':date', $date, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbClients;
}






























/* FONCTION NON UTILISEES






function modGetMotifFromId($idMotif) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM motif WHERE idmotif=:idM';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idM', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modModifMotif($idMotif, $intitule, $document) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE motif SET intitule=:intitule, document=:document WHERE idmotif=:idM';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idM', $idMotif, PDO::PARAM_INT);
    $prepared -> bindParam(':intitule', $intitule, PDO::PARAM_STR);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}




function modGetTypeStaff($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idCategorie FROM employe WHERE idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->idCategorie;
}



function modGetAppointmentConseiller($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idRDV, idMotif, idClient, idEmploye, horairedebut, horairefin FROM rdv NATURAL JOIN employe WHERE idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


function modGetAdminConseiller($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idTa, horairedebut, horairefin, libelle FROM tacheadmin NATURAL JOIN employe WHERE idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


function modGetIntituleMotif($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT intitule FROM Motif WHERE idMotif=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->intitule;
}

function modGetIntituleCategorie($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT libellecategorie FROM Categorie WHERE idCategorie=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->libellecategorie;
}


function modCreateMotive($label, $doc) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO motif(intitule, document) VALUES (:label, :doc)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':label', $label, PDO::PARAM_STR);
    $prepared -> bindParam(':doc', $doc, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}


function modGetRichestClient() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'CREATE OR REPLACE VIEW totalIndividualWealth(idClient, totalWealth) AS
                SELECT idClient, SUM(solde) 
	            FROM Client NATURAL JOIN PossedeCompte NATURAL JOIN Compte;
            SELECT IDCLIENT, NOM, PRENOM FROM client WHERE idClient IN (SELECT idClient FROM totalIndividualWealth WHERE totalWealth=(SELECT MAX(totalWealth) FROM totalIndividualWealth));';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


function modGetIdClientFromAccount($idAccount){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient FROM possedeCompte WHERE idCompte=:idAccount';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->idClient;
}

function modGetTypeAccount($idTypeAccount) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typecompte NATURAL JOIN motif WHERE idtypecompte=:idTypeCompte';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeCompte', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}


function modGetIdClientFromContract($idContract){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient FROM possedeContrat WHERE idContrat=:idClient';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idClient', $idContract, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->idClient;
}

function modGetContractFromId($idContractType) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typecontrat NATURAL JOIN motif WHERE idtypecontrat=:idContractType';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idContractType', $idContractType, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

*/