<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Info</title>
</head>
<body>
    <!--Message pour Nathan
    Cette partie est a afficher en php dans la vue, car si le client n'a pas de compte, il ne faut pas afficher cette partie
    Mais je te la laisse ici pour que tu puisse faire le css
    -->
    <?php 
        $events = [["Ouverture Compte", "28/12/2023", "14h30", "15h30", "lush-green", "Bertrand"], ["Signature Contrat", "28/12/2023","15h30", "15h45", "lush-green", "Bertrand"]]
    ?>
    <div class="syntheseWrapper">
        <h1>Synthese</h1>
        <div class="nameWrapper">
            <h2><?php echo $civi; ?> <?php echo $firstNameClient; ?> <?php echo $nameClient; ?></h2>
            <span class="pronounsSpan">Il/Lui</span>
            <!-- CHANGE TO A VAR -->
            <div class="idDiv">
                ID: <?php echo $idClient; ?>
            </div>
        </div>
        <div class="infoWrapper">
            <div class="infoHeaderWrapper">
                <div class="infoHeader">Date de naissance</div>
                <div class="infoHeader">Profession</div>
                <div class="infoHeader">Situation familiale</div>
                <div class="infoHeader">Client·e depuis</div>
            </div>
            <div class="infoContentWrapper">
                <div class="infoContent"><?php echo $naissance; ?></div>
                <div class="infoContent"><?php echo $profession; ?></div>
                <div class="infoContent"><?php echo $situation; ?></div>
                <div class="infoContent"><?php echo $creation; ?></div>
            </div>
        </div>
        <div id="contactAndRDVWrapper" class="contactAndRDVWrapper">
            <div class="contactWrapper">
                <h1>Contact:</h1>
                <div class="contactTableWrapper">
                    <div class="contactHeaderWrapper">
                        <div class="contactHeader">Addresse : </div>
                        <div class="contactHeader">N° : </div>
                        <div class="contactHeader">Email : </div>
                    </div>
                    <div class="contactContentWrapper">
                        <div class="contactContent"><?php echo $addressClient; ?></div>
                        <div class="contactContent"><?php echo $phoneClient; ?></div>
                        <div class="contactContent"><?php echo $emailClient; ?></div>
                    </div>
                </div>
                <div class="debitCreditWrapper">
                    <h1>Débit / Crédit</h1> 
                    <form action="index.php" method="post">
                        <select name="debitAccountSelector" id="" class="debitAccountSelector">
                            <?php
                                echo $optionSelect;
                            ?>
                        </select>
                        <input type="number" name="amountInput" id="" min="0" required>
                        <input type="submit" value="Débit" name="debitBtn" min="0" class="debitCreditBtn">
                        <input type="submit" value="Crédit" name="creditBtn" class="debitCreditBtn">
                    </form>
                </div> 
            </div>
            <div class="RDVSectionWrapper">
                <h1>Liste des RDV du client:</h1>
                <div class="RDVWrapper">

                </div>
            </div>
        </div>
   
    </div>
    
<script>

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

    let eventsArray = <?php echo json_encode($events); ?>;
    // let eventsArray = [];
    console.log(eventsArray);
    addAllEvents(eventsArray);
</script>
</body>
</html>
