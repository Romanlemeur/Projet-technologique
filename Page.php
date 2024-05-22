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
            <div>
                <?php 
                        include('config.php');

                        function getTasksForCurrentWeek($conn) {
                        // Obtention de la date de début et de fin de la semaine actuelle
                        $startDate = date('Y-m-d', strtotime('monday this week'));
                        $endDate = date('Y-m-d', strtotime('sunday this week'));
                            
                        $nom = $_SESSION['nom'] ;
                        // Requête pour récupérer les tâches pour la semaine actuelle
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
                        
                        // Récupération des tâches de la base de données
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $tasks[] = $row;
                            }
                        }
                        
                        return $tasks;
                    }

                    function generateColorFromId($id) {
                        // Convert the ID to a hexadecimal string and use it to generate a color
                        $hash = md5($id); // Create a hash from the ID
                        $color = substr($hash, 0, 6); // Use the first 6 characters as the color code
                        return '#' . $color;
                    }
                    
                    // Fonction pour générer le calendrier de la semaine actuelle avec les tâches
                    function generateCalendarForCurrentWeek($conn) {
                        // Obtention de la liste des tâches pour la semaine actuelle
                        $tasks = getTasksForCurrentWeek($conn);
                    
                        // Génération du calendrier
                        $calendar = '<table class="calendar-table">';
                        $calendar .= '<thead><tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr></thead>';
                        $calendar .= '<tbody>';
                    
                        $currentDate = strtotime('monday this week'); // Date de début de la semaine actuelle
                    
                        for ($i = 0; $i < 1; $i++) {
                            $calendar .= '<tr>';
                            for ($j = 0; $j < 7; $j++) {
                                $calendar .= '<td>';
                                $calendar .= '<div class="date">' . date('j', $currentDate) . '</div>';
                    
                                // Vérifier s'il y a des tâches pour cette date
                                $tasksForDate = array_filter($tasks, function($task) use ($currentDate) {
                                    return strtotime($task['Debut']) <= $currentDate && strtotime($task['Fin']) >= $currentDate;
                                });
                    
                                // Afficher les tâches pour cette date
                                foreach ($tasksForDate as $task) {
                                    $color = generateColorFromId($task['ID_Tache']);
                                    $calendar .= "<div class='task' style='background-color : $color'>";
                                    $calendar .= "<strong>{$task['Titre']}</strong><br>";
                                    $calendar .= "<span>Collaborateurs: {$task['Collaborateurs']}</span>";
                                    $calendar .= "</div>";
                                }
                    
                                $calendar .= '</td>';
                                $currentDate = strtotime('+1 day', $currentDate); // Passer au jour suivant
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
