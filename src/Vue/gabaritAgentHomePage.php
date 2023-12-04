<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/31ad525f9a.js" crossorigin="anonymous"></script>
    <title>Accueil</title>
</head>
<body>
<div class="agentWrapper">
    <div class="navWrapper">
        <nav>
            <div class="searchWrapper">
                <form action="index.php" method="post">
                    <label for="searchClientField" class="visually-hidden">Chercher un client</label>
                    <input type="number" name="searchClientByIdField" id="searchClientByIdField" placeholder="Id du client" class="searchField" required="required">
                    <button class="searchButton" name="searchClientBtn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="advancedSearchandAccountWrapper">
                <form action="index.php" method="post">
                    <button class="advancedSearchButton" name="advancedSearchBtn">
                        <i class="fa-regular fa-chart-bar"></i>
                    </button>
                </form>
                <div class="dropdown">
                    <button class="accountButton">
                            Username
                            <!-- <?php $user?> -->
                        <i class="fa-solid fa-user"></i>
                    </button>
                    <div class="dropdownContent">
                        <form action="index.php" method="post">
                            <button class="dropdownButton">
                                Theme
                                <i class="fa-solid fa-moon"></i>
                            </button>
                            <button class="dropdownButton">
                                Parametres
                                <i class="fa-solid fa-user-gear"></i>
                            </button>
                            <button class="dropdownButton" name="disconnection">
                                Se deconnecter
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="calendarWrapper">
        <div class="calendarNavWrapper">
            <div class="dateBlock">
                <h1>Decembre</h1>
                <span>03</span>
            </div>
            <div class="weekSelector">
                <button class="previous">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <label for="weekSelectorDateField" class="visually-hidden">Selectionner une Semaine</label>
                <input type="date" name="weekSelectorDateField" id="weekSelectorDateField" class="weekSelectorDateField">
                <button class="next">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
        <div class="weekWrapper">
            <div class="day monday">
                <div class="dayCell">
                    <h1>Lundi</h1>
                    <span>27</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day tuesday">
                <div class="dayCell">
                    <h1>Mardi</h1>
                    <span>28</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day wednesday">
                <div class="dayCell">
                    <h1>Mercredi</h1>
                    <span>29</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day thursday">
                <div class="dayCell">
                    <h1>Jeudi</h1>
                    <span>30</span>
                </div>
                <div class="events">                </div>
            </div>
            <div class="day friday">
                <div class="dayCell">
                    <h1>Vendredi</h1>
                    <span>01</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day saturday">
                <div class="dayCell">
                    <h1>Samedi</h1>
                    <span>02</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day sunday">
                <div class="dayCell">
                    <h1>Dimanche</h1>
                    <span>03</span>
                </div>
                <div class="events"></div>
            </div>
        </div>
    </div>
</div>
<script>
    let rendezVous = <?php echo json_encode($appointments); ?>;
    console.log(rendezVous);
</script>
</body>
</html>
<!--                     <div class="event">
                        <h2>Ouverture de Compte</h2>
                        <p>Mx Elise Johansson</p>
                        <span class="pronounsSpan">elle/elle</span>
                        <div class="eventDetails">
                            <div>
                                <p class="eventStartTime">12:40</p>
                                <p class="eventEndTime">13:40</p>
                            </div>
                            <div class="eventConseiller" style="background-color: #F2542D;">
                                <i class="fa-solid fa-user-tie"></i>
                                Alexander
                            </div>
                        </div>
                    </div> -->