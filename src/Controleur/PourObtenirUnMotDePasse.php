
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de mot de passe pour la BD</title>
</head>
<body>
    <form action="PourObtenirUnMotDePasse.php" method="post">
        <label for="password">Mot de passe à hasher</label>
        <input type="text" name="password" id="password">
        <input type="submit" name="btn" value="hasher">
    </form>
</body>
</html>











<?php

if (isset($_POST['btn'])) {
    $password = $_POST['password'];
    $hashedPassword = hashPassword($password);
    echo '<br>';
    echo "le mot de passe à hasher est : ".$password;
    echo '<br>';
    echo '<br>';
    echo 'le mot de passe hashé est : <input type="text" value="'.$hashedPassword.'">';
    echo '<br>';
    echo '<br>';
    echo "c'est bon ? : ".checkPassword($password, $hashedPassword);
}

function checkPassword($password, $hashedPassword) {
    if (password_verify($password, $hashedPassword)) {
        return "true";
    } else {
        return "false";
    }
}
function hashPassword($password) {
    $options = ['cost' => 12];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
    return $hashedPassword;
}