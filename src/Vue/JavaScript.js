



let nom_du_cookie = "Theme=";
let decodedCookie = decodeURIComponent(document.cookie);
let ca = decodedCookie.split(';');
for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
        c = c.substring(1);
    }
    if (c.indexOf(nom_du_cookie) == 0) {
        console.log(c.substring(nom_du_cookie.length, c.length));
    }
}
console.log(c.substring(nom_du_cookie.length, c.length));
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
    console.log("toggleTheme");
    console.log(isLightTheme);
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





function togglePasswordVisibility() {
    let passwordField = document.getElementById("landingPasswordField");
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







/*
POUBELLE
<!-- <script type="text/javascript">
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script> -->



*/










