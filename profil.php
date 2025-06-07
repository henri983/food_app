<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
     <link rel="stylesheet" href="./style/style.css">
    <title>Profile</title>
</head>
<body>
      <?php require_once "header.php"; ?>
  
    <main>
        <section class="content">
            <h2>Profil</h2>
            <div class="container">
                <?php
                session_start();
                if (isset($_SESSION['email'])) {
                    echo "<p>Bienvenue, " . htmlspecialchars($_SESSION['email']) . "!</p>";
                    echo '<a href="logout.php" class="crystal-btn">Se déconnecter</a>';
                } else {
                    echo "<p>Vous n'êtes pas connecté.</p>";
                    echo '<a href="login.php" class="crystal-btn">Se connecter</a>';
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <?php require_once "footer.php"; ?>
    </footer>
</body>
</html>