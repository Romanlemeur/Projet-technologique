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
                <p>Nom du Profil</p>
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
                <select>
                    <option value="">Filtrer par équipe</option>
                    <option value="team1">Équipe 1</option>
                    <option value="team2">Équipe 2</option>
                </select>
                <select>
                    <option value="">Filtrer par client</option>
                    <option value="client1">Client 1</option>
                    <option value="client2">Client 2</option>
                </select>
                <select>
                    <option value="">Filtrer par priorité</option>
                    <option value="high">Haute</option>
                    <option value="medium">Moyenne</option>
                    <option value="low">Basse</option>
                </select>
                <input type="text" placeholder="Rechercher...">
            </div>
            <ul class="project-list">
                <li class="project-item success">
                    <div class="info">
                        <h3>Projet 1</h3>
                        <p>Responsable: Jean Dupont</p>
                        <p>Début: 01/01/2023 | Jalon: 3/5 | Délai: Respecté | Budget: Ok</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: 60%;"></div>
                    </div>
                </li>
                <li class="project-item warning">
                    <div class="info">
                        <h3>Projet 2</h3>
                        <p>Responsable: Marie Curie</p>
                        <p>Début: 15/02/2023 | Jalon: 2/5 | Délai: En retard | Budget: Ok</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: 40%;"></div>
                    </div>
                </li>
                <li class="project-item danger">
                    <div class="info">
                        <h3>Projet 3</h3>
                        <p>Responsable: Albert Einstein</p>
                        <p>Début: 01/03/2023 | Jalon: 1/5 | Délai: En retard | Budget: Dépassé</p>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: 20%;"></div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="chart-container">
            <canvas id="projectsChart"></canvas>
        </div>
    </div>
    <div id="settings-tab" class="settings-tab">
        <div class="profile-section">
            <img src="image/user.png" alt="Profil">
            <p>Nom du Profil</p>
            <input type="text" placeholder="Nouveau mot de passe">
            <input type="text" placeholder="Confirmer le mot de passe">
            <button>Nouveau Mot de Passe</button>
        </div>
        <button class="logout-button">Déconnexion</button>
        <button class="suppri-button">Supprimer le compte</button>
    </div>
</body>
</html>

