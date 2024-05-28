<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php"); 
    exit();
}

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $objectif = $_POST['objectif'];
    $budget = $_POST['budget'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO Projet (Titre, Description, Debut, Fin, Objectif, Budget) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$titre, $description, $debut, $fin, $objectif, $budget]);

        header("Location: projet.php?message=Projet créé avec succès");
        exit();

    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
?>