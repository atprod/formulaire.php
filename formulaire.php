<?php  
session_start();  

// Vérifier si l'utilisateur est authentifié  
if (!isset($_SESSION['username'])) {  
    header("Location: login.php");  
    exit();  
}  

// Déconnexion  
if (isset($_GET['logout'])) {  
    session_destroy();  
    header("Location: login.php");  
    exit();  
}  

// Si l'utilisateur est authentifié, on récupère son nom d'utilisateur  
$username = $_SESSION['username']; 

// Traitement des fichiers uploadés  
$uploaded_files = [];  
$photo_names = [  
    'photo_pto_prise_signal' => 'Photo de la PTO avec prise du signal',  
    'photo_laser_pb' => 'Photo du laser au PB',  
    'photo_laser_pm' => 'Photo du laser au PM',  
    'photo_signal_pm' => 'Photo du signal au PM',  
    'photo_cin' => 'Photo du CIN client'  
];  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    $upload_dir = "uploads/";  

    // Créer le dossier s'il n'existe pas  
    if (!is_dir($upload_dir)) {  
        mkdir($upload_dir, 0777, true);  
    }  

    foreach ($photo_names as $input_name => $label) {  
        if (isset($_FILES[$input_name]) && is_uploaded_file($_FILES[$input_name]['tmp_name'])) {  
            $file_name = basename($_FILES[$input_name]['name']);  
            $file_tmp = $_FILES[$input_name]['tmp_name'];  
            $file_path = $upload_dir . $file_name;  

            // Enregistrer le fichier  
            if (move_uploaded_file($file_tmp, $file_path)) {  
                $uploaded_files[$input_name] = $file_path;  
            }  
        }  
    }  
}  
?>  
<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Formulaire d'Insertion</title>  
    <link rel="stylesheet" href="styles.css">  
    <style>  
        /* Styles existants... */  
        
        .logout-button {  
            position: absolute;  
            top: 20px;  
            right: 20px;  
            background-color: #ff6600;  
            color: white;  
            padding: 10px 15px;  
            text-decoration: none;  
            border-radius: 5px;  
        }  

        .logout-button:hover {  
            background-color: #ffcc00;  
        }  
    </style>  
</head>  
<body>  
    <div class="container">  
        <header>  
            <img src="logo.png" alt="Logo" class="logo">  
            <a href="?logout=true" class="logout-button">Déconnexion</a> <!-- Bouton de déconnexion -->  
        </header>  
        <main>  
            <h1>Formulaire d'Insertion</h1>  
            <p>Bienvenue Mr : <?php echo htmlspecialchars($username); ?>, veuillez remplir les données du formulaire puis valider svp :</p>  
            
            <!-- Formulaire -->   
			<form action="process_form.php" method="POST" enctype="multipart/form-data">
                <!-- Tous les champs du formulaire -->  
                <label for="date_intervention">Date intervention:</label>  
                <input type="date" id="date_intervention" name="date_intervention" value="<?php echo date('Y-m-d'); ?>" readonly>  

                <label for="id_intervention">ID intervention:</label>  
                <input type="number" id="id_intervention" name="id_intervention" required>  

                <label for="technicien">Technicien intervenant:</label>  
                <input type="text" id="technicien" name="technicien" value="<?php echo htmlspecialchars($username); ?>" readonly>  

                <label for="nom_client">Nom du client:</label>  
                <input type="text" id="nom_client" name="nom_client" required>  

                <label for="prenom_client">Prénom du client:</label>  
                <input type="text" id="prenom_client" name="prenom_client" required>  
                
                <label for="numero_telephone">Numéro de téléphone:</label>  
                <input type="number" id="numero_telephone" name="numero_telephone" required>  

                <label for="numero_cin">Numéro CIN:</label>  
                <input type="number" id="numero_cin" name="numero_cin" required>  

                <label for="numero_voie">Numéro de la voie:</label>  
                <input type="text" id="numero_voie" name="numero_voie" required>  

                <label for="residence">Résidence:</label>  
                <input type="text" id="residence" name="residence">  

                <label for="ville">Ville:</label>  
                <input type="text" id="ville" name="ville" required>  

                <label for="etage">Étage:</label>  
                <input type="text" id="etage" name="etage">  

                <label for="numero_appartement">Numéro d'appartement:</label>  
                <input type="text" id="numero_appartement" name="numero_appartement">  

                <label for="tube">Tube:</label>  
                <select id="tube" name="tube" required>  
                    <option value="Rouge">Rouge</option>  
                    <option value="Bleu">Bleu</option>  
                    <option value="Vert">Vert</option>  
                    <option value="Jaune">Jaune</option>  
                    <option value="Violet">Violet</option>  
                    <option value="Blanc">Blanc</option>  
                    <option value="Orange">Orange</option>  
                    <option value="Gris">Gris</option>  
                    <option value="Marron">Marron</option>  
                    <option value="Turquoise">Turquoise</option>  
                    <option value="Vert clair (Noir)">Vert clair (Noir)</option>  
                    <option value="Rose">Rose</option>  
                </select>  

                <label for="fibre">Fibre:</label>  
                <select id="fibre" name="fibre" required>  
                    <option value="Rouge">Rouge</option>  
                    <option value="Bleu">Bleu</option>  
                    <option value="Verte">Verte</option>  
                    <option value="Jaune">Jaune</option>  
                    <option value="Violette">Violette</option>  
                    <option value="Blanche">Blanche</option>  
                    <option value="Orange">Orange</option>  
                    <option value="Gris">Gris</option>  
                    <option value="Marron">Marron</option>  
                    <option value="Turquoise">Turquoise</option>  
                    <option value="Vert clair (Noir)">Vert clair (Noir)</option>  
                    <option value="Rose">Rose</option>  
                </select>  

                <label for="type_raccordement">Type de raccordement:</label>  
                <select id="type_raccordement" name="type_raccordement" required>  
                    <option value="Aérien">Aérien</option>  
                    <option value="Aérosouterrain">Aérosouterrain</option>  
                    <option value="Façade">Façade</option>  
                    <option value="Souterrain">Souterrain</option>  
                    <option value="Intérieur">Intérieur</option>  
                </select>  

                <label for="statut_final">Statut final de l'intervention:</label>  
                <select id="statut_final" name="statut_final" required>  
                    <option value="Terminé OK">Terminé OK</option>  
                    <option value="Client absent">Client absent</option>  
                    <option value="Client Indisponible">Client Indisponible</option>  
                    <option value="Client non intéressé">Client non intéressé</option>  
                    <option value="Mis en instance (D2 incomplet)">Mis en instance (D2 incomplet)</option>  
                    <option value="Défaut de continuité">Défaut de continuité</option>  
                    <option value="PB vandalisé">PB vandalisé</option>  
                    <option value="Fourreaux bouchés">Fourreaux bouchés</option>  
                    <option value="Gardée en main">Gardée en main</option>  
                </select>  

                <label for="longueur_cable">Longueur du câble (m):</label>  
                <input type="number" id="longueur_cable" name="longueur_cable" required>  

                <!-- Affichage des champs pour uploader les photos -->  
                <div>  
                    <?php foreach ($photo_names as $input_name => $label): ?>  
                        <label for="<?php echo $input_name; ?>"><?php echo $label; ?>:</label>  
                        <input type="file" id="<?php echo $input_name; ?>" name="<?php echo $input_name; ?>" accept="image/*">  
                    <?php endforeach; ?>  
                </div>  

                <!-- Bouton pour soumettre le formulaire -->  
                <button type="submit">Valider</button>  
            </form>  
			

            <!-- Affichage des photos en mosaïque -->  
            <div class="photo-grid">  
                <?php  
                foreach ($uploaded_files as $file_path) {  
                    echo '<div class="photo-item">';  
                    echo '<img src="' . htmlspecialchars($file_path) . '" alt="Photo">';  
                    echo '</div>';  
                }  
                ?>  
            </div>  

        </main>  
    </div>  
</body>  
</html>