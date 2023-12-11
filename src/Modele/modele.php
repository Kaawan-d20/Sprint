<?php
require_once('modele/token.php');
require_once('token.php');

/**
 * renvoie le salt correspondant au login passé en paramètre, 
 * rien si ce login n'est pas présent dans la base de données.
 * @param string $login le login de l'employé 
 */
function modGetSalt($login) {
    $connection = Connection::getInstance()->getConnection();
    $query = $connection -> prepare("SELECT salt FROM employe WHERE login=:logi");
    $prepared = $connection->prepare($query);
    $prepared -> bindParam(":logi", $login, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->salt;
}

/**
 * renvoie toutes les infos de l'employé dont le login et password sont en paramètres,
 * rien si celui-ci n'est pas présent dans la base de données.
 * @param string $login le login de l'employé
 * @param string $password le password salé de l'employé 
 */
function modConnect($login, $password) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM employe WHERE login=:logi AND password=:pass';
    $prepared = $connection->prepare($query);
    $prepared -> bindParam(':logi',$login,PDO::PARAM_STR);
    $prepared -> bindParam(':pass',$password,PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie toutes les infos du client dont l'id est en paramètre, 
 * rien si il n'est pas présent dans la base de données.
 * @param int $idClient l'id du client
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
 * renvoie tous les comptes du client dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idClient l'id du client
 */
function modGetAccounts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT compte.idCompte,idTypeCompte,intitule,solde,decouvert,datecreation FROM compte LEFT JOIN possedeCompte ON compte.idCompte=possedeCompte.idCompte WHERE idClient=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idC', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'id de la catégorie à laquelle appartient l'employé dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param string $id l'id de l'employé
 */
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

/**
 * renvoie l'id, le nom, le prénom et la date de naissance de tous les clients correspondants aux critères en paramètres,
 * rien si il n'est pas présent dans la base de données.
 * @param string $sname le nom du client
 * @param string $fname le prénom du client
 * @param string $bdate la date de naissance du client
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
 * renvoie l'id du RDV, celui du motif, celui du client et celui de l'employé et l'horaire de tous les RDV de l'employé dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param string $id l'id de l'employé 
 */
function modGetAppointmentConseiller($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idRDV,idMotif,idClient,idEmploye,horaire FROM rdv NATURAL JOIN employe WHERE idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie id de la tache admin, l'horaire et le libelle de toutes les taches admin de l'employé dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param string $id l'id de l'employé
 */
function modGetAdminConseiller($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idTa,horaire,libelle FROM tacheadmin NATURAL JOIN employe WHERE idEmploye=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * débite le compte dont l'id est en paramètre de la somme (positive) en paramètre
 * @param int $idA id du compte à débiter
 * @param string $sum la somme à débiter
 */
function modDebit($idA,$sum) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE Compte SET solde=solde-:sum WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * crédite le compte dont l'id est en paramètre de la somme en paramètre 
 * @param int $idA id du compte à créditer
 * @param string $sum somme à créditer
 */
function modCredit($idA,$sum) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE Compte SET solde=solde+:sum WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> closeCursor();
}

/**
 * renvoie le découvert du compte dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idA l'id du compte
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
 * renvoie d'id du client à qui appartient le compte dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idAccount l'id du compte
 */
function modGetIdClientFromAccount($idAccount){
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient FROM possedeCompte WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->decouvert;
}

/**
 * renvoie le solde du compte dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idA l'id du compte
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
 * renvoie la somme des soldes de tous les comptes
 */
function modSumAllSolde() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT SUM(solde) FROM Compte';
    $result = $connection -> query($query);
    $result -> setFetchMode(PDO::FETCH_OBJ);
    $result = $result -> fetch();
    $result -> closeCursor();
    return $result;
}


/**
 * renvoie tous les contrats du client dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $idClient l'id du client
 */
function modGetContracts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT contrat.idContrat,idTypeContrat,intitule,tarifmensuel,dateouverture FROM contrat LEFT JOIN possedeContrat ON contrat.idContrat=possedeContrat.idContrat WHERE idClient=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idC', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie le nombre de contrats
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
 * renvoie l'intitule du motif dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param int $id l'id du motif
 */
function modGetIntituleMotif($id) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT intitule FROM Motif WHERE idMotif=:id';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':id', $id, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie toutes les infos de l'employé dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données
 * @param string $id l'id de l'employé
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
 * renvoie le nombre de clients
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
 */
function modGetNumberActiveContracts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAciveContracts FROM contrat WHERE idTypeContrat IN (SELECT idTypeContrat FROM typeContrat WHERE actif=1)';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbActiveContracts;
}

/**
 * renvoie le nombre de contrats inactifs
 */
function modGetNumberInactiveContracts() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbInactiveContracts FROM contrat WHERE idTypeContrat IN (SELECT idTypeContrat FROM typeContrat WHERE actif=0';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result->nbInactiveContracts;
}

/**
 * renvoie le nombre de comptes étant à découvert (dont le solde est négatif)
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
 * renvoie toutes les infos de tous les types de compte
 */
function modGetAllAccountTypes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typeCompte';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie toutes les informations de tous les types de contrat
 */
function modGetAllContractTypes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typeContrat';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

function modGetAllMotif() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM motif';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

function modGetTypeAccount($idTypeAccount) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typecompte WHERE idtypecompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idTypeAccount, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result;
}
function modGetContractFromId($idContractType) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM typecontrat WHERE idtypecontrat=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idContractType, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result;
}

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

function modModifTypeAccount($idAccount, $name, $active){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE typecompte SET nom=:name, actif=:active WHERE idtypecompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idAccount, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
}

function modModifTypeContract($idContract, $name, $active){
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE typecontrat SET nom=:name, actif=:active WHERE idtypecontrat=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idContract, PDO::PARAM_INT);
    $prepared -> bindParam(':name', $name, PDO::PARAM_STR);
    $prepared -> bindParam(':active', $active, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> closeCursor();
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


/**
 * renvoie le ou les client(s) dont la somme des soldes des comptes est la plus élevée,
 */
function modGetRichestClient() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'CREATE OR REPLACE VIEW totalIndividualWealth(idClient,totalWealth) AS
                SELECT idClient,SUM(solde) 
	            FROM Client NATURAL JOIN PossedeCompte NATURAL JOIN Compte;
            SELECT * FROM client WHERE idClient IN (SELECT idClient FROM totalInvidualWealth WHERE totalWealth=(SELECT MAX(totalWealth) FROM totalIndividualWealth));';
    $prepared = $connection -> query($query);
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie l'intitulé de la catégorie dont l'id est en paramètre,
 * rien si il n'est pas présent dans la base de données
 * @param int $id l'id de la catégorie
 */
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

/**
 * modifie les nom, prénom, login, password et id de catégorie de l'employé dont l'id est en premier paramètre
 * @param int $idE l'id de l'employé
 * @param string $sname le nom de l'employé
 * @param string $fname le prénom de l'employé
 * @param string $login le login de l'employé
 * @param string $password le password (salé) de l'employé
 * @param int $idCat l'id de la catégorie de l'employé
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
 * renvoie tous les rdv entre la première et la deuxième date mises en paramètres,
 * rien si il n'y en a pas dans la base de données
 * @param string $date1 date de début
 * @param string $date2 date de fin
 */
function modGetAllAppoinmentsBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM rdv WHERE horaire>:d1 AND horaire<:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $prepared;
}

/**
 * renvoie toutes les ta entre la première et la deuxième date mises en paramètres,
 * rien si il n'y en a pas dans la base de données
 * @param string $date1 date de début
 * @param string $date2 date de fin
 */
function modGetAllAdminBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT * FROM tacheAdmin WHERE horaire>:d1 AND horaire<:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $prepared;
}

/**
 * renvoie le nombre de rdv après la date1 mais avant la date2
 * @param string $date1 date de début
 * @param string $date2 date de fin
 */
function modGetNumberAppointmentsBetween($date1,$date2) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) AS nbAppointments FROM rdv WHERE horaire>:d1 AND horaire<:d2';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':d1', $date1, PDO::PARAM_STR);
    $prepared -> bindParam(':d2', $date2, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    return $result->nbAppointments;
}

/**
 * renvoie le nombre de contrats créés après la date1 mais avant la date2
 * @param string $date1 date de début
 * @param string $date2 date de fin
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
 * renvoie le nombre de clients à la date en paramètre
 * @param string $date la date
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
 * cree un nouveau rdv
 * @param int $idM l'id du motif
 * @param int $idC l'id du client
 * @param int $idE l'id de l'employe
 * @param string $time la date et l'heure 
 */
function modAddAppointment($idM,$idC,$idE,$time) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO rdv(idMotif,idClient,idEmploye,horaire) VALUES (:idM,:idC,:idE,:dt)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idM', $idM, PDO::PARAM_INT);
    $prepared -> bindParam(':idC', $idC, PDO::PARAM_INT);
    $prepared -> bindParam(':idE', $idE, PDO::PARAM_INT);
    $prepared -> bindParam(':dt', $time, PDO::PARAM_STR);
    $prepared -> execute();
}

/**
 * modifie le decouvert du compte dont l'id est en paramètre
 * @param int $idC l'id du compte
 * @param string $deco le découvert
 */
function modSetDecouvert($idC,$deco) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE compte SET decouvert=:deco WHERE idCompte=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idC', $idC, PDO::PARAM_INT);
    $prepared -> bindParam(':deco', $deco, PDO::PARAM_STR);
    $prepared -> execute();
}

function modCreateMotive($label,$doc) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO motif(intitule,document) VALUES (:label,:doc)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':label', $label, PDO::PARAM_STR);
    $prepared -> bindParam(':doc', $doc, PDO::PARAM_STR);
    $prepared -> execute();
}

function modCreateTypeAccount($idM,$name) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO typeCompte(idMotif,nom) VALUES (:idM,:nameA)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idM', $idM, PDO::PARAM_INT);
    $prepared -> bindParam(':nameA', $name, PDO::PARAM_STR);
    $prepared -> execute();
}

function modCreateTypeContract($idM,$name) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'INSERT INTO typeContrat(idMotif,nom) VALUES (:idM,:nameA)';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idM', $idM, PDO::PARAM_INT);
    $prepared -> bindParam(':nameA', $name, PDO::PARAM_STR);
    $prepared -> execute();
}

function modModifClient($idC,$idE,$sname,$fname,$dob,$dc,$adr,$num,$email,$job,$fam,$civ) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE client SET idEmploye=:idE, nom=:sname, prenom=:fname, dateNaissance=:dob, dateCreation=:dc, adresse=:adr, numTel=:num, email=:email, profession=:job, situationFamiliale=:fam, civilite=:civ WHERE idClient=:idC';
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