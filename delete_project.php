<?php
include('config.php'); // Inclure votre fichier de configuration de base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'ID du projet à supprimer est défini
    if (isset($_POST['projectId'])) {
        // Échapper les données pour éviter les injections SQL
        $projectId = mysqli_real_escape_string($conn, $_POST['projectId']);

        // Construire et exécuter la requête de suppression
        $sql = "DELETE FROM Projet WHERE ID_Projet = '$projectId'";
        if (mysqli_query($conn, $sql)) {
            header('Location: adminprojet.php');
        } else {
            echo "Erreur lors de la suppression du projet : " . mysqli_error($conn);
        }
    } else {
        echo "ID du projet non spécifié.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
