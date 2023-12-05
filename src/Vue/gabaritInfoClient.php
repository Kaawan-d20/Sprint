<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Info</title>
</head>
<body>
    <!--Message pour Nathan
    Cette partie est a afficher en php dans la vue, car si le client n'a pas de compte, il ne faut pas afficher cette partie
    Mais je te la laisse ici pour que tu puisse faire le css
    -->
    <?php 
        $nameClient = "Hemingway";
        $firstNameClient = "Harold";
        $civi = "Mr";
        $naissance = "02/07/1997";
        $idClient = "023409897867";
        $profession = "Podcaster";
        $situation = "Marié·e·s";
        $creation = "17/11/2022";
        
        $addressClient = "2618 Pheasant Ridge Road";
        $phoneClient = "06 33 60 22 29";
        $emailClient = "harold.hemingway@gmail.com";


    ?>
    <div>
        <h1>Débit / Crédit</h1> 
        <form action="index.php" method="post">
            <select name="debitAccountSelector" id="">
                <?php
                    echo $optionSelect;
                ?>
            </select>
            <input type="number" name="amountInput" id="" min="0" required>
            <input type="submit" value="Débit" name="debitBtn" min="0">
            <input type="submit" value="Crédit" name="creditBtn">
        </form>
    </div>
    <!-- <div>
        <h1>Synthese</h1>
            <p>Identifiant du client : <?php echo $idClient; ?></p>
            <p>Nom du conseiller : <?php echo $nameConseiller; ?></p>
            <p>Nom : <?php echo $nameClient; ?></p>
            <p>Prenom : <?php echo $firstNameClient; ?></p>
            <p>Date de naissance : <?php echo $naissance; ?></p>
            <p>Date de création : <?php echo $creation; ?></p>
            <p>Adresse : <?php echo $addressClient; ?></p>
            <p>Telephone : <?php echo $phoneClient; ?></p>
            <p>Email : <?php echo $emailClient; ?></p>
            <p>Profession : <?php echo $profession; ?></p>
            <p>Situtation familiale : <?php echo $situation; ?></p>
            <p>Civilité : <?php echo $civi; ?></p>
    </div> -->
    <div class="syntheseWrapper">
        <h1>Synthese</h1>
        <div class="nameWrapper">
            <h2><?php echo $civi; ?> <?php echo $firstNameClient; ?> <?php echo $nameClient; ?></h2>
            <span class="pronounsSpan">Il/Lui</span>
            <!-- CHANGE TO A VAR -->
            <div class="idDiv">
                ID: <?php echo $idClient; ?>
            </div>
        </div>
        <div class="infoWrapper">
            <div class="infoHeaderWrapper">
                <div class="infoHeader">Date de naissance</div>
                <div class="infoHeader">Profession</div>
                <div class="infoHeader">Situation familiale</div>
                <div class="infoHeader">Client·e·s depuis</div>
            </div>
            <div class="infoContentWrapper">
                <div class="infoContent"><?php echo $naissance; ?></div>
                <div class="infoContent"><?php echo $profession; ?></div>
                <div class="infoContent"><?php echo $situation; ?></div>
                <div class="infoContent"><?php echo $creation; ?></div>
            </div>
        </div>
        <div class="contactWrapper">
            <h2>Contact:</h2>
            <div class="contactTableWrapper">
                <div class="contactHeaderWrapper">
                    <div class="contactHeader">Addresse : </div>
                    <div class="contactHeader">N° : </div>
                    <div class="contactHeader">Email : </div>
                </div>
                <div class="contactContentWrapper">
                    <div class="contactContent"><?php echo $addressClient; ?></div>
                    <div class="contactContent"><?php echo $phoneClient; ?></div>
                    <div class="contactContent"><?php echo $emailClient; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <h1>Liste des RDV du client</h1>
    </div>
</body>
</html>
