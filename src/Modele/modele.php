<?php
require_once('modele/token.php');
require_once('token.php');

/**
 * Renvoie le salt correspondant au login passé en paramètre,
 * Rien si ce login n'est pas présent dans la base de données.
 * @param string $login Le login de l'employé.
 * @return string Le salt de l'employé.
 */
function modGetSalt($login) {
    $connection = Connection::getInstance()->getConnection();
    $query = $connection -> prepare("SELECT SALT FROM employe WHERE login=:login");
    $prepared = $connection->prepare($query);
    $prepared -> bindParam(":login", $login, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->SALT;
}

/**
 * Renvoie toutes les infos de l'employé dont le login et password sont en paramètres,
 * Rien si celui-ci n'est pas présent dans la base de données.
 * @param string $login Le login de l'employé
 * @param string $password Le password salé de l'employé
 * @return object Les infos de l'employé (IDEMPLOYE, IDCATEGORIE, NOM)
 */
function modConnect($login, $password) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDEMPLOYE, IDCATEGORIE, NOM FROM employe WHERE login=:login AND password=:password';
    $prepared = $connection->prepare($query);
    $prepared -> bindParam(':login',$login,PDO::PARAM_STR);
    $prepared -> bindParam(':password',$password,PDO::PARAM_STR);
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
 * Renvoie tous les comptes du client dont l'id est en paramètre,
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idClient L'id du client
 * @return array Les comptes du client (IDCOMPTE, NOM, SOLDE, DECOUVERT, DATECREATION, IDTYPECOMPTE) (tableau d'objets)
 */
function modGetAccounts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT compte.idCompte,typecompte.NOM,solde,decouvert,datecreation, idtypecompte
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
 * Renvoie d'id du client à qui appartient le compte dont l'id est en paramètre,
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idAccount L'id du compte
 * @return int L'id du client
 */
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
    $idCompte = $connection->lastInsertId();

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
 * @param string $date La date de l'opération
 * @return void
 */
function modDebit($idAccount,$sum,$date) {
    $connection = Connection::getInstance()->getConnection();
    $query = "UPDATE Compte SET solde=solde-:sum WHERE idCompte=:idAccount;
                INSERT INTO `operation`(`IDCOMPTE`, `SOURCE`, `LIBELLE`, `DATEOPERATION`, `MONTANT`, `ISCREDIT`) VALUES (:idAccount,'Banque','Debit',:date,:sum,0)";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':date', $date, PDO::PARAM_STR);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Crédite le compte dont l'id est en paramètre de la somme en paramètre 
 * @param int $idAccount L'id du compte à créditer
 * @param string $sum La somme à créditer
 * @param string $date La date de l'opération
 * @return void
 */
function modCredit($idAccount,$sum,$date) {
    $connection = Connection::getInstance()->getConnection();
    $query = "UPDATE Compte SET solde=solde+:sum WHERE idCompte=:idAccount;
                INSERT INTO `operation`(`IDCOMPTE`, `SOURCE`, `LIBELLE`, `DATEOPERATION`, `MONTANT`, `ISCREDIT`) VALUES (:idAccount,'Banque','Crédit',:date,:sum,1)";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAccount', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> bindParam(':date', $date, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}




/**
 * Modifie le decouvert du compte dont l'id est en paramètre
 * @param int $idAccount L'id du compte
 * @param string $overdraft Le montant du découvert
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
 * Renvoie le découvert du compte dont l'id est en paramètre,
 * Rien si il n'est pas présent dans la base de données.
 * @param int $idA L'id du compte
 * @return string Le découvert du compte
 */
function modGetDecouvert($idA) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT decouvert FROM Compte WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->decouvert;
}

/**
 * Renvoie le solde du compte dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idA l'id du compte
 * @return string le solde du compte
 */
function modGetSolde($idA) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT solde FROM Compte WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->solde;
}

/**
 * renvoie la liste des operations du compte dont l'id est en paramètre
 * @param int $id l'id du compte
 * @return array la liste des operations du compte dont l'id est en paramètre (IDOPERATION, IDCOMPTE, SOURCE, LIBELLE, DATEOPERATION, MONTANT, ISCREDIT) (tableau d'objets)
 */
function modGetOperations($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM operation WHERE idCompte=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_INT);
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
 * renvoie toutes les infos de tous les types de compte
 * @return array toutes les infos de tous les types de compte (IDTYPECOMPTE, NOM, ACTIF, DOCUMENT, IDMOTIF) (tableau d'objets)
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
 * renvoie toutes les informations de tous les types de compte qui sont actifs
 * @return array toutes les infos de tous les types de compte qui sont actifs(IDTYPECOMPTE, NOM, ACTIF, DOCUMENT, IDMOTIF) (tableau d'objets)
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
 * renvoie toutes les infos du type de compte dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données
 * @param int $idTypeAccount l'id du type de compte
 * @return object les infos du type de compte (IDTYPECOMPTE, NOM, ACTIF, DOCUMENT, IDMOTIF)
 */
function modGetTypeAccount($idTypeAccount) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typecompte NATURAL JOIN motif WHERE idtypecompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result;
}


/**
 * Fonction qui permet de mettre à jour les informations d'un type de compte
 * @param int $idAccount l'id du type de compte
 * @param string $name le nom du type de compte
 * @param int $active 1 si le type de compte est actif, 0 sinon
 * @param string $document le document du type de compte
 * @param int $idMotif l'id du motif du type de compte
 * @return void
 */
function modModifTypeAccount($idAccount, $name, $active, $document, $idMotif){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE typecompte SET nom=:name, actif=:active WHERE idtypecompte=:idA;
                UPDATE motif SET document=:document WHERE idmotif=:idM';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> bindParam(':idM', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}


/**
 * Fonction qui permet d'ajouter un type de compte
 * @param string $name le nom du type de compte
 * @param int $active 1 si le type de compte est actif, 0 sinon
 * @param string $document le document du type de compte
 * @return void
 */
function modAddTypeAccount($name, $active, $document){
    $nameMotif = 'Gestion de '.$name;
    $connection = Connection::getInstance()->getConnection();
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = 'INSERT INTO `motif`(`INTITULE`, `DOCUMENT`) VALUES (:nameMotif,:document);
    INSERT INTO `typecompte`(`IDMOTIF`, `NOM`, `ACTIF`) VALUES ((SELECT LAST_INSERT_ID()),:name,:active)';
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
 * @param int $idTypeAccount l'id du type de compte
 * @return void
 */
function modDeleteTypeAccount($idTypeAccount){
    $connection = Connection::getInstance()->getConnection();
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    
    // Récupérer l'idmotif
    $query = 'SELECT idmotif FROM typecompte WHERE idtypecompte=:idTypeAccount';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeAccount', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $idMotif = $prepared->fetchColumn();
    $prepared -> closeCursor();

    // Supprimer les entrées
    $query = 'DELETE FROM typecompte WHERE idTypeCompte=:idTypeAccount;
              DELETE FROM motif WHERE idmotif = :idMotif';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeAccount', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':idMotif', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}




# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- CONTRACT ------------------------------------------------------------ #
# ------------------------------------------------------------------------------------------------------------------------------------------ #

/**
 * renvoie tous les contrats du client dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idClient l'id du client
 * @return array les contrats du client (IDCONTRAT, IDTYPECONTRAT, TARIFMENSUEL, DATEOUVERTURE) (tableau d'objets)
 */
function modGetContracts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT contrat.idContrat,idTypeContrat,tarifmensuel,dateouverture, typecontrat.NOM FROM contrat LEFT JOIN possedeContrat ON contrat.idContrat=possedeContrat.idContrat NATURAL JOIN typecontrat WHERE idClient=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idC', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

function modGetIdClientFromContract($idContract){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient FROM possedeContrat WHERE idContrat=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idContract, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->idClient;
}



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


function modAddContractToClientTwo($idClient, $idClient2, $monthCost, $idTypeContract){
    $connection = Connection::getInstance()->getConnection();

    // Première requête
    $query = 'INSERT INTO contrat(idTypeContrat, TarifMensuel, dateOuverture) VALUES (:idTypeContrat, :monthCost, NOW())';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeContrat', $idTypeContract, PDO::PARAM_INT);
    $prepared -> bindParam(':monthCost', $monthCost, PDO::PARAM_STR);
    $prepared -> execute();

    // Récupérer l'ID du contrat inséré
    $idContrat = $connection->lastInsertId();

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
 * renvoie toutes les informations de tous les types de contrat
 * @return array toutes les infos de tous les types de contrat (IDTYPECONTRAT, NOM, ACTIF, DOCUMENT, IDMOTIF) (tableau d'objets)
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
 * renvoie toutes les informations de tous les types de contrat qui sont actifs
 * @return array toutes les infos de tous les types de contrat qui sont actifs(IDTYPECONTRAT, NOM, ACTIF, DOCUMENT, IDMOTIF) (tableau d'objets)
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
 * renvoie toutes les infos du type de contrat dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données
 * @param int $idContractType l'id du type de contrat
 * @return object les infos du type de contrat (IDTYPECONTRAT, NOM, ACTIF, DOCUMENT, IDMOTIF)
 */
function modGetContractFromId($idContractType) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typecontrat NATURAL JOIN motif WHERE idtypecontrat=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idContractType, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result;
}

/**
 * Fonction qui permet de mettre à jour les informations d'un type de contrat
 * @param int $idContract l'id du type de contrat
 * @param string $name le nom du type de contrat
 * @param int $active 1 si le type de contrat est actif, 0 sinon
 * @param string $document le document du type de contrat
 * @param int $idMotif l'id du motif du type de contrat
 * @return void
 */
function modModifTypeContract($idContract, $name, $active, $document, $idMotif){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE typecontrat SET nom=:name, actif=:active WHERE idtypecontrat=:idC;
                UPDATE motif SET document=:document WHERE idmotif=:idM';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idContract, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> bindParam(':idM', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}



/**
 * Fonction qui permet d'ajouter un type de contrat
 * @param string $name le nom du type de contrat
 * @param int $active 1 si le type de contrat est actif, 0 sinon
 * @param string $document le document du type de contrat
 * @return void
 */
function modAddTypeContract($name, $active, $document){
    $connection = Connection::getInstance()->getConnection();
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    $query = "INSERT INTO `motif`(`INTITULE`, `DOCUMENT`) VALUES ('Gestion de :name',:document);
    INSERT INTO `typecontrat`(`IDMOTIF`, `NOM`, `ACTIF`) VALUES ((SELECT LAST_INSERT_ID()),:name,:active)";
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> bindParam(':document', $document, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}



/**
 * Fonction qui permet de supprimer un type de contrat
 * @param int $idTypeContract l'id du type de contrat
 * @return void
 */
function modDeleteTypeContract($idTypeContract){
    $connection = Connection::getInstance()->getConnection();
    $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    
    // Récupérer l'idmotif
    $query = 'SELECT idmotif FROM typecontrat WHERE idtypecontrat=:idTypeContract';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeContract', $idTypeContract, PDO::PARAM_INT);
    $prepared -> execute();
    $idMotif = $prepared->fetchColumn();
    $prepared -> closeCursor();

    // Supprimer les entrées
    $query = 'DELETE FROM typecontrat WHERE idTypecontrat=:idTypeContract;
              DELETE FROM motif WHERE idmotif = :idMotif';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idTypeContract', $idTypeContract, PDO::PARAM_INT);
    $prepared -> bindParam(':idMotif', $idMotif, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}




# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- TACHE ADMIN ------------------------------------------------------------ #
# ------------------------------------------------------------------------------------------------------------------------------------------ #

/**
 * renvoie tous les TA entre la première et la deuxième date mises en paramètres,
 * rien si il n'y en a pas dans la base de données
 * @param int $id l'id du conseiller
 * @param string $date1 date de début
 * @param string $date2 date de fin
 * @return array tous les TA entre la première et la deuxième date mises en paramètres (HORAIREDEBUT, HORAIREFIN) (tableau d'objets)
 */
function modGetTABetweenCounselor($id,$date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT HORAIREDEBUT, HORAIREFIN FROM tacheadmin WHERE horairedebut>:d1 AND horairedebut<=:d2 AND idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> bindParam(':id', $id, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result= $prepared -> fetchAll();
    $prepared -> closeCursor();
    debug($result);
    return $result;
}

/**
 * renvoie toutes les ta entre la première et la deuxième date mises en paramètres,
 * rien si il n'y en a pas dans la base de données
 * @param string $date1 date de début
 * @param string $date2 date de fin
 */
function modGetAllAdminBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT *  FROM tacheAdmin NATURAL JOIN employe WHERE horairedebut>:d1 AND horairedebut<:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result=$prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}



/**
 * cree une ta avec l'id d'employé, l'horaire et le libelle en paramètre
 * @param int $idE l'id de l'employé
 * @param string $hd l'horaire de début
 * @param string $hf l'horaire de fin
 * @param string $label le libelle
 */
function modCreateAdmin($idE,$hd,$hf,$label) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO tacheAdmin(idEmploye,horaireDebut,horaireFin,libelle) VALUES (:idE,:hd,:hf,:label)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idE', $idE, PDO::PARAM_INT);
    $prepared -> bindParam(':hd', $hd, PDO::PARAM_STR);
    $prepared -> bindParam(':hf', $hf, PDO::PARAM_STR);
    $prepared -> bindParam(':label', $label, PDO::PARAM_STR);
    $prepared -> execute();
}

function modDeleteAdmin($idAdmin){
    $connection = Connection::getInstance()->getConnection();
    $query = 'DELETE FROM tacheAdmin WHERE idTA=:idAdmin';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idAdmin', $idAdmin, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- APPOINTMENT ---------------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #



/**
 * renvoie tous les rdv entre la première et la deuxième date mises en paramètres,
 * rien si il n'y en a pas dans la base de données
 * @param string $date1 date de début
 * @param string $date2 date de fin
 * @return array tous les rdv entre la première et la deuxième date mises en paramètres (IDEMPLOYE, IDENTITEEMPLOYE, COLOR, IDCLIENT, IDENTITECLIENT, HORAIREDEBUT, HORAIREFIN, INTITULE) (tableau d'objets
 */
function modGetAllAppoinmentsBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT rdv.IDEMPLOYE, rdv.IDRDV,
    CONCAT(employe.PRENOM," ", employe.NOM) AS IDENTITEEMPLOYE,
    employe.COLOR,
    rdv.IDCLIENT,
    CONCAT(client.CIVILITEE," ", client.PRENOM," ", client.NOM) AS identiteClient,
    rdv.HORAIREDEBUT,
    rdv.HORAIREFIN,
    motif.INTITULE,
    motif.DOCUMENT
    FROM rdv
    JOIN employe ON rdv.IDEMPLOYE=employe.IDEMPLOYE
    JOIN motif ON rdv.IDMOTIF=motif.IDMOTIF
    JOIN client ON rdv.IDCLIENT=client.IDCLIENT WHERE horairedebut>:d1 AND horairedebut<=:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result= $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


/**
 * renvoie tous les rdv entre la première et la deuxième date mises en paramètres,
 * rien si il n'y en a pas dans la base de données
 * @param int $id l'id du conseiller
 * @param string $date1 date de début
 * @param string $date2 date de fin
 * @return array tous les rdv entre la première et la deuxième date mises en paramètres (IDEMPLOYE, IDENTITEEMPLOYE, COLOR, IDCLIENT, IDENTITECLIENT, HORAIREDEBUT, HORAIREFIN, INTITULE) (tableau d'objets
 */
function modGetAppoinmentsBetweenCounselor($id,$date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT HORAIREDEBUT, HORAIREFIN FROM rdv WHERE horairedebut>:d1 AND horairedebut<=:d2 AND idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> bindParam(':id', $id, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result= $prepared -> fetchAll();
    $prepared -> closeCursor();
    debug($result);
    return $result;
}


/**
 * cree un nouveau rdv
 * @param int $idM l'id du motif
 * @param int $idC l'id du client
 * @param int $idE l'id de l'employe
 * @param string $hd l'horaire de début
 * @param string $hf l'horaire de fin 
 */
function modAddAppointment($idM,$idC,$idE,$hd,$hf) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO rdv(idMotif,idClient,idEmploye,horaireDebut,horaireFin) VALUES (:idM,:idC,:idE,:hd,:hf)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idM', $idM, PDO::PARAM_INT);
    $prepared -> bindParam(':idC', $idC, PDO::PARAM_INT);
    $prepared -> bindParam(':idE', $idE, PDO::PARAM_INT);
    $prepared -> bindParam(':hd', $hd, PDO::PARAM_STR);
    $prepared -> bindParam(':hf', $hf, PDO::PARAM_STR);
    $prepared -> execute();
}

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
 * renvoie toutes les infos de l'employé dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données
 * @param string $id l'id de l'employé
 * @return object les infos de l'employé (IDEMPLOYE, IDCATEGORIE, NOM, PRENOM, LOGIN, PASSWORD, COLOR, SALT)
 */
function modGetEmployeFromId($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM employe WHERE idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}


/**
 * renvoie toutes les infos de tous les employés
 * @return array toutes les infos de tous les employés (IDEMPLOYE, IDCATEGORIE, NOM, PRENOM, LOGIN, PASSWORD, COLOR, SALT) (tableau d'objets)
 */
function modGetAllEmployes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM employe';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


/**
 * modifie les nom, prénom, login, password et id de catégorie de l'employé dont l'id est en premier paramètre
 * @param int $idE l'id de l'employé
 * @param string $sname le nom de l'employé
 * @param string $fname le prénom de l'employé
 * @param string $login le login de l'employé
 * @param string $password le password (salé) de l'employé
 * @param int $idCat l'id de la catégorie de l'employé
 * @param string $color la couleur de l'employé
 * @return void
 */
function modModifEmploye($idE, $sname, $fname, $login, $password, $idCat, $color) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE employe set nom=:sname, prenom=:fname, login=:login, password=:password, idCategorie=:idCat, color=:color WHERE idEmploye=:idE';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idE', $idE, PDO::PARAM_INT);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> bindParam(':login', $login, PDO::PARAM_STR);
    $prepared -> bindParam(':password', $password, PDO::PARAM_STR);
    $prepared -> bindParam(':idCat', $idCat, PDO::PARAM_INT);
    $prepared -> bindParam(':color', $color, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * Fonction qui permet de supprimer un employé
 * @param int $idEmployee l'id de l'employé
 * @return void
 */
function modDeleteEmploye($idEmployee){
    $connection = Connection::getInstance()->getConnection();
    $query = 'DELETE FROM employe WHERE idEmploye=:idEmployee';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idEmployee', $idEmployee, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

function modGetAllCounselors(){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idEmploye, CONCAT(employe.PRENOM," ", employe.NOM) AS identiteEmploye FROM employe WHERE idCategorie=2';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}



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
 * @param int $idCategorie l'id de la catégorie de l'employé
 * @param string $name le nom de l'employé
 * @param string $firstName le prénom de l'employé
 * @param string $login le login de l'employé
 * @param string $password le password (salé) de l'employé
 * @param string $color la couleur de l'employé
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


# ------------------------------------------------------------------------------------------------------------------------------------------ #
# ----------------------------------------------------------------- CLIENT ----------------------------------------------------------------- #
# ------------------------------------------------------------------------------------------------------------------------------------------ #


/**
 * renvoie toutes les infos du client dont l'id est en paramètre, 
 * rien si il n'est pas présent dans la base de données.
 * @param int $idClient l'id du client
 * @return object les infos du client (IDCLIENT, NOM, PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE, NOMCONSEILLER, PRENOMCONSEILLER)
 */
function modGetClientFromId($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT IDCLIENT, client.NOM, client.PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE, employe.NOM AS NOMCONSEILLER, employe.PRENOM AS PRENOMCONSEILLER FROM client JOIN employe ON client.IDEMPLOYE=employe.IDEMPLOYE WHERE idClient=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}



/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $sname le nom du client
 * @param string $fname le prénom du client
 * @param string $bdate la date de naissance du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientABC($sname,$fname,$bdate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE nom=:sname AND prenom=:fname AND dateNaissance=:bdate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> bindParam(':bdate', $bdate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $sname le nom du client
 * @param string $fname le prénom du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientAB($sname,$fname) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient,nom,prenom,dateNaissance FROM client WHERE nom=:sname AND prenom=:fname';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $fname le prénom du client
 * @param string $bdate la date de naissance du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientBC($fname,$bdate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient,nom,prenom,dateNaissance FROM client WHERE prenom=:fname AND dateNaissance=:bdate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> bindParam(':bdate', $bdate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $sname le nom du client
 * @param string $bdate la date de naissance du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientAC($sname,$bdate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient,nom,prenom,dateNaissance FROM client WHERE nom=:sname AND dateNaissance=:bdate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':bdate', $bdate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $sname le nom du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientA($sname) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient,nom,prenom,dateNaissance FROM client WHERE nom=:sname';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $fname le prénom du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientB($fname) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient,nom,prenom,dateNaissance FROM client WHERE prenom=:fname';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $bdate la date de naissance du client
 * @return array les clients correspondants aux critères (IDCLIENT, NOM, PRENOM, DATENAISSANCE) (tableau d'objets)
 */
function modAdvancedSearchClientC($bdate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient,nom,prenom,dateNaissance FROM client WHERE dateNaissance=:bdate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':bdate', $bdate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


/**
 * renvoie toutes les infos de tous les clients
 * @return array toutes les infos de tous les clients (IDCLIENT, NOM, PRENOM, DATENAISSANCE, DATECREATION, ADRESSE, NUMTEL, EMAIL, PROFESSION, SITUATIONFAMILIALE, CIVILITEE, IDEMPLOYE) (tableau d'objets)
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
 * modifie les infos du client dont l'id est en paramètre
 * @param int $idC l'id du client
 * @param int $idE l'id du conseiller
 * @param string $sname le nom
 * @param string $fname le prenom
 * @param string $dob la date de naissance
 * @param string $dc la date de creation
 * @param string $adr l'adresse
 * @param string $num le numero de tel
 * @param string $email l'adresse mail
 * @param string $job la profession
 * @param string $fam la situation familiale
 * @param string $civ la civilite
 */
function modModifClient($idC,$idE,$sname,$fname,$dob,$dc,$adr,$num,$email,$job,$fam,$civ) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE client SET idEmploye=:idE, nom=:sname, prenom=:fname, dateNaissance=:dob, dateCreation=:dc, adresse=:adr, numTel=:num, email=:email, profession=:job, SITUATIONFAMILIALE=:fam, civilitee=:civ WHERE idClient=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idC, PDO::PARAM_INT);
    $prepared -> bindParam(':idE', $idE, PDO::PARAM_INT);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> bindParam(':dob', $dob, PDO::PARAM_STR);
    $prepared -> bindParam(':dc', $dc, PDO::PARAM_STR);
    $prepared -> bindParam(':adr', $adr, PDO::PARAM_STR);
    $prepared -> bindParam(':num', $num, PDO::PARAM_STR);
    $prepared -> bindParam(':email', $email, PDO::PARAM_STR);
    $prepared -> bindParam(':job', $job, PDO::PARAM_STR);
    $prepared -> bindParam(':fam', $fam, PDO::PARAM_STR);
    $prepared -> bindParam(':civ', $civ, PDO::PARAM_STR);
    $prepared -> execute();
}



/**
 * cree un client avec les infos en paramètre
 * @param int $idE l'id du conseiller
 * @param string $sname le nom
 * @param string $fname le prenom
 * @param string $dob la date de naissance
 * @param string $dc la date de creation
 * @param string $adr l'adresse
 * @param string $num le numero de tel
 * @param string $email l'adresse mail
 * @param string $job la profession
 * @param string $fam la situation familiale
 * @param string $civ la civilite
 */
function modCreateClient($idE,$sname,$fname,$dob,$dc,$adr,$num,$email,$job,$fam,$civ) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO client(idEmploye,nom,prenom,dateNaissance,dateCreation,adresse,numTel,email,profession,SITUATIONFAMILIALE,civilitee) VALUES (:idE,:sname,:fname,:dob,:dc,:adr,:num,:email,:job,:fam,:civ)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idE', $idE, PDO::PARAM_INT);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> bindParam(':dob', $dob, PDO::PARAM_STR);
    $prepared -> bindParam(':dc', $dc, PDO::PARAM_STR);
    $prepared -> bindParam(':adr', $adr, PDO::PARAM_STR);
    $prepared -> bindParam(':num', $num, PDO::PARAM_STR);
    $prepared -> bindParam(':email', $email, PDO::PARAM_STR);
    $prepared -> bindParam(':job', $job, PDO::PARAM_STR);
    $prepared -> bindParam(':fam', $fam, PDO::PARAM_STR);
    $prepared -> bindParam(':civ', $civ, PDO::PARAM_STR);
    $prepared -> execute();
}






# ---------------------------------------------------------------------------------------------------------------------------------------------- #
# ----------------------------------------------------------------- STATSIQUES ----------------------------------------------------------------- #
# ---------------------------------------------------------------------------------------------------------------------------------------------- #

/**
 * renvoie la somme des soldes de tous les comptes
 * @return string la somme des soldes de tous les comptes
 */
function modSumAllSolde() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT SUM(solde) AS somme FROM Compte';
    $prepared = $connection -> query($query);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->somme;
}


/**
 * renvoie le nombre de contrats
 * @return int le nombre de contrats
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
 * renvoie le nombre de comptes
 * @return int le nombre de comptes
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
 * renvoie le nombre de clients
 * @return int le nombre de clients
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
 * renvoie le nombre de conseillers
 * @return int le nombre de conseillers
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
 * renvoie le nombre d'agents
 * @return int le nombre d'agents
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
 * renvoie le nombre de types de contrat
 * @return int le nombre de types de contrat
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
 * renvoie le nombre de types de compte
 * @return int le nombre de types de compte
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
 * renvoie le nombre de comptes actifs
 * @return int le nombre de comptes actifs
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
 * renvoie le nombre de comptes inactifs
 * @return int le nombre de comptes inactifs
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
 * renvoie le nombre de contrats actifs
 * @return int le nombre de contrats actifs
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
 * renvoie le nombre de contrats inactifs
 * @return int le nombre de contrats inactifs
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
 * renvoie le nombre de comptes étant à découvert (dont le solde est négatif)
 * @return int le nombre de comptes étant à découvert
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
 * renvoie le nombre de comptes n'étant pas à découvert (dont le solde est positif ou nul)
 * @return int le nombre de comptes n'étant pas à découvert
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
 * renvoie le ou les client(s) dont la somme des soldes des comptes est la plus élevée,
 * @return array le ou les client(s) dont la somme des soldes des comptes est la plus élevée (IDCLIENT, NOM, PRENOM) (tableau d'objets)
 */
function modGetRichestClient() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'CREATE OR REPLACE VIEW totalIndividualWealth(idClient,totalWealth) AS
                SELECT idClient,SUM(solde) 
	            FROM Client NATURAL JOIN PossedeCompte NATURAL JOIN Compte;
            SELECT IDCLIENT, NOM, PRENOM FROM client WHERE idClient IN (SELECT idClient FROM totalIndividualWealth WHERE totalWealth=(SELECT MAX(totalWealth) FROM totalIndividualWealth));';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}


/**
 * renvoie le nombre de rdv après la date1 mais avant la date2
 * @param string $date1 date de début
 * @param string $date2 date de fin
 */
function modGetNumberAppointmentsBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAppointments FROM rdv WHERE horairedebut>:d1 AND horairedebut<:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result->nbAppointments;
}


/**
 * renvoie le nombre de contrats souscrit entre la première et la deuxième date mises en paramètres
 * @param string $date1 date de début
 * @param string $date2 date de fin
 * @return int le nombre de contrats souscrit entre la première et la deuxième date mises en paramètres
 */
function modGetNumberContractsBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbContracts FROM contrat WHERE dateouverture>:d1 AND dateouverture<:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result->nbContracts;
}

/**
 * renvoie le nombre de client à une date donnée
 * @param string $date la date
 * @return int le nombre de client à une date donnée
 */
function modGetNumberClientsAt($date){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbClients FROM client WHERE datecreation<=:d';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d', $date, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result->nbClients;
}

/**
 * renvoie tous les rdv du client dont l'id est en paramètre
 * @param int $id l'id du client
 */
function modGetAppointmentsClient($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM rdv WHERE idClient=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
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
    $query = 'SELECT idRDV,idMotif,idClient,idEmploye,horairedebut, horairefin FROM rdv NATURAL JOIN employe WHERE idEmploye=:id';
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


function modCreateMotive($label,$doc) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO motif(intitule,document) VALUES (:label,:doc)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':label', $label, PDO::PARAM_STR);
    $prepared -> bindParam(':doc', $doc, PDO::PARAM_STR);
    $prepared -> execute();
}

*/