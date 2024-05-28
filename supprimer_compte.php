<?php
session_start();
include('config.php'); 

if (isset($_SESSION['nom'])) {
    $nomUtilisateur = $_SESSION['nom'];

   
    $sql = "DELETE FROM Collaborateur WHERE Nom = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nomUtilisateur);

    if ($stmt->execute()) {
        
        session_unset();
        session_destroy();
        header("Location: PageLogin.php"); 
        exit();
    } else {
        echo "Erreur lors de la suppression du compte. Veuillez réessayer.";
    }
}
?>
