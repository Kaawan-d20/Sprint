<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Info</title>
</head>
<body class="light"> <!-- TODO: use session to choose beetween light or dark -->
    <?php 
        $events = [["Ouverture Compte", "28/12/2023", "14h30", "15h30", "lush-green", "Bertrand"], ["Signature Contrat", "28/12/2023","15h30", "15h45", "lush-green", "Bertrand"]]
    ?>
<div class="syntheseWrapper">
        <div class="nameWrapper">
            <h1><?php echo $civi; ?> <?php echo $firstNameClient; ?> <?php echo $nameClient; ?></h1>
            <div class="idDiv">ID: <?php echo $idClient; ?></div>
        </div>
    <form action="index.php" method="post">
        <div class="infoTableWrapper">
            <div class="infoCell header">Date de naissance</div>
            <div class="infoCell header">Profession</div>
            <div class="infoCell header">Situation familiale</div>
            <div class="infoCell header">Client·e depuis</div>
            
            <input type="date" class="infoCell content" id="naissance" name="naissance" value="<?php echo $naissance; ?>">
            <input type="text" class="infoCell content" id="profession" name="profession" value="<?php echo $profession; ?>">
            <input type="text" class="infoCell content" id="situation" name="situation" value="<?php echo $situation; ?>">
            <input type="date" class="infoCell content" id="creation" name="creation" disabled="disabled" value="<?php echo $creation; ?>">
        </div>
        <div id="contactAndRDVWrapper" class="contactAndRDVWrapper">
            <div class="contactWrapper">
                <h1>Contact:</h1>
                <div class="contactTableWrapper">
                    <div class="contactCell header">Adresse : </div>
                    <div class="contactCell header">N° : </div>
                    <div class="contactCell header">Email : </div>
                    <div class="contactCell header">Conseiller : </div>
                    
                    <input type="text" class="contactCell content" name="addressClient" id="addressClient" value="<?php echo $addressClient; ?>">
                    <input type="tel" class="contactCell content" name="phoneClient" id="phoneClient" pattern="((\+|00)?[1-9]{2}|0)[1-9]( ?[0-9]){8}" value="<?php echo $phoneClient; ?>">
                    <input type="email" class="contactCell content" name="emailClient" id="emailClient" value="<?php echo $emailClient; ?>">
                    <input type="text" class="contactCell content" name="nameConseiller" id="nameConseiller" value="<?php echo $nameConseiller; ?>">
                </div>
            </div>
            <div class="RDVSectionWrapper">
                <h1>Liste des RDV du client:</h1>
                <div class="RDVWrapper">

                </div>
            </div>
        </div>
    </form>
    <div class="accountAndContractWrapper">
        <div class="accountSection">
            <form action="index.php" method="post" class="debitCreditWrapper">
                <div class="accountSelectorWrapper">
                    <h1>Compte:</h1>
                    <select name="debitAccountSelector" id="debitAccountSelector" class="debitAccountSelector">
                            <?php
                                echo $optionSelect;
                            ?>
                    </select>
                </div>
                <div class="debitCreditBtnWrapper">
                    <div class="amountInputWrapper">
                        <input type="number" name="amountInput" id="amountInput" class="amountInput" min="0" required>
                        €
                    </div>
                    <input type="submit" value="- Débit" name="debitBtn" min="0" class="debitCreditBtn">
                    <input type="submit" value="+ Crédit" name="creditBtn" class="debitCreditBtn">
                </div>
            </form>
            <div class="accountTableWrapper">
                    <div class="accountCell Header">Compte</div>
                    <div class="accountCell Header">Solde</div>
                    <div class="accountCell Header">Decouvert </div>
                    <!-- <?php echo $listA ?> -->
                    <div class="accountCell content">$account->intitule</div>
                    <div class="accountCell content">$account->solde</div>
                    <div class="accountCell content">$account->decouvert</div>

                    <div class="accountCell content">$account->intitule</div>
                    <div class="accountCell content">$account->solde</div>
                    <div class="accountCell content">$account->decouvert</div>
                </div>
        </div>
        <div class="contractSection">
            <h1>Contrats:</h1>
            <?php echo($listC); ?>
        </div>

    </div>
    <div class="releveCompte">
        <?php echo $content; ?>
    </div>
</div>
<script>
function displayOperations() {
    if (typeof accountSelected == 'undefined'){
        previous = "1";
    }
    else{
        previous = accountSelected;
    }
    console.log(previous)
    accountSelected = document.getElementById('comptes').value;
    console.log(document.getElementById('comptes').value)
    document.getElementById(previous).classList.add("hidden");
    document.getElementById(accountSelected).classList.remove("hidden");

}
displayOperations();
</script>
</body>
</html>