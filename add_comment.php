<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php");
    exit();
}

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $projetId = $_POST['projet_id'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ajouter le commentaire dans la base de données
        $stmt = $pdo->prepare('INSERT INTO Commentaire (Message, Projet) VALUES (?, ?)');
        $stmt->execute([$message, $projetId]);

        header("Location: projet_detail.php?id=" . $projetId);
        exit();

    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
?>
