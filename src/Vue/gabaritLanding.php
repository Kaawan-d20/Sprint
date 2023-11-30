<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Landing</title>
</head>
<body>
    <div class="landingWrapper">
        <form action="index.php" method="POST">
        <fieldset>
            <legend>Connection</legend>
            <p>
                <label for="landingLoginField">Nom d'utilisateur</label>
                <input type="text" name="landingLoginField" id="landingLoginField">
            </p>
            <p>
                <label for="landingPasswordField">Mot de Passe</label>
                <input type="password" name="landingPasswordField" id="landingPasswordField">
            </p>
            <p>
                <input type="submit" name="landingSubmitBtn"  id="landingSubmitBtn" value="Connection">
                <input type="reset" name="landingResetBtn" id="landingResetBtn">
            </p>
        </fieldset>
        </form>
    </div>
</body>
</html>