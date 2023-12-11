<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/31ad525f9a.js" crossorigin="anonymous"></script>
    <title>Accueil</title>
</head>
<body class="dark">
<div class="agentWrapper">
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
                            <!-- <?php $user?> -->
                        <i class="fa-solid fa-user"></i>
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
    <div class="calendarWrapper">
        <div class="calendarNavWrapper">
            <div class="dateBlock">
                <h1>Novembre</h1>
                <span>1812</span>
            </div>
            <div class="weekSelector">
                <form action="index.php" method="post" id="previousWeekForm">
                    <button class="previous" name="weekSelectorPrevious" id="weekSelectorPrevious" title="Semaine Precedente" type="submit">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <input type="date" name="previousWeekDate" id="previousWeekDate" class="hidden">
                </form>
                <form action="index.php" method="post" id="weekSelectorForm">
                    <label for="weekSelectorDateField" class="visually-hidden">Selectionner une Semaine</label>
                    <input type="date" name="weekSelectorDateField" id="weekSelectorDateField" class="weekSelectorDateField"  title="Selectionner une semaine" onblur="attemptUpdate()">
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
                <div class="events"></div>
            </div>
            <div class="day tuesday">
                <div class="dayCell">
                    <h1>Mardi</h1>
                    <span>02</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day wednesday">
                <div class="dayCell">
                    <h1>Mercredi</h1>
                    <span>03</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day thursday">
                <div class="dayCell">
                    <h1>Jeudi</h1>
                    <span>04</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day friday">
                <div class="dayCell">
                    <h1>Vendredi</h1>
                    <span>05</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day saturday">
                <div class="dayCell">
                    <h1>Samedi</h1>
                    <span>06</span>
                </div>
                <div class="events"></div>
            </div>
            <div class="day sunday">
                <div class="dayCell">
                    <h1>Dimanche</h1>
                    <span>07</span>
                </div>
                <div class="events"></div>
            </div>
        </div>
    </div>
</div>
<script>


let conseillersArray = []; // this is bullshit, this is juste every conseiller in form of an array, because JS is bullshit (no it's not, I just dont know it that well)
let conseillersDict = [];
let selectedFilters = [];
let globalCurrentDate = new Date( "<?php echo($dateOfWeek); ?>" );
document.getElementById("weekSelectorDateField").value =dateToString(globalCurrentDate);
console.log(globalCurrentDate);

let datePreviousWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));
document.getElementById("previousWeekDate").value =dateToString(datePreviousWeek);

let dateNextWeek = console.log(new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() + 14)));
document.getElementById("nextWeekDate").value =dateToString(dateNextWeek);

globalCurrentDate = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));

let isLightTheme = false;

let correspondingMonth = [
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juillet",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre"
];


function toggleTheme() {
    console.log(isLightTheme);
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
    console.log(isLightTheme);
}
/** returns a Date as a string "yyyy-mm-dd" */ 
function dateToString(date) {
    return (globalCurrentDate.getFullYear() 
    + "-" + ((globalCurrentDate.getMonth() <= 8) ? "0" : "") 
    + (globalCurrentDate.getMonth() + 1) + "-" 
    + ((globalCurrentDate.getDate() <= 9) ? "0" : "") + globalCurrentDate.getDate());
}

/** generate a single filter button, and returns it designed to be used in the generateFilters() function */
function generateSingleFilter(name, colorClass) {
    let filterBtn = document.createElement("button");
    filterBtn.classList.add("filterBtn", "inactive", colorClass);

    filterBtn.setAttribute("onclick", "filterToggle(this)");
    filterBtn.setAttribute("title", "Selectionner " + name);

    let icon = document.createElement("i");
    icon.classList.add("fa-regular", "fa-square")

    filterBtn.appendChild(icon)
    filterBtn.appendChild(document.createTextNode(name));

    filterBtn.dataset.conseiller = name;

    return filterBtn;
} 

/** generates the filters, must be called AFTER the events are all stored in the board */ 
function generateFilters (){
    let filterWrapper = document.createElement("div");
    filterWrapper.classList.add("filterWrapper");

    conseillersDict.forEach((conseiller) => {
        let filterBtn = generateSingleFilter(conseiller.key, conseiller.value);
        filterWrapper.appendChild(filterBtn);
    })
    document.querySelector(".calendarNavWrapper").insertBefore(filterWrapper, document.querySelector(".weekSelector"))
}

function filterToggle(filterBtn) {
    let icon = filterBtn.childNodes[0];
    if (filterBtn.classList.value.includes("inactive")) {

        filterBtn.setAttribute("title", "Deselectionner " + filterBtn.textContent);

        // filterBtn.classList.add("active");
        filterBtn.classList.remove("inactive");

        icon.classList.add("fa-square-check")
        icon.classList.remove("fa-square")

        selectedFilters.push(filterBtn.dataset.conseiller);
    }
    else {
        filterBtn.setAttribute("title", "Selectionner " + filterBtn.textContent);

        filterBtn.classList.add("inactive");
        // filterBtn.classList.remove("active");

        icon.classList.add("fa-square")
        icon.classList.remove("fa-user-square-check")

        selectedFilters = selectedFilters.filter(item => item !== filterBtn.dataset.conseiller) // GOD THIS IS UGLY
        // This is basically selectedFilters.remove(filterBtn.dataset.conseiller), I hate that this is not an option
    }

    filterEvents();  
}

function hide (eventHTML) {
    eventHTML.classList.add("hidden")
}

function show (eventHTML) {
    eventHTML.classList.remove("hidden");
}

function filterEvents() {
    if (selectedFilters.length == 0) {
        // No button selected, show everything
        document.querySelectorAll(".event.hidden").forEach((event) => {
            show(event);
        })
    }
    else {
        document.querySelectorAll(".event").forEach((event) => {
            if (selectedFilters.includes(event.dataset.conseiller)) {
                show(event);
            } else {
                hide(event);
            }
        })
    }
}

/** takes in a week array of seven string, corresponding to the number of each day of the week. /!\ week start on monday because we are in france */ 
function setdayCellSpan (week) {
    let spanToFill = document.querySelectorAll(".day .dayCell span")
    for (let i = 0; i < 7; i++) {
        spanToFill[i].textContent = week[i]
    }
}

function updateDateTitle(currentDate) {
    // console.log(currentDate instanceof Date)
    document.querySelector(".dateBlock h1").textContent = correspondingMonth[currentDate.getMonth()]
    document.querySelector(".dateBlock span").textContent = currentDate.getFullYear();
}

function updateCalendar (currentDate) {
    updateDateTitle(currentDate);
    setdayCellSpan(getWeekArray(currentDate));
}

function getWeekArray(mondayDate) {
    let weekArray = [];
    for (let i = 0; i < 7; i++) {
        let currentday = currentDate.getDate().toString();
        weekArray.push((currentday.length < 2) ? '0' + currentday :currentday);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    return (weekArray);
}

/** when is a string taking "next|previous|get" */ 
// function updateCurrentDate(when) {
//     if (when === "get")

// }

function attemptUpdate() {
    let weekSelectorInput = document.getElementById("weekSelectorDateField");
    let attemptedDate = new Date(weekSelectorInput.value);
    if (attemptedDate.getFullYear() > 200 && attemptedDate.getFullYear() < 20000) {
        document.getElementById("weekSelectorForm").submit();
    }
}

// function nextWeek() {
//     updateCurrentDate();
// }

// function previousWeek() {
//     updateCurrentDate();
// }

generateFilters();
updateCalendar(globalCurrentDate);


</script>
</body>
</html>
<!--                <div class="event">
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