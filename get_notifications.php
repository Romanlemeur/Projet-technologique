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

    $stmt = $pdo->query('SELECT Titre, Contenu FROM Notification');
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notifications);

} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
