<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php");
    exit();
}

include('config.php');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $currentDate = date('Y-m-d');
    $stmt = $pdo->prepare('SELECT Titre, Description FROM Tache WHERE Fin < ? AND Etat != 1');
    $stmt->execute([$currentDate]);
    $lateTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($lateTasks);

} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
