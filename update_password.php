<?php
session_start();
include('config.php');

if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomUtilisateur = $_SESSION['nom'];
    $newPassword = $_POST['newPassword'];
    $sql = "UPDATE Collaborateur SET Mdp = ? WHERE Nom = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $hashedPassword, $nomUtilisateur);

    if ($stmt->execute()) {
        echo "Mot de passe mis à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour du mot de passe";
    }

    $stmt->close();
    $conn->close();
}
?>
