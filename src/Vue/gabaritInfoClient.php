<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>Info</title>
</head>
<body class="light"> <!-- TODO: use session to choose beetween light or dark -->
    <?php 
        $events = [["Ouverture Compte", "28/12/2023", "14h30", "15h30", "lush-green", "Bertrand"], ["Signature Contrat", "28/12/2023","15h30", "15h45", "lush-green", "Bertrand"]]
    ?>
    <div class="syntheseWrapper">
    <form action="index.php" method="post">
        <h1>Synthese</h1>
        <div class="nameWrapper">
            <h2><?php echo $civi; ?> <?php echo $firstNameClient; ?> <?php echo $nameClient; ?></h2>
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
                <input type="date" class="infoContent" id="naissance" name="naissance" value="<?php echo $naissance; ?>">
                <input type="text" class="infoContent" id="profession" name="profession" value="<?php echo $profession; ?>">
                <input type="text" class="infoContent" id="situation" name="situation" value="<?php echo $situation; ?>">
                <input type="date" class="infoContent" id="creation" name="creation" disabled="disabled" value="<?php echo $creation; ?>">
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
                        <div class="contactHeader">Conseiller : </div>
                    </div>
                    <div class="contactContentWrapper">
                         <input type="text" class="contactContent" name="addressClient" id="addressClient" value="<?php echo $addressClient; ?>">
                        <input type="tel" class="contactContent" name="phoneClient" id="phoneClient" pattern="((\+|00)?[1-9]{2}|0)[1-9]( ?[0-9]){8}" value="<?php echo $phoneClient; ?>">
                        <input type="email" class="contactContent" name="emailClient" id="emailClient" value="<?php echo $emailClient; ?>">
                        <input type="text" class="contactContent" name="nameConseiller" id="nameConseiller" value="<?php echo $nameConseiller; ?>">
                    </div>
                </div>
                <div class="debitCreditWrapper">
                    <h1>Débit / Crédit</h1> 
                    <form action="index.php" method="post">
                        <select name="debitAccountSelector" id="debitAccountSelector" class="debitAccountSelector">
                            <?php
                                echo $optionSelect;
                            ?>
                        </select>
                        <input type="number" name="amountInput" id="amountInput" class="amountInput" min="0" required>
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
    </form>
    </div>
    <div>
        
        <?php
            echo $content;
        ?>
    </div>
<script>
function displayOperations() {
    if (typeof accountSelected == 'undefined'){
        previous = "1";
    }
    else{
        previous = accountSelected;
    }
    console.log(previous)
    accountSelected = document.getElementById('comptes').value;
    console.log(document.getElementById('comptes').value)
    document.getElementById(previous).classList.add("hidden");
    document.getElementById(accountSelected).classList.remove("hidden");

}
window.setTimeout(displayOperations(),0);
</script>































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