<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="light">
    <div class="directeurWrapper">
    <?php echo $navbar ?>
    <div class="statWrapper">
        <h1>Statistiques globales
        </h1>
        <!-- <p>Nombre de clients : <?php echo $stat['nbClient'] ?></p>
        <p>Nombre de comptes : <?php echo $stat['nbAccount'] ?></p>
        <p>Nombre de contrats : <?php echo $stat['nbContract'] ?></p>
        <p>Nombre de conseillers : <?php echo $stat['nbConseiller'] ?></p>
        <p>Nombre d'agents : <?php echo $stat['nbAgent'] ?></p>
        <p>Nombre de types de compte : <?php echo $stat['nbTypeAccount'] ?></p>
        <p>Nombre de types de contrat : <?php echo $stat['nbTypeContract'] ?></p>
        <p>Nombre de comptes actifs : <?php echo $stat['nbAccountActive'] ?></p>
        <p>Nombre de comptes inactifs : <?php echo $stat['nbAccountInactif'] ?></p>
        <p>Nombre de comptes à découvert : <?php echo $stat['nbAccountDecouvert'] ?></p>
        <p>Nombre de comptes non à découvert : <?php echo $stat['nbAccoutNonDecouvert'] ?></p> -->
        
        <div class="statTableWrapper">
            <div class="statCell header">Nombre de clients</div>
            <div class="statCell header">Nombre de comptes</div>
            <div class="statCell header">Nombre de contrats</div>
            <div class="statCell header">Nombre de conseillers</div>
            <div class="statCell header">Nombre d'agents</div>
            <div class="statCell header">Nombre de types de comptes</div>
            <div class="statCell header">Nombre de types de contrats</div>
            <div class="statCell header">Nombre de comptes actifs</div>
            <div class="statCell header">Nombre de comptes inactifs</div>
            <div class="statCell header">Nombre de comptes à découvert</div>
            <div class="statCell header">Nombre de comptes non à découvert</div>

            <div class="statCell content"><?php echo $stat['nbClient'] ?></div>
            <div class="statCell content"><?php echo $stat['nbAccount'] ?></div>
            <div class="statCell content"><?php echo $stat['nbContract'] ?></div>
            <div class="statCell content"><?php echo $stat['nbConseiller'] ?></div>
            <div class="statCell content"><?php echo $stat['nbAgent'] ?></div>
            <div class="statCell content"><?php echo $stat['nbTypeAccount'] ?></div>
            <div class="statCell content"><?php echo $stat['nbTypeContract'] ?></div>
            <div class="statCell content"><?php echo $stat['nbAccountActive'] ?></div>
            <div class="statCell content"><?php echo $stat['nbAccountInactif'] ?></div>
            <div class="statCell content"><?php echo $stat['nbAccountDecouvert'] ?></div>
            <div class="statCell content"><?php echo $stat['nbAccoutNonDecouvert'] ?></div>
        </div>

        <form action="index.php" method="post">
            <label >Date de début </label><input type="date" name="datedebut">
            <label > Date de fin </label><input type="date" name="datefin">
            <input type="submit" name='searchStatClient2'value="Rechercher">
            <p>Nombre de RDV entre deux dates : <?php echo $stat['AppoinmentBetween'] ?></p>
            <p>Nombre de Contrat souscrit entre deux dates : <?php echo $stat['ContractBetween'] ?></p>
        </form>
        <form action="index.php" method="post">
            <label >Date </label>
            <input type="date" name="date">
            <input type="submit" name='searchStatClient1' value="Rechercher">
            <p>Nombre de Client à une date <?php echo $stat['nbClientAt'] ?></p>

        </form>
        <div>
            <form action="index.php" method="post" class="gestionForm">
                <button type="submit" name="GestionPersonnelAllBtn" class="gestionBtn">Gestion du Personnel</button>
                <button type="submit" name="GestionServicesAllBtn" class="gestionBtn">Gestion des Services</button>
            </form>
        </div>
    </div>
</body>
</html>
