<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bank</title>
</head>
<body class="light">
<div class="loginWrapper">
    <div class="loginCard">
        <header class="loginCardHeader">
            <h1>Connection</h1>
        </header>
        <div>
            <form action="index.php" method="POST" class="loginForm" id="formPassword">
                <div class="loginFormFieldWrapper">
                    <label for="landingLoginField" class="visually-hidden">Nom d'utilisateur</label>
                    <input type="text" name="landingLoginField" id="landingLoginField" class="loginFormField" placeholder="Login" required>
                </div>
                <div class="loginFormFieldWrapper">
                    <label for="landingPasswordField" class="visually-hidden">Mot de Passe</label>
                    <input type="password" name="landingPasswordField" id="PasswordField" class="loginFormField" placeholder="Password" required>
                    <button onclick="togglePasswordVisibility('')" type="button" class="visibilityButton"><i class="fa-solid fa-eye" id="visibilityIcon"></i></button>
                </div>
                <div class="ctaContainer">
                    <input type="submit" name="landingSubmitBtn"  id="connectBtn" value="Connection" class="cta">
                </div>
            </form>
            <button class="landingThemeBtn" onclick="toggleTheme()" type="button" id="themeSwitcherBtn">
                <i class="fa-solid fa-moon" id="themeSwitcherIcon"></i>
            </button>
        </div>
    </div>
</div>
</body>
</html>
