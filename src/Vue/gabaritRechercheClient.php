<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info</title>
</head>
<body class="light">
    <?php echo $navbar ?>
    <h1>Recherche Client</h1>
    <form action="index.php" method="post">
        <p><label for="searchClientField">Chercher un client </label>
        <input type="text" name="searchNameClientField" id="searchNameClientField" placeholder="nom">
        <input type="text" name="searchFirstNameClientField" id="searchFirstNameClientField" placeholder="prenom">
        <input type="date" name="searchBirthClientField" id="searchBirthClientField" placeholder="date de naissance">
        <input type="submit" name="advanceSearchClient" value="Rechercher"></p>
    </form>
    <?php echo $content; ?>
</body>
</html>