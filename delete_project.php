<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('DELETE FROM Tache WHERE Projet = ?');
        $stmt->execute([$projectId]);

        $stmt = $pdo->prepare('DELETE FROM Projet WHERE ID_Projet = ?');
        $stmt->execute([$projectId]);

        header("Location: projet.php?message=Projet supprimé avec succès");
        exit();

    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
?>

