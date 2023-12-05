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
                <button class="previous" title="Semaine Precedente">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <label for="weekSelectorDateField" class="visually-hidden">Selectionner une Semaine</label>
                <input type="date" name="weekSelectorDateField" id="weekSelectorDateField" class="weekSelectorDateField"  title="Selectionner une semaine">
                <button class="next" title="Semaine Suivante">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
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


/** create an event div, with every field filled then returns it */ 
function createEvent(motif, client, civilitee, pronoms, horaireDebut, horaireFin, conseillerColor, conseiller) {
    let eventHTML = document.createElement("div");
    eventHTML.classList.add("event");

    eventHTML.dataset.conseiller = conseiller
    


    if (!(conseillersArray.includes(conseiller))) {
        conseillersArray.push(conseiller);
        conseillersDict.push({
            key: conseiller,
            value: conseillerColor,
        });
    }

    console.log(conseillersArray)

    let eventTitle = document.createElement("h2");
    eventTitle.appendChild(document.createTextNode(motif));
    eventHTML.appendChild(eventTitle);

    let eventClient = document.createElement("p");
    eventClient.appendChild(document.createTextNode(civilitee + " " + client));
    eventHTML.appendChild(eventClient);

    let pronounsSpan = document.createElement("span");
    pronounsSpan.classList.add("pronounsSpan");
    pronounsSpan.appendChild(document.createTextNode(pronoms));
    eventHTML.appendChild(pronounsSpan);

    let eventDetails = document.createElement("div");
    eventDetails.classList.add("eventDetails");

    let timeDiv = document.createElement("div");

    let eventStartTime = document.createElement("p");
    // eventStartTime.classList.add("eventStartTime");
    eventStartTime.appendChild(document.createTextNode(horaireDebut));
    timeDiv.appendChild(eventStartTime);

    let eventEndTime = document.createElement("p");
    // eventEndTime.classList.add("eventEndTime");
    eventEndTime.appendChild(document.createTextNode(horaireFin));
    timeDiv.appendChild(eventEndTime);

    eventDetails.appendChild(timeDiv);

    let eventConseiller = document.createElement("div");
    eventConseiller.classList.add("eventConseiller");
    eventConseiller.classList.add(conseillerColor);

    let userTieIcon = document.createElement("i");
    userTieIcon.classList.add("fa-solid", "fa-user-tie");
    eventConseiller.appendChild(userTieIcon);

    eventConseiller.appendChild(document.createTextNode(conseiller));
    

    eventDetails.appendChild(eventConseiller);

    eventHTML.appendChild(eventDetails);

    return eventHTML;
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
    console.log(eventHTML, "is now hidden");
    eventHTML.classList.add("hidden")
}

function show (eventHTML) {
    console.log(eventHTML, "is now shown");
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
    console.log("Yes")
    let spanToFill = document.querySelectorAll(".day .dayCell span")
    for (let i = 0; i < 7; i++) {
        spanToFill[i].textContent = week[i]
    }
}


document.querySelector(".tuesday .events").appendChild(createEvent(
    "Signature de Contrat",
    "Harold Hemmingway",
    "Mr",
    "Il/Lui",
    "09h45",
    "10h30",
    "turquoise-cyan",
    "Eleanor"
))

document.querySelector(".monday .events").appendChild(createEvent(
    "Fermeture de livret-A",
    "Akira Mashima",
    "Mx",
    "Iel/Iel",
    "14h30",
    "16h30",
    "berry-red",
    "Ernest"
))

document.querySelector(".tuesday .events").appendChild(createEvent(
    "Signature du Contrat d'Assurance",
    "Ornella Dupré",
    "Mme",
    "Elle/elle",
    "17h30",
    "18h00",
    "lavender",
    "Eliza"
))

generateFilters()
setdayCellSpan(["04", "05", "06", "07", "08", "09", "10"])

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