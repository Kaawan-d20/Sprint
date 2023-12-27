<?php


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


#C'est ici que l'on rentre le mot de passe que l'on veut hasher
$password = 'AZERTY';









$hashedPassword = hashPassword($password);
echo "le mot de passe hashé est : ".$hashedPassword;
echo '<br>';
echo checkPassword($password, $hashedPassword);