<?php
session_start();
// config.php
include('config.php');

function getTasks($conn, $date) {
    $nom = $_SESSION['nom'] ;

    $sql = "SELECT Tache.*, GROUP_CONCAT(Collaborateur.Nom SEPARATOR ', ') as Collaborateurs
            FROM Tache
            LEFT JOIN Tache_Collaborateur ON Tache.ID_Tache = Tache_Collaborateur.ID_Tache
            LEFT JOIN Collaborateur ON Tache_Collaborateur.ID_Collaborateur = Collaborateur.ID_Collaborateur
            WHERE '$date' BETWEEN Debut AND Fin
            AND EXISTS (
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
    $hash = md5($id); // Create a hash from the ID
    $color = substr($hash, 0, 6); // Use the first 6 characters as the color code
    return '#' . $color;
}

function generateCalendar($conn, $year, $month) {
    $calendar = '<table class="calendar-table">';
    $calendar .= '<thead><tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr></thead>';
    $calendar .= '<tbody>';

    $firstDayOfMonth = new DateTime("$year-$month-01");
    $firstDayOfMonth->modify('first day of this month');
    $daysInMonth = $firstDayOfMonth->format('t');
    $firstDayOfWeek = ($firstDayOfMonth->format('N') + 6) % 7; // Adjust for Monday as the first day
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
                    $calendar .= "<div class='task' style='background-color : $color'>";
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
    <link rel="stylesheet" href="Page.css">
    <link rel="stylesheet" href="Calendar.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <h1>Calendrier de <?php echo "$month/$year"?></h1>
    <?php echo generateCalendar($conn, $year, $month); ?>
    <div>
        <a href="?year=<?php echo $year - 1; ?>&month=<?php echo $month; ?>">Année précédente</a> |
        <a href="?year=<?php echo $year + 1; ?>&month=<?php echo $month; ?>">Année suivante</a> |
        <a href="?year=<?php echo $year; ?>&month=<?php echo $month - 1; ?>">Mois précédent</a> |
        <a href="?year=<?php echo $year; ?>&month=<?php echo $month + 1; ?>">Mois suivant</a>
    </div>

    <h1>Ajouter une tâche</h1>
    <?php
    include('config.php');

    // Récupérer la liste des collaborateurs pour le formulaire
    $sql = "SELECT ID_Collaborateur, Nom FROM Collaborateur";
    $result = $conn->query($sql);
    $collaborateurs = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
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

    <script>
        $(document).ready(function() {
            $('#collaborateurs').select2({
                placeholder: "Sélectionner des collaborateurs",
                allowClear: true
            });

            $('#debut, #fin').datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>

</body>
</html>
<?php $conn->close(); ?>