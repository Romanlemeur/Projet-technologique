<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="page.css">
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
            <li><a href="page.php">Accueil</a></li>
            <li><a href="projet.php">Projet</a></li>
            <li><a href="#">Calendrier</a></li>
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
        <h2 class="accueil">Accueil</h2>
        <div class="sections-container">
            <div class="section">
                <h3>Projets en cours</h3>
                <ul>
                    <li>Projet 1</li>
                    <li>Projet 2</li>
                    <li>Projet 3</li>
                </ul>
                <button>Mes projets</button>
            </div>
            <div class="section">
                <h3>Les équipes</h3>
                <ul>
                    <li>Équipe 1</li>
                    <li>Équipe 2</li>
                    <li>Équipe 3</li>
                </ul>
                <button>Mes équipes</button>
            </div>
            <div class="section">
                <h3>Notifications</h3>
                <ul>
                    <li>Notification 1</li>
                    <li>Notification 2</li>
                    <li>Notification 3</li>
                </ul>
                <button>Voir toutes les notifications</button>
            </div>
        </div>
        <div class="calendar-section">
            <h3>Calendrier</h3>
            <p>Contenu du calendrier...</p>
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
