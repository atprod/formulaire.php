<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techniciens_db";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifier si les champs sont remplis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Préparer la requête
    $stmt = $conn->prepare("SELECT password FROM utilisateurs WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    // Vérifier le mot de passe
    if ($pass === $stored_password) {
        $_SESSION['username'] = $user;
        header("Location: recherche.php");
        exit();
    } else {
        header("Location: login2.php?error=1");
        exit();
		
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <form action="login2.php" method="POST">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Se connecter</button>
            <?php
            if (isset($_GET['error'])) {
                echo '<p class="error">Nom d’utilisateur ou mot de passe incorrect.</p>';
            }
            ?>
        </form>
    </div>
</body>
</html>

