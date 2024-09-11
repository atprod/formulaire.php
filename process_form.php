<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Changez ce nom d'utilisateur si nécessaire
$password = ""; // Changez ce mot de passe si nécessaire
$dbname = "techniciens_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $date_intervention = $_POST['date_intervention'];
    $id_intervention = $_POST['id_intervention'];
    $technicien = $_POST['technicien'];
    $nom_client = $_POST['nom_client'];
    $prenom_client = $_POST['prenom_client'];
    $numero_telephone = $_POST['numero_telephone'];
    $numero_cin = $_POST['numero_cin'];
    $numero_voie = $_POST['numero_voie'];
    $residence = $_POST['residence'];
    $ville = $_POST['ville'];
    $etage = $_POST['etage'];
    $numero_appartement = $_POST['numero_appartement'];
    $tube = $_POST['tube'];
    $fibre = $_POST['fibre'];
    $type_raccordement = $_POST['type_raccordement'];
    $statut_final = $_POST['statut_final'];
    $longueur_cable = $_POST['longueur_cable'];

    // Gérer les fichiers uploadés
    $photo_names = [
        'photo_pto_prise_signal' => 'Photo de la PTO avec prise du signal',
        'photo_laser_pb' => 'Photo du laser au PB',
        'photo_laser_pm' => 'Photo du laser au PM',
        'photo_signal_pm' => 'Photo du signal au PM',
        'photo_cin' => 'Photo du CIN client'
    ];

    $uploaded_files = [];
    foreach ($photo_names as $input_name => $label) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES[$input_name]['tmp_name'];
            $file_name = $_FILES[$input_name]['name'];
            $file_path = 'uploads/' . $file_name;
            move_uploaded_file($file_tmp_path, $file_path);
            $uploaded_files[$input_name] = $file_path;
        } else {
            $uploaded_files[$input_name] = null;
        }
    }

    // Préparer la requête SQL pour insérer les données
    $stmt = $conn->prepare("INSERT INTO interventions (
        date_intervention, id_intervention, technicien, nom_client, prenom_client, numero_telephone, numero_cin,
        numero_voie, residence, ville, etage, numero_appartement, tube, fibre, type_raccordement, statut_final,
        longueur_cable, photo_pto_prise_signal , photo_laser_pb , photo_laser_pm, photo_signal_pm, photo_cin 
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sissssssssssssssisssss", $date_intervention, $id_intervention, $technicien, $nom_client, $prenom_client, 
        $numero_telephone, $numero_cin, $numero_voie, $residence, $ville, $etage, $numero_appartement, $tube, 
        $fibre, $type_raccordement, $statut_final, $longueur_cable, $uploaded_files['photo_pto_prise_signal'], 
        $uploaded_files['photo_laser_pb'], $uploaded_files['photo_laser_pm'], $uploaded_files['photo_signal_pm'], 
        $uploaded_files['photo_cin']);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Données insérées avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>
