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
    <link rel="stylesheet" href="adminprojet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById('settings-tab');
            settingsTab.classList.toggle('visible');
        }

        window.onload = function() {
            var ctx = document.getElementById('projectsChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                    <button type="submit">Rechercher</button>
                </form>
            </div>

            <?php
                try {
                    // Établir une connexion à la base de données
                    $pdo = new PDO('mysql:host=localhost;dbname=plano', 'root', '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Vérifier si une recherche a été effectuée
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    if ($search) {
                        // Requête SQL avec une clause WHERE pour filtrer les projets par titre
                        $stmt = $pdo->prepare('SELECT * FROM Projet WHERE Titre LIKE ?');
                        $stmt->execute(['%' . $search . '%']);
                    } else {
                        // Requête SQL pour récupérer tous les projets
                        $stmt = $pdo->query('SELECT * FROM Projet');
                    }
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

                            // Calculer le délai restant
                            $dateFin = new DateTime($projet['Fin']);
                            $dateActuelle = new DateTime();
                            $interval = $dateActuelle->diff($dateFin);
                            $joursRestants = $interval->days;
                            if ($dateActuelle > $dateFin) {
                                $joursRestants *= -1; // Nombre de jours de retard
                            }

                            // Déterminer la classe du projet en fonction de l'avancement et du délai restant
                            $classeProjet = 'success';
                            if ($avancement < 75) {
                                if ($joursRestants < 30) {
                                    $classeProjet = 'danger';
                                } elseif ($joursRestants <= 60) {
                                    $classeProjet = 'warning';
                                }
                            }
                        ?>
                        <a href="projet_detail.php?id=<?= $projet['ID_Projet'] ?>" class="project-link">    
                            <li class="project-item <?= $classeProjet ?>">
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
                                    <div class="modify">
                                        <a href="#"><img src="image/edit.png" alt="Modifier"></a>
                                    </div>
                            </li>
                        </a>
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
            <input type="text" placeholder="Nouveau mot de passe">
            <input type="text" placeholder="Confirmer le mot de passe">
            <button>Nouveau Mot de Passe</button>
        </div>
        <button class="logout-button">Déconnexion</button>
        <button class="suppri-button">Supprimer le compte</button>
    </div>
</body>
</html>