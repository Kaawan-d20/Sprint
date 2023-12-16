<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="light">
    <?php echo $navbar; ?>
    <?php echo $content ?>
    <!--<h1>Gestion des Employés</h1>
    <div class="employeTableWrapper">
        <div class="employeTableHeaderWrapper">
            <div class="employeCell header">Id</div>
            <div class="employeCell header">Rôle</div>
            <div class="employeCell header">Nom</div>
            <div class="employeCell header">Prénom</div>
            <div class="employeCell header">Login</div>
            <div class="employeCell header">Couleur</div>
        </div>
        <form action="index.php" method="post" class="employeTableContentWrapper" onformchange="displayValidBtn">
            <input class="employeCell content" name="idEmployee" value="'.$employee->IDEMPLOYE.'" disabled="disabled">
            <select name="idCategorie" class="employeCell content">
                <option value="1" ".$etat1." >Directeur</option>
                <option value="2" ".$etat2." >Conseiller</option>
                <option value="3" ".$etat3." >Agent d'acceuil</option>
            </select>
            <input type="text" name="nameEmployee" class="employeCell content" value="'.$employee->NOM.'">
            <input type="text" name="firstNameEmployee" class="employeCell content" value="'.$employee->PRENOM.'">
            <input type="text" name="loginEmployee" class="employeCell content" value="'.$employee->LOGIN.'">
            <select name="colorEmployee" class="employeCell content">
                do foreach
            </select>
            <button type="submit" name="ModifPersonnelOneBtn" class="employeBtn"><i class="fa-solid fa-pen-to-square"></i>Valider</button>
            <button type="submit" name="GestionPersonnelDeleteBtn" class="employeBtn red"><i class="fa-solid fa-trash-can"></i>Supprimer</button>
        </form>
        <form action="index.php" method="post" class="employeTableContentWrapper" onformchange="displayValidBtn">
            <input class="employeCell content" name="idEmployee" value="'.$employee->IDEMPLOYE.'" disabled="disabled">
            <select name="idCategorie" class="employeCell content">
                <option value="1" ".$etat1." >Directeur</option>
                <option value="2" ".$etat2." >Conseiller</option>
                <option value="3" ".$etat3." >Agent d'acceuil</option>
            </select>
            <input type="text" name="nameEmployee" class="employeCell content" value="'.$employee->NOM.'">
            <input type="text" name="firstNameEmployee" class="employeCell content" value="'.$employee->PRENOM.'">
            <input type="text" name="loginEmployee" class="employeCell content" value="'.$employee->LOGIN.'">
            <select name="colorEmployee" class="employeCell content">
                do foreach
            </select>
            <button type="submit" name="ModifPersonnelOneBtn" class="employeBtn"><i class="fa-solid fa-pen-to-square"></i>Valider</button>
            <button type="submit" name="GestionPersonnelDeleteBtn" class="employeBtn red"><i class="fa-solid fa-trash-can"></i>Supprimer</button>
        </form>
    </div> -->
</body>
</html>