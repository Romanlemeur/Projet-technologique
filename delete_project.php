<?php
include('config.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['projectId'])) {
        $projectId = mysqli_real_escape_string($conn, $_POST['projectId']);

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
