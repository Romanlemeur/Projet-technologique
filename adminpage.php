<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="adminpage.css">
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
            <li><a href="adminpage.php">Accueil</a></li>
            <li><a href="adminprojet.php">Projet</a></li>
            <li><a href="#">Calendrier</a></li>
            <li><a href="#">Notifications</a></li>
        </ul>
        <div class="profile-banner">
            <img src="image/user.png" alt="Profil">
            <div class="profile-info">
                <p>Admin</p>
                <div class="settings-button">
                    <a href="javascript:void(0);" onclick="toggleSettings()"><img src="image/settings.png" alt="Réglages"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <h2 class="accueil">Accueil</h2>
        <div class="sections-container">
                <?php
                    session_start();
                    include('config.php');

                    $sql = "SELECT * FROM Projet";
                    $result = $conn->query($sql);

                    $projets = [];

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $projets[] = $row;
                        }
                    }
                ?>   
                
                <div class="section">
                    <h3>Projets en cours</h3>
                    <ul>
                        <?php foreach ($projets as $projet): ?>
                            <li><?php echo $projet['Titre']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button>Gérer les projets</button>
                </div>
                <?php
                    include('config.php');

                    $sql = "SELECT * FROM Equipe";
                    $result = $conn->query($sql);

                    $equipes = [];

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $equipes[] = $row;
                        }
                    }
                ?> 
            <div class="section">
                <h3>Les équipes</h3>
                    <ul>
                        <?php foreach ($equipes as $equipe): ?>
                            <li><?php echo $equipe['Titre']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <button>Gérer Les équipes</button>
            </div>
            <?php
                    include('config.php');

                    $sql = "SELECT * FROM Notification";
                    $result = $conn->query($sql);

                    $notifications = [];

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $notifications[] = $row;
                        }
                    }
                ?> 
            <div class="section">
                <h3>Notifications</h3>
                <ul>
                    <?php foreach ($equipes as $equipe): ?>
                        <li><?php echo $equipe['Titre']; ?></li>
                    <?php endforeach; ?>
                </ul>
                <button>Voir toutes les notifications</button>
            </div>
        </div>
        <div class="calendar-section">
            <h3>Calendrier</h3>
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
                    WHERE Debut <= '$endDate' AND Fin >= '$startDate'
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
    <div id="settings-tab" class="settings-tab">
        <div class="profile-section">
            <img src="image/user.png" alt="Profil">
            <p>Admin</p>
            <input type="text" placeholder="Nouveau mot de passe">
            <input type="text" placeholder="Confirmer le mot de passe">
            <button>Nouveau Mot de Passe</button>
        </div>
        <button class="logout-button">Déconnexion</button>
    </div>
</body>
</html>