<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $collaborateurs = $_POST['collaborateurs'];

    $sql = "INSERT INTO Tache (Titre, Debut, Fin) VALUES ('$titre', '$debut', '$fin')";
    if ($conn->query($sql) === TRUE) {
        $id_tache = $conn->insert_id;

        foreach ($collaborateurs as $id_collaborateur) {
            $sql = "INSERT INTO Tache_Collaborateur (ID_Tache, ID_Collaborateur) VALUES ('$id_tache', '$id_collaborateur')";
            $conn->query($sql);
        }
        echo "Nouvelle tâche ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

    header("Location: Calendrier.php");
    exit();
}
?>