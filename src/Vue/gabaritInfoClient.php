<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info</title>
</head>
<body>
    <!--Message pour Nathan
    Cette partie est a afficher en php dans la vue, car si le client n'a pas de compte, il ne faut pas afficher cette partie
    Mais je te la laisse ici pour que tu puisse faire le css
    -->
    <div>
        <h1>Débit / Crédit</h1> 
        <form action="index.php" method="post">
            <select name="debitAccountSelector" id="">
                <?php
                    echo $optionSelect;
                ?>
            </select>
            <input type="number" name="amountInput" id="" required>
            <input type="submit" value="Débit" name="debitBtn">
            <input type="submit" value="Crédit" name="creditBtn">
        </form>
    </div>
    <div>
        
        <h1>Synthese</h1>
        <p>Identifiant du client : <?php echo $idClient; ?></p>
        <p>Nom du conseiller : <?php echo $nameConseiller; ?></p>
        <p>Nom : <?php echo $nameClient; ?></p>
        <p>Date de naissance : <?php echo $naissance; ?></p>
        <p>Date de création : <?php echo $creation; ?></p>
        <p>Prenom : <?php echo $firstNameClient; ?></p>
        <p>Adresse : <?php echo $addressClient; ?></p>
        <p>Telephone : <?php echo $phoneClient; ?></p>
        <p>Email : <?php echo $emailClient; ?></p>
        <p>Profession : <?php echo $profession; ?></p>
        <p>Situtation familliale : <?php echo $situation; ?></p>
        <p>Civilité : <?php echo $civi; ?></p>
        <p>++rdv client, debit/credit UI</p>
    </div>
    <div>
        <h1>Liste des RDV du client</h1>
    </div>
</body>
</html>
