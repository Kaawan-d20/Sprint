<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Agent - Bank</title>
</head>
<body class="light">
<div class="agentWrapper">
    <?php echo $navbar ?>
    <div class="calendarWrapper" id="calendar">
        <div class="calendarNavWrapper">
            <div class="dateBlock">
                <h1>Novembre</h1>
                <span>1812</span>
            </div>
            <?php echo($filterWrapper); ?>
            <div class="weekSelector">
                <form action="index.php" method="post" id="previousWeekForm">
                    <button class="previous" name="weekSelectorPrevious" id="weekSelectorPrevious" title="Semaine Précédente" type="submit">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <input type="date" name="previousWeekDate" id="previousWeekDate" class="hidden">
                </form>
                <form action="index.php" method="post" id="weekSelectorForm" class="weekSelectorForm">
                    <label for="weekSelectorDateField" class="visually-hidden">Sélectionner une Semaine</label>
                    <input type="date" name="weekSelectorDateField" id="weekSelectorDateField" class="weekSelectorDateField"  title="Sélectionner une semaine" onblur="attemptUpdate()">
                    <input type="submit" name="weekSelectorDateBtn" id="weekSelectorDateBtn" class="hidden">
                </form>
                <form action="index.php" method="post">
                    <button class="next" name="weekSelectorNext" title="Semaine Suivante" type="submit" value="next">
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <input type="date" name="nextWeekDate" id="nextWeekDate" class="hidden">
                </form>
            </div>
        </div>
        <div class="weekWrapper">
            <div class="day monday">
                <div class="dayCell">
                    <h1>Lundi</h1>
                    <span>01</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[0]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
            <div class="day tuesday">
                <div class="dayCell">
                    <h1>Mardi</h1>
                    <span>02</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[1]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
            <div class="day wednesday">
                <div class="dayCell">
                    <h1>Mercredi</h1>
                    <span>03</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[2]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
            <div class="day thursday">
                <div class="dayCell">
                    <h1>Jeudi</h1>
                    <span>04</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[3]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
            <div class="day friday">
                <div class="dayCell">
                    <h1>Vendredi</h1>
                    <span>05</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[4]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
            <div class="day saturday">
                <div class="dayCell">
                    <h1>Samedi</h1>
                    <span>06</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[5]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
            <div class="day sunday">
                <div class="dayCell">
                    <h1>Dimanche</h1>
                    <span>07</span>
                </div>
                <div class="events">
                    <?php echo( $weekEvents[6]); ?>
                    <form action="index.php" method="post">
                        <input type="date" name="newRDVdateField" id="newRDVdateField" class="hidden">
                        <button type="submit" class="newRDVbtn" name="newRDVbtn">
                            <i class="fa-solid fa-plus"></i> Ajouter un rendez-vous
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden" id="transmetterJS"><?php echo($dateOfWeek->format('Y-m-d')); ?></div>
</body>
</html>
