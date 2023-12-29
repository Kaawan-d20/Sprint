
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de mot de passe pour la BD</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
</head>
<body>
    <h1>Générateur de mot de passe pour la BD</h1>
    <h2>Hacher JS</h2>
    <input type="text" id="password" onchange="hashed()" size="30">
    <input type="text" id="hashedpassword" size="80">


    <h2>Hacher PHP</h2>
    <form action="PourObtenirUnMotDePasse.php" method="post">
        <label for="password">Mot de passe à hacher</label>
        <input type="text" name="password" id="password"size="80">
        <input type="submit" name="btn" value="hacher">
    </form>


    <h2>Hacher PHP verif</h2>
    <form action="PourObtenirUnMotDePasse.php" method="post">
        <label for="password">Mot de passe à hacher</label>
        <input type="text" name="password" id="password" placeholder="password"size="80">
        <input type="text" name="seed" id="seed" placeholder="seed"size="80">
        <input type="submit" name="verif" value="hacher">
    </form>
</body>
<script>
    function hashed(){
        password = document.getElementById("password").value;
        hash = CryptoJS.SHA256(password).toString();
        document.getElementById("hashedpassword").value = hash;
    }
</script>
</html>











<?php

if (isset($_POST['btn'])) {
    $password = $_POST['password'];
    $hashedPassword = hashPassword($password);
    echo '<br>';
    echo "le mot de passe à hacher est : ".$password;
    echo '<br>';
    echo '<br>';
    echo 'le mot de passe haché est : <input type="text" value="'.$hashedPassword.'" size="80">';
    echo '<br>';
    echo '<br>';
    echo "c'est bon ? : ".checkPassword($password, $hashedPassword);
}

if (isset($_POST['verif'])) {
    $password = $_POST['password'];
    $seed = $_POST['seed'];
    echo '<br>';
    echo "le mot de passe à tester est est : ".$password;
    echo '<br>';
    echo '<br>';
    echo 'la seed est : '.$seed;
    echo '<br>';
    echo '<br>';
    echo "c'est bon ? : ".checkPassword($password, $seed);
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