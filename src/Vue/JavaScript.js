



let nom_du_cookie = "Theme=";
let decodedCookie = decodeURIComponent(document.cookie);
let ca = decodedCookie.split(';');
for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
        c = c.substring(1);
    }
    // if (c.indexOf(nom_du_cookie) == 0) {
    //     console.log(c.substring(nom_du_cookie.length, c.length));
    // }
}
let isLightTheme = c.substring(nom_du_cookie.length, c.length) == "light";

/**
 * Fonction qui au chargement de la page va charger le thème en fonction du cookie
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
 * Focntion qui va changer le thème de la page en thème clair
 * @param {Document} icon l'icone du bouton
 * @param {Document} btn le bouton
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
 * Focntion qui va changer le thème de la page en thème sombre
 * @param {Document} icon l'icone du bouton
 * @param {Document} btn le bouton
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



/** envoie le mot de passe haché au serveur
 * @param {string} passwordField l'id du champ de mot de passe
 * @param {string} submitBtn l'id du bouton submit
*/
function sent(passwordField, submitBtn) {
    let password = document.getElementById(passwordField).value;
    password = CryptoJS.SHA256(password).toString();
    document.getElementById(passwordField).value = password;
    document.getElementById(submitBtn).click();
}





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





//Synthèse client 


function toggleFilter(filterBtn) {
    if (filterBtn == null) {
        return;
    }
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



function initToggleFilter() {
    if (document.querySelector("#operationsFilterWrapper") == null) {
        return;
    }
    currentFilter = document.getElementById("operationsFilterWrapper").children[0];
    toggleFilter(currentFilter);
}


window.addEventListener('load', initToggleFilter);




//Prise de TA

let isAdmin = false;
function toggleTA() {
    isAdmin = ! isAdmin ;
    if (isAdmin) {
        document.querySelectorAll(".admin").forEach(function (item) {
            console.log(item);
            item.classList.remove("hidden")
        });
        document.querySelectorAll(".appointement").forEach(function (item) {
            item.classList.add("hidden")
            item.removeAttribute("required")
        });
    } else {
        document.querySelectorAll(".appointement").forEach(function (item) {
            item.classList.remove("hidden")
            item.setAttribute("required", "")
        });
        document.querySelectorAll(".admin").forEach(function (item) {
            item.classList.add("hidden")
        });
    }
}


//Prise de RDV
function changeConseiller(select) {
    let nbClient = document.getElementById(select.id).value;
    //let option =select.options[select.selectedIndex];
    let option = document.getElementById(nbClient);
    console.log(option);
    let value = option.dataset.conseiller;
    console.log(value);
    document.getElementById("appointementsConseillerField").value = value;
}








/*
POUBELLE
<!-- <script type="text/javascript">
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script> -->



*/








function onSubmitButtonClick(data='') {
    if (data==''){
        var formID='formPassword';
        var passwordField='PasswordField';
    }
    else{
        var idEmploye = data.currentTarget.id.replace(/\D/g, '');
        var formID='formPassword'+idEmploye;
        var passwordField='PasswordField'+idEmploye;
    }

    // Récupérer le mot de passe depuis le champ de formulaire
    var password = document.getElementById(passwordField).value;
    if (password != '') {
         // Hasher le mot de passe
        var hashedPassword = CryptoJS.SHA256(password).toString();

        // Remplacer le mot de passe dans le champ du formulaire avec le mot de passe hashé
        document.getElementById(passwordField).value = hashedPassword;
    }

    // Soumettre le formulaire
    document.getElementById(formID).submit();
}

// Ajouter un écouteur d'événements pour le clic sur le bouton "Soumettre"

window.addEventListener('load', function() {
    if (document.querySelector('#connectBtn') != null){
        document.getElementById('connectBtn').addEventListener('click', onSubmitButtonClick);
    }
    else{
        list=document.getElementsByName('ModifPersonnelOneBtn');
        for (var i = 0; i < list.length; i++) {
            list[i].addEventListener('click', onSubmitButtonClick.bind(list[i].id));
            
        }
    }
});





window.addEventListener('load', init);

function init(){
    if (document.querySelector("#calendar") == null) {
        return;
    }
    selectedFilters = [];

    globalCurrentDate = new Date(document.getElementById("transmetterJS").textContent);
    document.getElementById("weekSelectorDateField").value =dateToString(globalCurrentDate);
    
    datePreviousWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));
    document.getElementById("previousWeekDate").value = dateToString(datePreviousWeek);
    
    dateNextWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() + 14)));
    document.getElementById("nextWeekDate").value =dateToString(dateNextWeek);
    
    globalCurrentDate = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));

    updateCalendar(globalCurrentDate);
    if (document.querySelector("#transmetterJS2") != null) {
        let name = document.getElementById("transmetterJS2").textContent;
        currentFilter = document.getElementById(name);
        filterToggle(currentFilter);
    }


}

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

/** returns a Date as a string "yyyy-mm-dd" */ 
function dateToString(globalCurrentDate) {
    return (globalCurrentDate.getFullYear() 
    + "-" + ((globalCurrentDate.getMonth() <= 8) ? "0" : "") 
    + (globalCurrentDate.getMonth() + 1) + "-" 
    + ((globalCurrentDate.getDate() <= 9) ? "0" : "") + globalCurrentDate.getDate());
}



/** called by the filterBtn dynamically created above, will toggle the filter mode for each type */ 
function filterToggle(filterBtn) {
    if (filterBtn == null) {
        return;
    }
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

/** used to hide the events that are filtered out */ 
function hide (eventHTML) {
    eventHTML.classList.add("hidden")
}

/** used to dehide the events that are filtered in again (yes it's callded show, but technically it de-hide.) */ 
function show (eventHTML) {
    eventHTML.classList.remove("hidden");
}

/** used to hide and dehide the events that are filtered in or out */ 
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
        spanToFill[i].textContent = week[i];
    }
}

function setHiddenDatefield (week) {
    var datefieldToFill = document.querySelectorAll("#newRDVdateField");
    for (let i = 0; i < 7; i++) {
        datefieldToFill[i].value = week[i];
    }
}

/** called during the updating of the title to setup the month and year label */ 
function updateDateTitle(currentDate) {
    document.querySelector(".dateBlock h1").textContent = correspondingMonth[currentDate.getMonth()]
    document.querySelector(".dateBlock span").textContent = currentDate.getFullYear();
}


function updateCalendar(currentDate) {
    updateDateTitle(currentDate);
    currentDateCopy = new Date(currentDate);
    setdayCellSpan(getWeekArray(currentDate));
    setHiddenDatefield(getWeekArrayFullDate(currentDateCopy));
}

function getWeekArray(mondayDate) {
    let weekArray = [];
    let currentDate =mondayDate;
    for (let i = 0; i < 7; i++) {
        let currentday = currentDate.getDate().toString();
        weekArray.push((currentday.length < 2) ? '0' + currentday :currentday);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    return (weekArray);
}

function getWeekArrayFullDate(mondayDate) {
    let weekArray = [];
    let currentDate = mondayDate;
    for (let i = 0; i < 7; i++) {
        let currentday = currentDate.getDate().toString();
        currentday = (currentday.length < 2) ? '0' + currentday : currentday;
        let currentmonth = (currentDate.getMonth() +1 ).toString();
        currentmonth = (currentmonth.length < 2) ? '0' + currentmonth : currentmonth;
        let currentyear = currentDate.getFullYear().toString();
        let currentFullDate = currentyear + "-" + currentmonth + '-' + currentday;
        weekArray.push(currentFullDate);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    return (weekArray);
}

function attemptUpdate() {
    let weekSelectorInput = document.getElementById("weekSelectorDateField");
    let attemptedDate = new Date(weekSelectorInput.value);
    if (attemptedDate.getFullYear() > 200 && attemptedDate.getFullYear() < 20000) {
        document.getElementById("weekSelectorForm").submit();
    }
}







/*
POUBELLE


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




*/