<?php
require_once('modele/token.php');
require_once('token.php');

function modGetSalt($login) {
    $connection = Connection::getInstance()->getConnection();
    $query = $connection -> prepare("SELECT salt FROM employe WHERE login=:logi");
    $prepared = $connection->prepare($query);
    $prepared -> bindParam(":logi", $login, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

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

function modGetAccounts($idClient) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idCompte,idTypeCompte,intitule,solde,decouvert,datecreation FROM compte LEFT JOIN possedeCompte ON compte.idCompte=possedeCompte.idCompte WHERE idClient=:idC';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam('idC', $idClient, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modGetTypeStaff($logi) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idCategorie FROM employe WHERE login=:logi';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':logi', $logi, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modAdvancedSearchClient($sname,$fname,$bdate) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idClient, nom, prenom, dateNaissance FROM client WHERE nom=:sname AND prenom=:fname AND dateNaissance=:bdate';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sname', $sname, PDO::PARAM_STR);
    $prepared -> bindParam(':fname', $fname, PDO::PARAM_STR);
    $prepared -> bindParam(':bdate', $bdate, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modGetAppointmentConseiller($logi) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idRDV,idMotif,idClient,login,horaire FROM rdv NATURAL JOIN employe WHERE login=:logi';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':logi', $logi, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modGetAdminConseiller($logi) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT idTa,login,horaire,libelle FROM tacheadmin NATURAL JOIN employe WHERE login=:logi';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':logi', $logi, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modDebit($idA,$sum) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE Compte SET solde=solde-:sum WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modCredit($idA,$sum) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'UPDATE Compte SET decouvert=decouvert-:sum WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> bindParam(':sum', $sum, PDO::PARAM_STR);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}

function modGetDecouvert($idA) {
    $connection = Connection::getInstance()->getConnection();
    $query = 'SELECT decouvert FROM Compte WHERE idCompte=:idA';
    $prepared = $connection -> prepare($query);
    $prepared -> bindParam(':idA', $idA, PDO::PARAM_INT);
    $prepared -> execute();
    $prepared -> setFetchMode(PDO::FETCH_OBJ);
    $result = $prepared -> fetch();
    $prepared -> closeCursor();
    return $result;
}