<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php"); 
    exit();
}
$nomUtilisateur = $_SESSION['nom'];

if (!isset($_GET['id'])) {
    header("Location: projet.php"); 
    exit();
}

$projetId = $_GET['id'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT * FROM Projet WHERE ID_Projet = ?');
    $stmt->execute([$projetId]);
    $projet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$projet) {
        header("Location: projet.php"); 
        exit();
    }

    $stmt = $pdo->prepare('SELECT * FROM Tache WHERE Projet = ?');
    $stmt->execute([$projetId]);
    $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare('SELECT * FROM Commentaire WHERE Tache IN (SELECT ID_Tache FROM Tache WHERE Projet = ?)');
    $stmt->execute([$projetId]);
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare('SELECT * FROM Fichier WHERE Commentaire IN (SELECT ID_Commentaire FROM Commentaire WHERE Tache IN (SELECT ID_Tache FROM Tache WHERE Projet = ?))');
    $stmt->execute([$projetId]);
    $fichiers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du projet</title>
    <link rel="stylesheet" href="projet.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="image/logofirst.png" alt="Logo">
            <h1>Plano</h1>
        </div>
        <ul>
            <li><a href="page.php">Accueil</a></li>
            <li><a href="projet.php">Projet</a></li>
            <li><a href="Calendrier.php">Calendrier</a></li>
            <li><a href="#">Notifications</a></li>
        </ul>
        <div class="profile-banner">
            <img src="image/user.png" alt="Profil">
            <div class="profile-info">
                <p><?php echo htmlspecialchars($nomUtilisateur); ?></p>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="project-detail-container">
            <h2><?php echo htmlspecialchars($projet['Titre']); ?></h2>
            <p>Description: <?php echo htmlspecialchars($projet['Description']); ?></p>
            <p>Début: <?php echo htmlspecialchars($projet['Debut']); ?> | Fin: <?php echo htmlspecialchars($projet['Fin']); ?></p>
            <p>Objectif: <?php echo htmlspecialchars($projet['Objectif']); ?></p>
            <p>Budget: <?php echo htmlspecialchars($projet['Budget']); ?></p>
            <h3>Tâches</h3>
            <ul>
                <?php foreach ($taches as $tache): ?>
                    <li>
                        <h4><?php echo htmlspecialchars($tache['Titre']); ?></h4>
                        <p>Début: <?php echo htmlspecialchars($tache['Debut']); ?> | Fin: <?php echo htmlspecialchars($tache['Fin']); ?></p>
                        <p>État: <?php echo $tache['état'] ? 'Terminée' : 'En cours'; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="comment-section">
            <h3>Commentaires</h3>
            <ul>
                <?php foreach ($commentaires as $commentaire): ?>
                    <li>
                        <p><?php echo htmlspecialchars($commentaire['Message']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form method="POST" action="add_comment.php">
                <textarea name="message" placeholder="Ajouter un commentaire" required></textarea>
                <input type="hidden" name="projet_id" value="<?php echo $projetId; ?>">
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <div class="file-section">
            <h3>Fichiers</h3>
            <ul>
                <?php foreach ($fichiers as $fichier): ?>
                    <li>
                        <a href="uploads/<?php echo htmlspecialchars($fichier['Nom']); ?>"><?php echo htmlspecialchars($fichier['Nom']); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form method="POST" action="upload_file.php" enctype="multipart/form-data">
                <input type="file" name="file" required>
                <input type="hidden" name="projet_id" value="<?php echo $projetId; ?>">
                <button type="submit">Téléverser</button>
            </form>
        </div>
    </div>
</body>
</html>
