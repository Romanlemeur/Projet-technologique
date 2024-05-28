<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php"); 
    exit();
}
$nomUtilisateur = $_SESSION['nom'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications, Alertes et Rappels</title>
    <link rel="stylesheet" href="Alerte.css">
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById('settings-tab');
            settingsTab.classList.toggle('visible');
        }
    </script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="image/logofirst.png" alt="Logo">
            <h1>Plano</h1>
        </div>
        <ul>
            <li><a href="adminpage.php">Accueil</a></li>
            <li><a href="adminprojet.php">Projet</a></li>
            <li><a href="adminCalendrier.php">Calendrier</a></li>
            <li><a href="adminAlerte.php">Notifications</a></li>
        </ul>
        <div class="profile-banner">
            <img src="image/user.png" alt="Profil">
            <div class="profile-info">
                <p><?php echo htmlspecialchars($_SESSION['nom']); ?></p>
                <div class="settings-button">
                    <a href="javascript:void(0);" onclick="toggleSettings()"><img src="image/settings.png" alt="Réglages"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <h2 class="accueil">Notifications, Alertes et Rappels</h2>
        <div class="alerts-container">
            <div class="alert-section">
                <h3>Notifications</h3>
                <ul id="notifications-list">
                </ul>
            </div>
            <div class="alert-section">
                <h3>Tâches en retard</h3>
                <ul id="late-tasks-list">
                </ul>
            </div>
            <div class="alert-section">
                <h3>Dépassements budgétaires critiques</h3>
                <ul id="budget-overruns-list">
                </ul>
            </div>
        </div>
    </div>
    
    <div id="settings-tab" class="settings-tab">
        <div class="profile-section">
            <img src="image/user.png" alt="Profil">
            <p><?php echo htmlspecialchars($_SESSION['nom']); ?></p>
            <input type="text" placeholder="Nouveau mot de passe">
            <input type="text" placeholder="Confirmer le mot de passe">
            <button>Nouveau Mot de Passe</button>
        </div>
        <button class="logout-button" onclick="redirectTo('logout.php')">Déconnexion</button>
        <button class="suppri-button" onclick="redirectTo('supprimer_compte.php')">Supprimer le compte</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchNotifications();
            fetchLateTasks();
            fetchBudgetOverruns();
        });

        function fetchNotifications() {
            fetch('get_notifications.php')
                .then(response => response.json())
                .then(data => {
                    const notificationsList = document.getElementById('notifications-list');
                    data.forEach(notification => {
                        const li = document.createElement('li');
                        li.textContent = `${notification.Titre}: ${notification.Contenu}`;
                        notificationsList.appendChild(li);
                    });
                });
        }

        function fetchLateTasks() {
            fetch('get_late_tasks.php')
                .then(response => response.json())
                .then(data => {
                    const lateTasksList = document.getElementById('late-tasks-list');
                    data.forEach(task => {
                        const li = document.createElement('li');
                        li.textContent = `${task.Titre}: ${task.Description}`;
                        lateTasksList.appendChild(li);
                    });
                });
        }

        function fetchBudgetOverruns() {
            fetch('get_budget_overruns.php')
                .then(response => response.json())
                .then(data => {
                    const budgetOverrunsList = document.getElementById('budget-overruns-list');
                    data.forEach(project => {
                        const li = document.createElement('li');
                        li.textContent = `${project.Titre}: Dépassement de ${project.Depassement}€`;
                        budgetOverrunsList.appendChild(li);
                    });
                });
        }
    </script>
</body>
</html>
