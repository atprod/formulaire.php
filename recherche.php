<?php
session_start();

// Vérifiez si l'utilisateur est authentifié
if (!isset($_SESSION['username'])) {
    header("Location: login2.php");
    exit();
}

$username = $_SESSION['username'];

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=techniciens_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

$id_intervention = '';
$intervention = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_intervention = $_POST['id_intervention'];

    // Préparer la requête pour obtenir les données de l'intervention
    $stmt = $pdo->prepare("SELECT * FROM interventions WHERE id_intervention = ?");
    $stmt->execute([$id_intervention]);
    $intervention = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$intervention) {
        $error = 'Aucune intervention trouvée pour cet ID.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'Intervention</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        h1 {
            color: #003366;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input {
            padding: 8px;
            margin-bottom: 10px;
            width: 100%;
        }
        button {
            background-color: #ff6600;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #ffcc00;
        }
        .readonly-field {
            background-color: #e9ecef;
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        .photo-grid {
            margin-top: 20px;
        }
        .photo-item {
            margin-bottom: 20px;
        }
        .photo-item img {
            max-width: 100%;
            border: 2px solid #ccc;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recherche d'Intervention</h1>
        <p>Bienvenue Mr : <?php echo htmlspecialchars($username); ?>, Veillez entrer l'ID de l'intervention pour afficher les données et les photos :</p>

        <!-- Formulaire de recherche -->
        <form action="recherche.php" method="POST">
            <label for="id_intervention">ID intervention:</label>
            <input type="number" id="id_intervention" name="id_intervention" value="<?php echo htmlspecialchars($id_intervention); ?>" required>
            <button type="submit">Rechercher</button>
        </form>

        <!-- Affichage des données de l'intervention -->
        <?php if (!empty($intervention)) : ?>
            <h2>Données de l'intervention <?php echo htmlspecialchars($id_intervention); ?> :</h2>
            <div>
                <label>Date intervention:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['date_intervention']); ?>" readonly>

                <label>ID intervention:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['id_intervention']); ?>" readonly>

                <label>Nom du client:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['nom_client']); ?>" readonly>

                <label>Prénom du client:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['prenom_client']); ?>" readonly>

                <label>Numéro de téléphone:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['numero_telephone']); ?>" readonly>

                <label>Numéro CIN:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['numero_cin']); ?>" readonly>

                <label>Numéro de la voie:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['numero_voie']); ?>" readonly>

                <label>Résidence:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['residence']); ?>" readonly>

                <label>Ville:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['ville']); ?>" readonly>

                <label>Étage:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['etage']); ?>" readonly>

                <label>Numéro d'appartement:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['numero_appartement']); ?>" readonly>

                <label>Tube:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['tube']); ?>" readonly>

                <label>Fibre:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['fibre']); ?>" readonly>

                <label>Type de raccordement:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['type_raccordement']); ?>" readonly>

                <label>Statut final de l'intervention:</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['statut_final']); ?>" readonly>

                <label>Longueur du câble (m):</label>
                <input type="text" class="readonly-field" value="<?php echo htmlspecialchars($intervention['longueur_cable']); ?>" readonly>
            </div>
        <?php elseif ($error) : ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Affichage des photos -->
        <?php if (!empty($intervention)) : ?>
            <h2>Photos de l'intervention :</h2>
            <div class="photo-grid">
                <?php if (!empty($intervention['photo_pto_prise_signal'])) : ?>
                    <div class="photo-item">
                        <strong>Photo de la PTO avec prise du signal:</strong>
                        <img src="<?php echo htmlspecialchars($intervention['photo_pto_prise_signal']); ?>" alt="Photo PTO prise signal">
                    </div>
                <?php endif; ?>signal
                <?php if (!empty($intervention['photo_laser_pb'])) : ?>
                    <div class="photo-item">
                        <strong>Photo du laser au PB:</strong>
                        <img src="<?php echo htmlspecialchars($intervention['photo_laser_pb']); ?>" alt="Photo Laser PB">
                    </div>
                <?php endif; ?>
                <?php if (!empty($intervention['photo_laser_pm'])) : ?>
                    <div class="photo-item">
                        <strong>Photo du laser au PM:</strong>
                        <img src="<?php echo htmlspecialchars($intervention['photo_laser_pm']); ?>" alt="Photo Laser PM">
                    </div>
                <?php endif; ?>
                <?php if (!empty($intervention['photo_signal_pm'])) : ?>
                    <div class="photo-item">
                        <strong>Photo du signal au PM:</strong>
                        <img src="<?php echo htmlspecialchars($intervention['photo_signal_pm']); ?>" alt="Photo Signal PM">
                    </div>
                <?php endif; ?>
                <?php if (!empty($intervention['photo_cin'])) : ?>
                    <div class="photo-item">
                        <strong>Photo du CIN client:</strong>
                        <img src="<?php echo htmlspecialchars($intervention['photo_cin']); ?>" alt="Photo CIN">
                    </div>
                <?php endif; ?>
				
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
