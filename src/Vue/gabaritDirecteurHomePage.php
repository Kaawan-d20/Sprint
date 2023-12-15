<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="light">
    <div class="directeurWrapper">
        <div class="navWrapper">
            <nav>
                <div class="searchWrapper">
                    <form action="index.php" method="post">
                        <label for="searchClientField" class="visually-hidden">Chercher un client</label>
                        <input type="number" name="searchClientByIdField" id="searchClientByIdField" placeholder="Id du client" class="searchField" required="required">
                        <button class="searchButton" name="searchClientBtn" title="Recherche par ID">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
                <div class="advancedSearchandAccountWrapper">
                    <form action="index.php" method="post">
                        <button class="advancedSearchButton" name="advancedSearchBtn" title="Recherche avancée">
                            <i class="fa-regular fa-chart-bar"></i>
                        </button>
                    </form>
                    <div class="dropdown">
                        <button class="accountButton">
                            <i class="fa-solid fa-user"></i>
                            <?php echo $username;?>
                        </button>
                        <div class="dropdownContent">
                            <form action="index.php" method="post">
                                <button class="dropdownButton" onclick="toggleTheme()" type="button" id="themeSwitcherBtn">
                                    Thème
                                    <i class="fa-solid fa-moon" id="themeSwitcherIcon"></i>
                                </button>
                                <button type="submit" class="dropdownButton" name="settingBtn" >
                                Paramètres
                                <i class="fa-solid fa-user-gear"></i>
                            </button>
                                <button class="dropdownButton disconnectionBtn" name="disconnection">
                                    Se déconnecter
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
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
            <input type="submit" name='searchStatClient1' value="Rechercher">
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
    </div>




    <script>
        let isLightTheme = true;
        /** switch beetween light and dark theme */ 
        function toggleTheme() {
            let icon = document.getElementById("themeSwitcherIcon");
            let btn = document.getElementById("themeSwitcherBtn");
            if (isLightTheme) {
                document.body.classList.add("dark");
                document.body.classList.remove("light");
                icon.classList.add("fa-sun")
                icon.classList.remove("fa-moon")
                btn.setAttribute("title", "Activer le thème Clair")

                isLightTheme = false;
            } else {
                document.body.classList.add("light");
                document.body.classList.remove("dark");
                icon.classList.add("fa-moon")
                icon.classList.remove("fa-sun")
                btn.setAttribute("title", "Activer le thème Sombre")

                isLightTheme = true;
            }
        }
    </script>
</body>
</html>