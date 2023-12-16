<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Vue/style.css">
    <script src="https://kit.fontawesome.com/31ad525f9a.js" crossorigin="anonymous"></script>
    <title>Bank</title>
</head>
<body>
    <!-- <script type="text/javascript">
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script> -->

    <?php require_once 'controleur/front.php'; ?>

    <script>
        let isLightTheme = true;
        /** switch beetween light and dark theme */ 
        function toggleTheme() {
            let icon = document.getElementById("themeSwitcherIcon");
            let btn = document.getElementById("themeSwitcherBtn");
            if (isLightTheme) {
                document.body.classList.add("dark");
                document.body.classList.remove("light");
                icon.classList.add("fa-sun")
                icon.classList.remove("fa-moon")
                btn.setAttribute("title", "Activer le thème Clair")

                isLightTheme = false;
            } else {
                document.body.classList.add("light");
                document.body.classList.remove("dark");
                icon.classList.add("fa-moon")
                icon.classList.remove("fa-sun")
                btn.setAttribute("title", "Activer le thème Sombre")

                isLightTheme = true;
            }
        }
    </script>
</body>
</html>