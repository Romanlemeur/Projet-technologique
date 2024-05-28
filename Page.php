<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}
$nomUtilisateur = $_SESSION['nom'];

include('config.php');

function getEquipes($conn) {
    $sql = "SELECT Nom FROM Equipe";
    
    $result = $conn->query($sql);
    $equipes = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $equipes[] = $row['Nom'];
        }
    }
    return $equipes;
}

$equipes = getEquipes($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="Calendar.css">
    <style>
        .slide-up-panel, .slide-up-panel-equipes {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 70%;
            height: 85%;
            background-color: white;
            z-index: 1000;
            margin-left: 350px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            transform: translateY(100%);
        }

        .slide-up-panel.show, .slide-up-panel-equipes.show {
            display: block;
            transform: translateY(0);
        }

        .slide-up-panel-content, .slide-up-panel-equipes-content {
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fefefe;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .close-slide-up-panel, .close-slide-up-panel-equipes {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById('settings-tab');
            settingsTab.classList.toggle('visible');
        }

        function redirectTo(url) {
            window.location.href = url;
        }

        function openSlideUpPanel() {
            document.getElementById('slideUpPanel').classList.add('show');
        }

        function closeSlideUpPanel() {
            document.getElementById('slideUpPanel').classList.remove('show');
        }

        function openSlideUpPanelEquipes() {
            document.getElementById('slideUpPanelEquipes').classList.add('show');
        }

        function closeSlideUpPanelEquipes() {
            document.getElementById('slideUpPanelEquipes').classList.remove('show');
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
    </script>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="image/logofirst.png" alt="Logo">
            <h1>Plano</h1>
        </div>
        <ul>
            <li><a href="#" onclick="redirectTo('page.php')">Accueil</a></li>
            <li><a href="#" onclick="redirectTo('projet.php')">Projet</a></li>
            <li><a href="#" onclick="redirectTo('calendrier.php')">Calendrier</a></li>
            <li><a href="#" onclick="redirectTo('notifications.php')">Notifications</a></li>
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
        <h2 class="accueil">Accueil</h2>
        <div class="sections-container">
            <div class="section">
                <h3>Projets en cours</h3>
                <ul>
                    <li>Projet 1</li>
                    <li>Projet 2</li>
                    <li>Projet 3</li>
                </ul>
                <button onclick="redirectTo('Projet.php')">Mes projets</button>
            </div>
            <div class="section">
                <h3>Les équipes</h3>
                <ul>
                    <?php
                        foreach ($equipes as $equipe) {
                            echo "<li>$equipe</li>";
                        }
                    ?>
                </ul>
                <button onclick="openSlideUpPanelEquipes()">Voir les équipes</button>
            </div>
            <div class="section">
                <h3>Notifications</h3>
                <ul>
                    <li>Notification 1</li>
                    <li>Notification 2</li>
                    <li>Notification 3</li>
                </ul>
                <button onclick="redirectTo('Alerte.php')">Voir toutes les notifications</button>
            </div>
        </div>
        <div class="calendar-section">
            <h3>Calendrier</h3>
            <div>
                <?php 
                    function getTasksForCurrentWeek($conn) {
                        $startDate = date('Y-m-d', strtotime('monday this week'));
                        $endDate = date('Y-m-d', strtotime('sunday this week'));
                        
                        $nom = $_SESSION['nom'];
                        $sql = "SELECT Tache.*, GROUP_CONCAT(Collaborateur.Nom SEPARATOR ', ') as Collaborateurs
                                FROM Tache
                                LEFT JOIN Tache_Collaborateur ON Tache.ID_Tache = Tache_Collaborateur.ID_Tache
                                LEFT JOIN Collaborateur ON Tache_Collaborateur.ID_Collaborateur = Collaborateur.ID_Collaborateur
                                WHERE EXISTS (
                                    SELECT 1
                                    FROM Tache_Collaborateur AS TC
                                    WHERE TC.ID_Tache = Tache.ID_Tache
                                    AND TC.ID_Collaborateur = (SELECT ID_Collaborateur FROM Collaborateur WHERE Nom = '$nom')
                                )
                                GROUP BY Tache.ID_Tache";
                        
                        $result = $conn->query($sql);
                        $tasks = [];
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $tasks[] = $row;
                            }
                        }
                        
                        return $tasks;
                    }

                    function generateColorFromId($id) {
                        $hash = md5($id);
                        $color = substr($hash, 0, 6);
                        return '#' . $color;
                    }
                    
                    function generateCalendarForCurrentWeek($conn) {
                        $tasks = getTasksForCurrentWeek($conn);
                        $calendar = '<table class="calendar-table">';
                        $calendar .= '<thead><tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr></thead>';
                        $calendar .= '<tbody>';
                    
                        $currentDate = strtotime('monday this week');
                    
                        for ($i = 0; $i < 1; $calendar .= '<tr>', $i++) {
                            for ($j = 0; $j < 7; $j++) {
                                $calendar .= '<td><div class="date">' . date('j', $currentDate) . '</div>';
                                $tasksForDate = array_filter($tasks, fn($task) => strtotime($task['Debut']) <= $currentDate && strtotime($task['Fin']) >= $currentDate);
                    
                                foreach ($tasksForDate as $task) {
                                    $color = generateColorFromId($task['ID_Tache']);
                                    $calendar .= "<div class='task' style='background-color: $color'><strong>{$task['Titre']}</strong><br><span>Collaborateurs: {$task['Collaborateurs']}</span></div>";
                                }
                    
                                $calendar .= '</td>';
                                $currentDate = strtotime('+1 day', $currentDate);
                            }
                            $calendar .= '</tr>';
                        }
                    
                        return $calendar . '</tbody></table>';
                    }
                    echo generateCalendarForCurrentWeek($conn);
                    
                ?>
            </div>
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

    <!-- Slide Up Panel for "Mes équipes" -->
    <div id="slideUpPanel" class="slide-up-panel">
        <div class="slide-up-panel-content">
            <span class="close-slide-up-panel" onclick="closeSlideUpPanel()">&times;</span>
            <h2>Mes Équipes</h2>
            <?php
                foreach ($equipes as $equipe => $collaborateurs) {
                    echo "<h3>$equipe</h3><ul>";
                    foreach ($collaborateurs as $collaborateur) {
                        echo "<li>$collaborateur</li>";
                    }
                    echo "</ul>";
                }
            ?>
        </div>
    </div>

    <!-- Slide Up Panel for "Voir les équipes" -->
    <div id="slideUpPanelEquipes" class="slide-up-panel-equipes">
        <div class="slide-up-panel-equipes-content">
            <span class="close-slide-up-panel-equipes" onclick="closeSlideUpPanelEquipes()">&times;</span>
            <h2>Liste des Équipes</h2>
            <?php
                foreach ($equipes as $equipe => $collaborateurs) {
                    echo "<h3>$equipe</h3><ul>";
                    foreach ($collaborateurs as $collaborateur) {
                        echo "<li>$collaborateur</li>";
                    }
                    echo "</ul>";
                }
            ?>
        </div>
    </div>
</body>
</html>
