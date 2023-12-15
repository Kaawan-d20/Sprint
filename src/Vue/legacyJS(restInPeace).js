
{/* <div class="event">
    <h2>Ouverture de Compte</h2>
    <p>Mx Elise Johansson</p>
    <span class="pronounsSpan">elle/elle</span> no more pronouns :(
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
</div> */}

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

    let eventTitle = document.createElement("h2");
    eventTitle.appendChild(document.createTextNode(motif));
    eventHTML.appendChild(eventTitle);

    let eventClient = document.createElement("p");
    eventClient.appendChild(document.createTextNode(civilitee + " " + client));
    eventHTML.appendChild(eventClient);

    // let pronounsSpan = document.createElement("span");
    // pronounsSpan.classList.add("pronounsSpan");
    // pronounsSpan.appendChild(document.createTextNode(pronoms));
    // eventHTML.appendChild(pronounsSpan);

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

let correspondingDay = [
    "sunday",
    "monday",
    "tuesday",
    "wednesday",
    "thursday",
    "friday",
    "saturday"
];

/** create an event div, with every field filled then returns it */ 
function createEvent(motif, date, horaireDebut, horaireFin, conseillerColor, conseiller) {
    let eventHTML = document.createElement("div");
    eventHTML.classList.add("event");
    
    let eventTitle = document.createElement("h2");
    eventTitle.appendChild(document.createTextNode(motif));
    eventHTML.appendChild(eventTitle);

    let eventDate = document.createElement("p");
    eventDate.appendChild(document.createTextNode(date));
    eventHTML.appendChild(eventDate)

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

// Will need to be updated
function addAllEvents(eventsArray) {
    if (eventsArray.length <= 0) {
        document.querySelector(".RDVSectionWrapper").classList.add("hidden")
        document.getElementById("contactAndRDVWrapper").classList.remove("contactAndRDVWrapper")
    } else {
        eventsArray.forEach(event => {
            let motif = event[0];
            let date =event[1];
            let horaireDebut =event[2];
            let horaireFin = event[3];
            let conseillerColor =event[4];
            let conseillerName =event[5];
            document.querySelector(".RDVWrapper").appendChild(createEvent(motif, date,horaireDebut, horaireFin, conseillerColor, conseillerName))     
        });
    }
}

// let eventsArray = <?php echo json_encode($events); ?>;
// let eventsArray = [];
console.log(eventsArray);
addAllEvents(eventsArray);
