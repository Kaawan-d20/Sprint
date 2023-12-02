<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info</title>
</head>
<body>
    <h1>Recherche Client</h1>
    <form action="index.php" method="post">
        <p><label for="searchClientField">Chercher un client </label>
        <input type="text" name="searchNomClientField" id="searchNomClientField">
        <input type="text" name="searchPrenomClientField" id="searchPrenomClientField">
        <input type="date" name="searchNaissanceClientField" id="searchNaissanceClientField">
        <input type="submit" name="rechercherAvanceeClient" value="Rechercher"></p>
    </form>
    <?php echo $contenu; ?>
</body>
</html>