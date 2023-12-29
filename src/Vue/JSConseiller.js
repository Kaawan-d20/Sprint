

window.addEventListener('load', init);

function init(){
    selectedFilters = [];
    currentFilter = null;
    globalCurrentDate = new Date(document.getElementById("transmetterJS1").textContent);
    document.getElementById("weekSelectorDateField").value =dateToString(globalCurrentDate);

    datePreviousWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));
    document.getElementById("previousWeekDate").value = dateToString(datePreviousWeek);

    dateNextWeek = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() + 14)));
    document.getElementById("nextWeekDate").value =dateToString(dateNextWeek);

    globalCurrentDate = (new Date(globalCurrentDate.setDate(globalCurrentDate.getDate() - 7)));

    //generateFilters();
    updateCalendar(globalCurrentDate);
    filterToggle(currentFilter);
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

    if (name == document.getElementById("transmetterJS2").textContent) {
        currentFilter = filterBtn;
    }

    return filterBtn;
} 

/** generates the filters, must be called AFTER the events are all stored in the board */ 
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

/** called by the filterBtn dynamically created above, will toggle the filter mode for each type */ 
function filterToggle(filterBtn) {
    if (filterBtn == null) {
        return null;
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
        spanToFill[i].textContent = week[i]
    }
}

function setHiddenDatefield (week) {
    let datefieldToFill = document.querySelectorAll("#newRDVdateFieldConseiller");
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
