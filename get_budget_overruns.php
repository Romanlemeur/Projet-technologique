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

    $stmt = $pdo->query('SELECT Titre, (BudgetActuel - Budget) AS Depassement FROM Projet WHERE BudgetActuel > Budget');
    $budgetOverruns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($budgetOverruns);

} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
