<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Landing</title>
</head>
<body>
<div class="loginWrapper">
    <div class="loginCard">
        <header class="loginCardHeader">
            <h1>Connection</h1>
        </header>
        <div>
            <form action="index.php" method="POST" class="loginForm">
                <div class="loginFormFieldWrapper">
                    <label for="landingLoginField" class="visually-hidden">Nom d'utilisateur</label>
                    <input type="text" name="landingLoginField" id="landingLoginField" class="loginFormField">
                </div>
                <div class="loginFormFieldWrapper">
                    <label for="landingPasswordField" class="visually-hidden">Mot de Passe</label>
                    <input type="password" name="landingPasswordField" id="landingPasswordField" class="loginFormField">
                    <input type="checkbox" name="showPassword" id="showPassword" onclick="togglePasswordVisibility()">
                </div>
                <div>
                    <input type="submit" name="landingSubmitBtn"  id="landingSubmitBtn" value="Connection" class="cta">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("landingPasswordField");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
}
</script>
</body>
</html>