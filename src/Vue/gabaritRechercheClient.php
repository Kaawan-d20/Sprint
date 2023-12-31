<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Client - Bank</title>
</head>
<body class="light">
    <?php echo $navbar ?>
    <div class="rechercheWrapper">
        <form action="index.php" method="post" class="rechercheForm">
            <h1>Recherche Client</h1>
            <label for="searchNameClientField" class="visually-hidden">Nom du Client</label>
            <input type="text" name="searchNameClientField" id="searchNameClientField" placeholder="nom">
            <label for="searchFirstNameClientField" class="visually-hidden">Prénom du Client</label>
            <input type="text" name="searchFirstNameClientField" id="searchFirstNameClientField" placeholder="prénom">
            <label for="searchBirthClientField" class="visually-hidden">Date de naissance du Client</label>
            <input type="date" name="searchBirthClientField" id="searchBirthClientField" placeholder="date de naissance">
            <input type="submit" name="advanceSearchClient" value="Rechercher" class="cta">
        </form>
        <?php echo $content; ?>
    </div>
</body>
</html>