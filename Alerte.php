
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

        function checkDeadlinesAndBudgets() {
            // Simuler la vérification des délais et des budgets
            var deadlinesApproaching = true; // Changer pour vérifier si les délais approchent
            var budgetsAlmostReached = true; // Changer pour vérifier si les budgets sont bientôt atteints

            // Afficher les notifications si nécessaire
            if (deadlinesApproaching) {
                document.getElementById('deadline-notification').style.display = 'block';
            }
            if (budgetsAlmostReached) {
                document.getElementById('budget-notification').style.display = 'block';
            }
        }

        function checkNotifications() {
            // Simuler la vérification des notifications
            var deadlinesApproaching = true; // Changer pour vérifier si les délais approchent
            var budgetsAlmostReached = true; // Changer pour vérifier si les budgets sont bientôt atteints
            var commentsMade = true; // Changer pour vérifier si des commentaires ont été réalisés

            // Afficher les notifications si nécessaire
            if (deadlinesApproaching) {
                document.getElementById('deadline-notification').style.display = 'block';
            }
            if (budgetsAlmostReached) {
                document.getElementById('budget-notification').style.display = 'block';
            }
            if (commentsMade) {
                document.getElementById('comment-notification').style.display = 'block';
            }
        }
        
        // Appeler les fonctions une fois que le DOM est chargé
        document.addEventListener("DOMContentLoaded", function(event) {
            checkDeadlinesAndBudgets();
            checkNotifications();
        });
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
                <li><a href="Alerte.php">Notifications</a></li>
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
    <h2 class="accueil">Notifications, Alertes et Rappels</h2>
    <div class="notification-container">
        <!-- Notification pour les commentaires -->
        <div id="comment-notification" class="notification" style="display: none;">
            <h3>Notifications: commentaires ajoutés</h3>
            <p>Des commentaires ont été ajoutés à certains projets.</p>
        </div>

        <!-- Notification pour les budgets bientôt atteints -->
        <div id="budget-notification" class="notification" style="display: none;">
            <h3>Notifications: Budgets Bientôt Atteints</h3>
            <p>Les budgets alloués sont bientôt atteints pour certains projets.</p>
        </div>

        <!-- Notification pour les délais approchants -->
        <div id="deadline-notification" class="notification" style="display: none;">
            <h3 style="color: red;">Notifications: Délais Approchants</h3>
            <p>Les délais approchent pour certains projets.</p>
        </div>
    </div>
 
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