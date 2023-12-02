<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <h1>Accueil Agent</h1>
    <form action="index.php" method="POST">
        <p><label for="searchClientField">Chercher un client </label>
        <input type="number" name="searchClientField" id="searcClientField">
        <input type="submit" name="searchClientBtn" value="Rechercher"></p>
        <p><label for="idClient">Debit Credit </label><input type="number" name="idClient" id="idClient"></p>
        <p><label for="calendar">Calendar </label><input type="date" name="calendar" id="calendar"></p>
        <p><input type="submit" name="advanceSearch" value="avancée"></p>
    </form>
</body>
</html>