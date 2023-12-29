



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
            item.classList.remove("hidden")
        });
        document.querySelectorAll(".appointement").forEach(function (item) {
            item.classList.add("hidden")
        });
    } else {
        document.querySelectorAll(".appointement").forEach(function (item) {
            item.classList.remove("hidden")
        });
        document.querySelectorAll(".admin").forEach(function (item) {
            item.classList.add("hidden")
        });
    }
}


//Prise de RDV
function changeConseiller(select) {
    let option =select.options[select.selectedIndex];
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

    // Hasher le mot de passe
    var hashedPassword = CryptoJS.SHA256(password).toString();

    // Remplacer le mot de passe dans le champ du formulaire avec le mot de passe hashé
    document.getElementById(passwordField).value = hashedPassword;
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
