<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/31ad525f9a.js" crossorigin="anonymous"></script> -->
    <title>Landing</title>
</head>
<body class="light">
<div class="loginWrapper">
    <div class="loginCard">
        <header class="loginCardHeader">
            <h1>Connection</h1>
        </header>
        <div>
            <form action="index.php" method="POST" class="loginForm">
                <div class="loginFormFieldWrapper">
                    <label for="landingLoginField" class="visually-hidden">Nom d'utilisateur</label>
                    <input type="text" name="landingLoginField" id="landingLoginField" class="loginFormField" placeholder="Login" required>
                </div>
                <div class="loginFormFieldWrapper">
                    <label for="landingPasswordField" class="visually-hidden">Mot de Passe</label>
                    <input type="password" name="landingPasswordField" id="landingPasswordField" class="loginFormField" placeholder="Password" required>
                    <button onclick="togglePasswordVisibility()" type="button" class="visibilityButton"><i class="fa-solid fa-eye-slash" id="visibilityIcon"></i></button>
                </div>
                <p>Pas de compte ? <a href="i dont know">en créer un</a></p>
                <div class="ctaContainer">
                    <input type="submit" name="landingSubmitBtn"  id="landingSubmitBtn" value="Connection" class="cta">
                </div>
            </form>
        </div>
        <div>
            <p class="error">
                <?php $content ?>
            </p>
        </div>
    </div>
</div>
<script>
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
</script>
</body>
</html>
