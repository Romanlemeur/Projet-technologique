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
    <title>Accueil</title>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="Calendar.css">
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById('settings-tab');
            settingsTab.classList.toggle('visible');
        }

        function redirectTo(url) {
            window.location.href = url;
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
                    <li>Équipe 1</li>
                    <li>Équipe 2</li>
                    <li>Équipe 3</li>
                </ul>
                <button onclick="redirectTo('equipes.php')">Mes équipes</button>
            </div>
            <div class="section">
                <h3>Notifications</h3>
                <ul>
                    <li>Notification 1</li>
                    <li>Notification 2</li>
                    <li>Notification 3</li>
                </ul>
                <button onclick="redirectTo('alerte.php')">Voir toutes les notifications</button>
            </div>
        </div>
        <div class="calendar-section">
            <h3>Calendrier</h3>
            <div>
                <?php 
                        include('config.php');

                        function getTasksForCurrentWeek($conn) {
                        $startDate = date('Y-m-d', strtotime('monday this week'));
                        $endDate = date('Y-m-d', strtotime('sunday this week'));
                            
                        $nom = $_SESSION['nom'] ;
                        $sql = "SELECT Tache.*, GROUP_CONCAT(Collaborateur.Nom SEPARATOR ', ') as Collaborateurs
                        FROM Tache
                        LEFT JOIN Tache_Collaborateur ON Tache.ID_Tache = Tache_Collaborateur.ID_Tache
                        LEFT JOIN Collaborateur ON Tache_Collaborateur.ID_Collaborateur = Collaborateur.ID_Collaborateur
                        WHERE 
                        EXISTS (
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
                    
                        for ($i = 0; $i < 1; $i++) {
                            $calendar .= '<tr>';
                            for ($j = 0; $j < 7; $j++) {
                                $calendar .= '<td>';
                                $calendar .= '<div class="date">' . date('j', $currentDate) . '</div>';
                    
                                $tasksForDate = array_filter($tasks, function($task) use ($currentDate) {
                                    return strtotime($task['Debut']) <= $currentDate && strtotime($task['Fin']) >= $currentDate;
                                });
                    
                                foreach ($tasksForDate as $task) {
                                    $color = generateColorFromId($task['ID_Tache']);
                                    $calendar .= "<div class='task' style='background-color : $color'>";
                                    $calendar .= "<strong>{$task['Titre']}</strong><br>";
                                    $calendar .= "<span>Collaborateurs: {$task['Collaborateurs']}</span>";
                                    $calendar .= "</div>";
                                }
                    
                                $calendar .= '</td>';
                                $currentDate = strtotime('+1 day', $currentDate); 
                            }
                            $calendar .= '</tr>';
                        }
                    
                        $calendar .= '</tbody>';
                        $calendar .= '</table>';
                    
                        return $calendar;
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
            <input type="text" placeholder="Nouveau mot de passe">
            <input type="text" placeholder="Confirmer le mot de passe">
            <button>Nouveau Mot de Passe</button>
        </div>
        <button class="logout-button" onclick="redirectTo('logout.php')">Déconnexion</button>
        <button class="suppri-button" onclick="redirectTo('supprimer_compte.php')">Supprimer le compte</button>
    </div>
</body>
</html>

