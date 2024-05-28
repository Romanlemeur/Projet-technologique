<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php");
    exit();
}

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $projetId = $_POST['projet_id'];
    $file = $_FILES['file'];

    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ajouter le fichier dans la base de données
            $stmt = $pdo->prepare('INSERT INTO Fichier (Nom, Type, Collaborateur, Commentaire) VALUES (?, ?, ?, ?)');
            $stmt->execute([$file['name'], $file['type'], $_SESSION['collaborateur_id'], $projetId]);

            header("Location: projet_detail.php?id=" . $projetId);
            exit();

        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        echo 'Erreur lors du téléversement du fichier.';
    }
}
?>
