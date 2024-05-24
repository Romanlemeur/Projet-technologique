<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header("Location: loginPage.php"); 
    exit();
}
include('config.php');
$nomUtilisateur = $_SESSION['nom'];
function getTasks($conn, $date) {
    $sql = "SELECT Tache.*, GROUP_CONCAT(Collaborateur.Nom SEPARATOR ', ') as Collaborateurs
            FROM Tache
            LEFT JOIN Tache_Collaborateur ON Tache.ID_Tache = Tache_Collaborateur.ID_Tache
            LEFT JOIN Collaborateur ON Tache_Collaborateur.ID_Collaborateur = Collaborateur.ID_Collaborateur
            WHERE '$date' BETWEEN Debut AND Fin
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

function generateCalendar($conn, $year, $month) {
    $calendar = '<table class="calendar-table">';
    $calendar .= '<thead><tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr></thead>';
    $calendar .= '<tbody>';

    $firstDayOfMonth = new DateTime("$year-$month-01");
    $firstDayOfMonth->modify('first day of this month');
    $daysInMonth = $firstDayOfMonth->format('t');
    $firstDayOfWeek = ($firstDayOfMonth->format('N') + 6) % 7; 
    $date = 1 - $firstDayOfWeek;

    for ($i = 0; $i < 6; $i++) {
        $calendar .= '<tr>';
        for ($j = 0; $j < 7; $j++) {
            if ($date > 0 && $date <= $daysInMonth) {
                $currentDate = "$year-$month-" . str_pad($date, 2, '0', STR_PAD_LEFT);
                $tasks = getTasks($conn, $currentDate);
                $calendar .= '<td>';
                $calendar .= "<div class='date'>$date</div>";
                foreach ($tasks as $task) {
                    $color = generateColorFromId($task['ID_Tache']);
                    $calendar .= "<div class='task' style='background-color: $color'>";
                    $calendar .= "<strong>{$task['Titre']}</strong><br>";
                    $calendar .= "<span>Collaborateurs: {$task['Collaborateurs']}</span>";
                    $calendar .= "</div>";
                }
                $calendar .= '</td>';
            } else {
                $calendar .= '<td></td>';
            }
            $date++;
        }
        $calendar .= '</tr>';
    }

    $calendar .= '</tbody>';
    $calendar .= '</table>';

    return $calendar;
}

$year = date('Y');
$month = date('m');

if (isset($_GET['year'])) {
    $year = $_GET['year'];
}

if (isset($_GET['month'])) {
    $month = $_GET['month'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>   
    <link rel="stylesheet" href="calendar.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById("settings-tab");
            settingsTab.style.display = settingsTab.style.display === "block" ? "none" : "block";
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

    <div class="main-container">
        <div class="main-content">
            <h1>Calendrier de <?php echo "$month/$year" ?></h1>
            <?php echo generateCalendar($conn, $year, $month); ?>
            <div class="calendar-navigation">
                <a href="?year=<?php echo $year - 1; ?>&month=<?php echo $month; ?>">Année précédente</a> |
                <a href="?year=<?php echo $year + 1; ?>&month=<?php echo $month; ?>">Année suivante</a> |
                <a href="?year=<?php echo $year; ?>&month=<?php echo $month - 1; ?>">Mois précédent</a> |
                <a href="?year=<?php echo $year; ?>&month=<?php echo $month + 1; ?>">Mois suivant</a>
            </div>

            <h1>Ajouter une tâche</h1>
            <?php
            $sql = "SELECT ID_Collaborateur, Nom FROM Collaborateur";
            $result = $conn->query($sql);
            $collaborateurs = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $collaborateurs[] = $row;
                }
            }
            ?>
            <form action="addTask.php" method="POST">
                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" required>

                <label for="debut">Date de début:</label>
                <input type="text" id="debut" name="debut" required>

                <label for="fin">Date de fin:</label>
                <input type="text" id="fin" name="fin" required>

                <label for="collaborateurs">Collaborateurs:</label>
                <select id="collaborateurs" name="collaborateurs[]" multiple="multiple" required>
                    <?php foreach ($collaborateurs as $collaborateur): ?>
                        <option value="<?php echo $collaborateur['ID_Collaborateur']; ?>">
                            <?php echo $collaborateur['Nom']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Ajouter</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#collaborateurs').select2({
                placeholder: "Sélectionner des collaborateurs",
                allowClear: true
            });

            $('#debut, #fin').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
    <script>
        function toggleSettings() {
            var settingsTab = document.getElementById('settings-tab');
            settingsTab.classList.toggle('visible');
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>



