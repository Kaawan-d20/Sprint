<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info</title>
</head>
<body class="light">
<?php echo $navbar ?>
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
                    <input type="text" class="contactCell content specialBorder" name="addressClient" id="addressClient" value="<?php echo $addressClient; ?>">
                    
                    <div class="contactCell header">N° : </div>
                    <input type="tel" class="contactCell content" name="phoneClient" id="phoneClient" pattern="((\+|00)?[1-9]{2}|0)[1-9]( ?[0-9]){8}" value="<?php echo $phoneClient; ?>">
                    
                    <div class="contactCell header">Email : </div>
                    <input type="email" class="contactCell content" name="emailClient" id="emailClient" value="<?php echo $emailClient; ?>">
                    
                    <div class="contactCell header">Conseiller : </div>
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
                        <input type="number" placeholder="Entrez un montant" name="amountInput" id="amountInput" class="amountInput" min="0" required>
                        €
                    </div>
                    <input type="submit" value="- Débit" name="debitBtn" min="0" class="debitCreditBtn">
                    <input type="submit" value="+ Crédit" name="creditBtn" class="debitCreditBtn">
                </div>
            </form>
            <?php echo $createAccount;?>  
            <div class="accountTableWrapper">
                    <div class="accountCell header">Compte</div>
                    <div class="accountCell header">Solde</div>
                    <div class="accountCell header">Decouvert </div>
                    <?php echo $listA ?>
            </div>
        </div>
        <div class="contractSection">
            <h1>Contrats:</h1>
            <?php echo $createContract;?>           
            <div class="contractTableWrapper">
                    <div class="accountCell header">Contrat</div>
                    <div class="accountCell header">Tarif Mensuel</div>
                    <?php echo $listC ?>
            </div>
        </div>
    </div>
    <div class="accountOperationsWrapper">
        <div class="filterWrapper" id="operationsFilterWrapper">
            <?php echo $filterBtns; ?>
        </div>
        <?php echo $operationDisplay; ?>
    </div>
</div>
<script>
let currentFilter = document.getElementById("operationsFilterWrapper").children[0];
toggleFilter(currentFilter);

function toggleFilter(filterBtn) {
    let oldIcon =currentFilter.children[0];
    let icon = filterBtn.children[0];

    oldIcon.classList.remove("fa-circle-dot");
    oldIcon.classList.add("fa-circle");
    currentFilter.classList.add("inactive");

    icon.classList.remove("fa-circle");
    icon.classList.add("fa-circle-dot");
    filterBtn.classList.remove("inactive");

    let oldWrapper = document.getElementById("account"+currentFilter.dataset.id);
    oldWrapper.classList.add("hidden");
    let newWrapper = document.getElementById("account"+filterBtn.dataset.id);
    newWrapper.classList.remove("hidden");

    currentFilter = filterBtn;
}
</script>
</body>
</html>