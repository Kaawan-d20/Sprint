<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Document</title>
</head>
<body class="dark">
    <div class="conseillerWrapper">
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
                                    <button class="dropdownButton">
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
        </div>

        <script>
            let isLightTheme = false;
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