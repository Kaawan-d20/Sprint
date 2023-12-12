<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index.php" method="post">
        <input type="submit" name="disconnection" value="Déconnexion">
    </form>
    <div>
        <h1>Stats.</h1>
        <p>Nombre de clients : <?php echo $stat['nbClient'] ?></p>
        <p>Nombre de comptes : <?php echo $stat['nbAccount'] ?></p>
        <p>Nombre de contrats : <?php echo $stat['nbContract'] ?></p>
        <p>Nombre de conseillers : <?php echo $stat['nbConseiller'] ?></p>
        <p>Nombre d'agents : <?php echo $stat['nbAgent'] ?></p>
        <p>Nombre de types de compte : <?php echo $stat['nbTypeAccount'] ?></p>
        <p>Nombre de types de contrat : <?php echo $stat['nbTypeContract'] ?></p>
        <p>Nombre de comptes actifs : <?php echo $stat['nbAccountActive'] ?></p>
        <p>Nombre de comptes inactifs : <?php echo $stat['nbAccountInactif'] ?></p>
        <p>Nombre de comptes à découvert : <?php echo $stat['nbAccountDecouvert'] ?></p>
        <p>Nombre de comptes non à découvert : <?php echo $stat['nbAccoutNonDecouvert'] ?></p>
        <form action="index.php" method="post">
            <label >Date de début </label><input type="date" name="datedebut">
            <label > Date de fin </label><input type="date" name="datefin">
            <input type="submit" name='searchStatClient2'value="Rechercher">
            <p>Nombre de RDV entre deux dates : <?php echo $stat['AppoinmentBetween'] ?></p>
            <p>Nombre de Contrat souscrit entre deux dates : <?php echo $stat['ContractBetween'] ?></p>
        </form>
        <form action="index.php" method="post">
            <label >Date de fin </label>
            <input type="date" name="date">
            <input type="submit" value="searchStatClient1">
            <p>Nombre de Client à une date <?php echo $stat['nbClientAt'] ?></p>

        </form>
    </div>
    <div>
        <h1>Gestion du Personnel</h1>
        <form action="index.php" method="post">
            <input type="submit" name="GestionPersonnelAllBtn" value="Gestion du Personnel">
        </form>
    </div>
    <div>
        <h1>Gestion des Services</h1>
        <form action="index.php" method="post">
            <input type="submit" name="GestionServicesAllBtn" value="Gestion des services">
        </form>
    </div>
</body>
</html>