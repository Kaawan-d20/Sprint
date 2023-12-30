// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------ Theme ------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


let nomDuCookie = "Theme=";
let decodedCookie = decodeURIComponent(document.cookie);
let listCookie = decodedCookie.split(';');
for(var i = 0; i <listCookie.length; i++) {
    var cookie = listCookie[i];
    while (cookie.charAt(0) == ' ') {
        cookie = cookie.substring(1);
    }
}
let isLightTheme = cookie.substring(nomDuCookie.length, cookie.length) == "light";

/**
 * Fonction qui au chargement de la page va charger le thème en fonction du cookie
 * @returns {void}
 */
function loadTheme() {
    if (isLightTheme) {
        makeLightTheme(document.getElementById("themeSwitcherIcon"), document.getElementById("themeSwitcherBtn"));
    } else {
        makeDarkTheme(document.getElementById("themeSwitcherIcon"), document.getElementById("themeSwitcherBtn"));
    }
}

/**
 * Fonction qui va changer le thème de la page
 * @returns {void}
 */ 
function toggleTheme() {
    let icon = document.getElementById("themeSwitcherIcon");
    let btn = document.getElementById("themeSwitcherBtn");
    if (isLightTheme) {
        makeDarkTheme(icon, btn);
    } else {
        makeLightTheme(icon, btn);
    }
}

/**
 * Fonction qui va changer le thème de la page en thème clair
 * @param {Document} icon L’icône du bouton
 * @param {Document} btn Le bouton
 * @returns {void}
 */
function makeLightTheme(icon, btn) {
    document.body.classList.add("light");
    document.body.classList.remove("dark");
    icon.classList.add("fa-moon")
    icon.classList.remove("fa-sun")
    btn.setAttribute("title", "Activer le thème Sombre")
    
    isLightTheme = true;
    document.cookie = "Theme=light; path=/";
}

/**
 * Fonction qui va changer le thème de la page en thème sombre
 * @param {Document} icon L’icône du bouton
 * @param {Document} btn Le bouton
 * @returns {void}
 */
function makeDarkTheme(icon, btn) {
    document.body.classList.add("dark");
    document.body.classList.remove("light");
    icon.classList.add("fa-sun")
    icon.classList.remove("fa-moon")
    btn.setAttribute("title", "Activer le thème Clair")

    isLightTheme = false;
    document.cookie = "Theme=dark; path=/";
}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------ Connexion --------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


/** Fonction qui envoie le mot de passe haché au serveur
 * @param {string} data L'id du bouton cliqué (si on est sur la page de modification des employés)
 * @returns {void}
*/
function onSubmitButtonClick(data='') {
    if (data==''){
        var formID='formPassword';
        var passwordField='PasswordField';
    }
    else{
        var idEmploye = data.currentTarget.id.replace(/\D/g, ''); // On récupère l'id de l'employé
        var formID='formPassword'+idEmploye;
        var passwordField='PasswordField'+idEmploye;
    }

    // Récupérer le mot de passe depuis le champ de formulaire
    var password = document.getElementById(passwordField).value;
    if (password != '') {
         // Hacher le mot de passe
        var hashedPassword = CryptoJS.SHA256(password).toString();

        // Remplacer le mot de passe dans le champ du formulaire avec le mot de passe haché
        document.getElementById(passwordField).value = hashedPassword;
    }

    // Soumettre le formulaire
    document.getElementById(formID).submit();
}

/**
 * Fonction qui va initialiser le mot de passe
 * @returns {void}
 */
function initPassword() {
    if (document.querySelector('#connectBtn') != null){
        document.getElementById('connectBtn').addEventListener('click', onSubmitButtonClick);
    }
    else{
        list=document.getElementsByName('ModifPersonnelOneBtn');
        for (var i = 0; i < list.length; i++) {
            list[i].addEventListener('click', onSubmitButtonClick.bind(list[i].id));
        }
    }
}

window.addEventListener('load', initPassword);

/**
 * Fonction qui va afficher ou cacher le mot de passe
 * @param {string} password L'id du champ de mot de passe
 * @returns {void}
 */
function togglePasswordVisibility(password) {
    let passwordField = document.getElementById(password);
    let icon = document.getElementById("visibilityIcon");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }   
}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------ Synthèse client --------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


/**
 * Fonction qui va afficher ou cacher les opérations des comptes sur la page de synthèse client
 * @param {string} filterBtn L'id du bouton de filtre cliqué
 * @returns {void}
 */
function toggleFilter(filterBtn) {
    if (filterBtn == null) {
        return;
    }
    let oldIcon = currentFilter.children[0];
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

/**
 * Fonction qui va initialiser le filtre de la page de synthèse client
 * @returns {void}
 */
function initToggleFilter() {
    if (document.querySelector("#operationsFilterWrapper") == null) { // Si on est pas sur la page de synthèse client
        return;
    }
    currentFilter = document.getElementById("operationsFilterWrapper").children[0];
    toggleFilter(currentFilter);
}

window.addEventListener('load', initToggleFilter);


// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------ Prise de rendez-vous ---------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


let isAdmin = false;
/**
 * Fonction qui va afficher ou cacher des éléments si c'est un rendez-vous ou une tache administrative
 * @returns {void} 
 */
function toggleTA() {
    isAdmin = ! isAdmin ;
    if (isAdmin) {
        document.querySelectorAll(".admin").forEach(function (item) {
            item.classList.remove("hidden")
        });
        document.querySelectorAll(".appointement").forEach(function (item) { // Faute d'orthographe dans le nom de la classe
            item.classList.add("hidden")
            item.removeAttribute("required")
        });
    } else {
        document.querySelectorAll(".appointement").forEach(function (item) { // Faute d'orthographe dans le nom de la classe
            item.classList.remove("hidden")
            item.setAttribute("required", "")
        });
        document.querySelectorAll(".admin").forEach(function (item) {
            item.classList.add("hidden")
        });
    }
}

/**
 * Fonction qui change la valeur du champ de conseiller en fonction du client choisi
 * @param {*} input L'id de l'input
 */
function changeConseiller(input) {
    let nbClient = document.getElementById(input.id).value;
    let option = document.getElementById(nbClient);
    let value = option.dataset.conseiller;
    document.getElementById("appointementsConseillerField").value = value;
}


// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------ Calendrier -------------------------------------------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


window.addEventListener('load', initCalendar);

/**
 * Fonction qui va initialiser le calendrier pour l'agent et le conseiller
 * @returns {void}
 */
function initCalendar(){
    if (document.querySelector("#calendar") == null) { // Si on est pas sur la page du calendrier
        return;
    }
    selectedFilters = [];

    globalCurrentDate = new Date(document.getElementById("transmetteurJS").textContent);
    document.getElementById("weekSelectorDateField").value =dateToString(globalCurrentDate);
    
    datePreviousWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));
    document.getElementById("previousWeekDate").value = dateToString(datePreviousWeek);
    
    dateNextWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() + 14)));
    document.getElementById("nextWeekDate").value =dateToString(dateNextWeek);
    
    globalCurrentDate = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));

    correspondingMonth = [
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

    updateCalendar(globalCurrentDate);
    if (document.querySelector("#transmetteurJS2") != null) {
        let name = document.getElementById("transmetteurJS2").textContent;
        currentFilter = document.getElementById(name);
        filterToggle(currentFilter);
    }
}

/**
 * Fonction qui transforme une date en string au format "yyyy-mm-dd"
 * @param {Date} globalCurrentDate Une date 
 * @returns {string} Une date sous forme de string au format "yyyy-mm-dd"
 */ 
function dateToString(globalCurrentDate) {
    return (globalCurrentDate.getFullYear() 
    + "-" + ((globalCurrentDate.getMonth() <= 8) ? "0" : "") 
    + (globalCurrentDate.getMonth() + 1) + "-" 
    + ((globalCurrentDate.getDate() <= 9) ? "0" : "") + globalCurrentDate.getDate());
}

/**
 * Fonction qui modifie les bouton de filtre et appel une autre fonction qui va afficher ou de cacher les événements sur le calendrier
 * @param {*} filterBtn Le bouton de filtre cliqué
 * @returns {void} 
 */
function filterToggle(filterBtn) {
    if (filterBtn == null) {
        return;
    }
    let icon = filterBtn.childNodes[0];
    if (filterBtn.classList.value.includes("inactive")) {

        filterBtn.setAttribute("title", "Désélectionner " + filterBtn.textContent);

        filterBtn.classList.remove("inactive");

        icon.classList.add("fa-square-check")
        icon.classList.remove("fa-square")

        selectedFilters.push(filterBtn.dataset.conseiller);
    }
    else {
        filterBtn.setAttribute("title", "Sélectionner " + filterBtn.textContent);

        filterBtn.classList.add("inactive");

        icon.classList.add("fa-square")
        icon.classList.remove("fa-user-square-check")

        selectedFilters = selectedFilters.filter(item => item !== filterBtn.dataset.conseiller)
    }

    filterEvents();  
}

/**
 * Fonction qui va cacher l'élément passé en paramètre
 * @param {*} eventHTML L'élément à cacher
 * @returns {void}
 */
function hide (eventHTML) {
    eventHTML.classList.add("hidden")
}

/**
 * Fonction qui va afficher l'élément passé en paramètre
 * @param {*} eventHTML L'élément à afficher
 * @returns {void}
 */
function show (eventHTML) {
    eventHTML.classList.remove("hidden");
}

/**
 * Fonction qui va cacher ou afficher les événements en fonction des filtres sélectionnés
 * @returns {void}
 */
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

/**
 * Fonction qui va mettre à jour les spans des cellules du calendrier avec les numéros des jours de la semaine
 * @param {Array} week Un tableau de 7 string, correspondant au numéro de chaque jour de la semaine
 * @returns {void}
 */
function setDayCellSpan (week) {
    let spanToFill = document.querySelectorAll(".day .dayCell span")
    for (let i = 0; i < 7; i++) {
        spanToFill[i].textContent = week[i];
    }
}

/**
 * Fonction qui va mettre à jour les champs cachés du formulaire avec les dates de la semaine
 * @param {Array} week Un tableau de 7 string, correspondant au numéro de chaque jour de la semaine
 * @returns {void}
 */
function setHiddenDateField (week) {
    var dateFieldToFill = document.querySelectorAll("#newRDVdateField");
    for (let i = 0; i < 7; i++) {
        dateFieldToFill[i].value = week[i];
    }
}

/**
 * Fonction qui va mettre à jour le titre du calendrier avec le mois et l'année
 * @param {Date} currentDate La date actuelle
 */
function updateDateTitle(currentDate) {
    document.querySelector(".dateBlock h1").textContent = correspondingMonth[currentDate.getMonth()];
    document.querySelector(".dateBlock span").textContent = currentDate.getFullYear();
}

/**
 * Fonction qui va mettre à jour le calendrier avec la date passée en paramètre
 * @param {Date} currentDate La date actuelle
 * @returns {void}
 */
function updateCalendar(currentDate) {
    updateDateTitle(currentDate);
    currentDateCopy = new Date(currentDate); // On fait une copie de la date pour ne pas modifier la date actuelle
    setDayCellSpan(getWeekArray(currentDate));
    setHiddenDateField(getWeekArrayFullDate(currentDateCopy));
}

/**
 * Fonction qui va faire un tableau de 7 string, correspondant au numéro de chaque jour de la semaine
 * @param {Date} mondayDate La date du lundi de la semaine
 * @returns {Array} Un tableau de 7 string, correspondant au numéro de chaque jour de la semaine
 */
function getWeekArray(mondayDate) {
    let weekArray = [];
    let currentDate = mondayDate;
    for (let i = 0; i < 7; i++) {
        let currentDay = currentDate.getDate().toString();
        weekArray.push((currentDay.length < 2) ? '0' + currentDay :currentDay);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    return (weekArray);
}

/**
 * Fonction qui va faire un tableau de 7 string, correspondant à la date complète de chaque jour de la semaine
 * @param {*} mondayDate La date du lundi de la semaine
 * @returns {Array} Un tableau de 7 string, correspondant à la date complète de chaque jour de la semaine
 */
function getWeekArrayFullDate(mondayDate) {
    let weekArray = [];
    let currentDate = mondayDate;
    for (let i = 0; i < 7; i++) {
        let currentDay = currentDate.getDate().toString();
        currentDay = (currentDay.length < 2) ? '0' + currentDay : currentDay;
        let currentMonth = (currentDate.getMonth() +1 ).toString();
        currentMonth = (currentMonth.length < 2) ? '0' + currentMonth : currentMonth;
        let currentYear = currentDate.getFullYear().toString();
        let currentFullDate = currentYear + "-" + currentMonth + '-' + currentDay;
        weekArray.push(currentFullDate);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    return (weekArray);
}









/*
POUBELLE




function interdireRetour() {
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
}

function generateSingleFilter(name, colorClass) {
    let filterBtn = document.createElement("button");
    filterBtn.classList.add("filterBtn", "inactive", colorClass);

    filterBtn.setAttribute("onclick", "filterToggle(this)");
    filterBtn.setAttribute("title", "Sélectionner " + name);

    let icon = document.createElement("i");
    icon.classList.add("fa-regular", "fa-square")

    filterBtn.appendChild(icon)
    filterBtn.appendChild(document.createTextNode(name));

    filterBtn.dataset.conseiller = name;

    return filterBtn;
} 

function generateFilters () {
    let filterWrapper = document.createElement("div");
    filterWrapper.classList.add("filterWrapper");
    let conseillers = [];
    let conseillerColors = [];
    document.querySelectorAll(".event").forEach(event => {
        if (! conseillers.includes(event.dataset.conseiller)) {
            conseillers.push(event.dataset.conseiller);
            conseillerColors[event.dataset.conseiller] = event.dataset.color;
        }
    })
    conseillers.forEach((conseiller) => {
        let filterBtn = generateSingleFilter(conseiller, conseillerColors[conseiller]);
        filterWrapper.appendChild(filterBtn);
    })
    document.querySelector(".calendarNavWrapper").insertBefore(filterWrapper, document.querySelector(".weekSelector"))
}

function attemptUpdate() {
    let weekSelectorInput = document.getElementById("weekSelectorDateField");
    let attemptedDate = new Date(weekSelectorInput.value);
    if (attemptedDate.getFullYear() > 200 && attemptedDate.getFullYear() < 20000) {
        document.getElementById("weekSelectorForm").submit();
    }
}


*/