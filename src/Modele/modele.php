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
 * renvoie toutes les infos de l'employe dont le login et password sont en paramètres,
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
    $query = 'SELECT * FROM client WHERE idClient=:idC';
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
 * renvoie l'id de la catégorie à laquelle appartient l'employé dont le login est passé en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param string $logi le login de l'employé
 */
function modGetTypeStaff($logi) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idCategorie FROM employe WHERE login=:logi';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':logi', $logi, PDO::PARAM_STR);
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
 * renvoie l'id du RDV, celui du motif et celui du client ainsi que le login de l'employé et l'horaire de tous les RDV de l'employé dont le login est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param string $logi le login de l'employé 
 */
function modGetAppointmentConseiller($logi) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idRDV,idMotif,idClient,login,horaire FROM rdv NATURAL JOIN employe WHERE login=:logi';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':logi', $logi, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetchAll();
    $prepared -> closeCursor();
    return $result;
}

/**
 * renvoie id de la tache admin, l'horaire et le libelle de toutes les taches admin de l'employé dont le login est en paramètre,
 * rien si il n'est pas présent dans la base de données.
 * @param string $logi le login de l'employé
 */
function modGetAdminConseiller($logi) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idTa,horaire,libelle FROM tacheadmin NATURAL JOIN employe WHERE login=:logi';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':logi', $logi, PDO::PARAM_STR);
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
    $query = 'SELECT COUNT(*) FROM contrat';
    $result = $connection -> query($query);
    $result -> setFetchMode(PDO::FETCH_OBJ);
    $result = $result -> fetch();
    $result -> closeCursor();
    return $result;
}

/**
 * renvoie le nombre de comptes
 */
function modGetNumberComptes() {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT COUNT(*) FROM compte';
    $result = $connection -> query($query);
    $result -> setFetchMode(PDO::FETCH_OBJ);
    $result = $result -> fetch();
    $result -> closeCursor();
    return $result;
}
