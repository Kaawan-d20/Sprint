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
        <h1>Statistiques globales</h1>
        <p></p>
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
            <div class="statCell header">Somme de tous les comptes</div>
            <div class="statCell header">Nombre de contrats actifs</div>
            <div class="statCell header">Nombre de contrats inactifs</div>


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
            <div class="statCell content"><?php echo $stat['sumAccount'] ?></div>
            <div class="statCell content"><?php echo $stat['nbContractActive'] ?></div>
            <div class="statCell content"><?php echo $stat['nbContractInactif'] ?></div>
        </div>
        <p></p>
        

        <div>
            <h1>Nombre de contrat & RDV sur une période</h1>
            <form action="index.php" method="post" class="statForm">
                <input type="date" name="datedebut" class="statDateField"  title="Sélectionner une date de début" required>
                <input type="date" name="datefin" class="statDateField"  title="Sélectionner une date de fin" required>
                <button type="submit" name="searchStatClient2" class="statBtn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Rechercher
                </button>
            </form>
        </div>




        <p></p>
        <div class="statResultTableWrapper">
            <div class="statResultCell header">Nombre de RDV entre deux dates</div>
            <div class="statResultCell content"><?php echo $stat['AppoinmentBetween'] ?></div>
            
            <div class="statResultCell header">Nombre de Contrat souscrit entre deux dates</div>
            <div class="statResultCell content"><?php echo $stat['ContractBetween'] ?></div>
        </div>
        <p></p>




        <div>
            <h1>Nombre de Clients à une date</h1>
            <form action="index.php" method="post" class="statForm">
                <input type="date" name="date" class="statDateField"  title="Sélectionner une semaine">
                <button type="submit" name="searchStatClient1" class="statBtn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Rechercher
                </button>
            </form>
        </div>





        <p></p>
        <div class="statResultTableWrapper">
            <div class="statResultCell header">Nombre de Client à une date</div>

            <div class="statResultCell content"><?php echo $stat['nbClientAt'] ?></div>
        </div>
        <p></p>
        <div>
            <h1>Gestion de la Banque</h1>
            <form action="index.php" method="post" class="gestionForm">
                <button type="submit" name="GestionPersonnelAllBtn" class="gestionBtn">Gestion du Personnel</button>
                <button type="submit" name="GestionServicesAllBtn" class="gestionBtn">Gestion des Services</button>
            </form>
        </div>
    </div>
</body>
</html>
