<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}
$nomUtilisateur = $_SESSION['nom'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets</title>
    <link rel="stylesheet" href="projet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById('settings-tab');
            settingsTab.classList.toggle('visible');
        }
        function updatePassword() {
            var newPassword = document.getElementById('new-password').value;
            var confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword === confirmPassword) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "update_password.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Mot de passe mis à jour avec succès");
                    }
                };
                xhr.send("newPassword=" + encodeURIComponent(newPassword));
            } else {
                alert("Les mots de passe ne correspondent pas");
            }
        }

        window.onload = function() {
            var ctx = document.getElementById('projectsChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Projets terminés',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        data: [0, 10, 5, 2, 20, 30, 45, 40, 50, 60, 70, 80]
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Temps (mois)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Projets terminés'
                            }
                        }
                    }
                }
            });
        };
    </script>
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
                <div class="settings-button">
                    <a href="javascript:void(0);" onclick="toggleSettings()"><img src="image/settings.png" alt="Réglages"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="projects-container">
            <h2>Projets en cours</h2>
            <div class="filter-section">
                <input type="text" placeholder="Rechercher...">
            </div>

            <?php
                try {
                    // Établir une connexion à la base de données
                    $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Exécuter une requête SQL pour récupérer les projets
                    $stmt = $pdo->query('SELECT * FROM Projet');
                    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

                } catch (PDOException $e) {
                    echo 'Erreur : ' . $e->getMessage();
                }
            ?>

            <div class="project-list-container" style="max-height: 500px; overflow-y: auto;">
                <ul class="project-list">
                    <?php foreach ($projets as $projet): ?>
                        <?php
                            // Récupérer le nombre total de tâches et le nombre de tâches terminées pour chaque projet
                            $stmt = $pdo->prepare('SELECT COUNT(*) as total, SUM(état) as completed FROM Tache WHERE Projet = ?');
                            $stmt->execute([$projet['ID_Projet']]);
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                            $totalTaches = $result['total'];
                            $tachesTerminees = $result['completed'];
                            $avancement = ($totalTaches > 0) ? ($tachesTerminees / $totalTaches) * 100 : 0;
                        ?>
                        <li class="project-item success">
                            <div class="info">
                                <h3><?= htmlspecialchars($projet['Titre']) ?></h3>
                                <p>Description: <?= htmlspecialchars($projet['Description']) ?></p>
                                <p>Début: <?= htmlspecialchars($projet['Debut']) ?> | Fin: <?= htmlspecialchars($projet['Fin']) ?></p>
                                <p>Objectif: <?= htmlspecialchars($projet['Objectif']) ?></p>
                                <p>Avancement: <?= number_format($avancement, 2) ?>%</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?= number_format($avancement, 2) ?>%;"></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="projectsChart"></canvas>
        </div>
    </div>
    <div id="settings-tab" class="settings-tab">
        <div class="profile-section">
            <img src="image/user.png" alt="Profil">
            <p><?php echo htmlspecialchars($nomUtilisateur); ?></p>
            <input type="password" id="new-password" placeholder="Nouveau mot de passe">
            <input type="password" id="confirm-password" placeholder="Confirmer le mot de passe">
            <button onclick="updatePassword()">Nouveau Mot de Passe</button>
        </div>
        <button class="logout-button" onclick="redirectTo('logout.php')">Déconnexion</button>
        <button class="suppri-button" onclick="redirectTo('supprimer_compte.php')">Supprimer le compte</button>
    </div>
</body>
</html>
