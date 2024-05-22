<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        // Connexion
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];

        $sql = "SELECT * FROM Collaborateur WHERE Mail='$mail'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (($mdp == $row['Mdp'])) {
                $_SESSION['id_collaborateur'] = $row['ID_Collaborateur'];
                $_SESSION['nom'] = $row['Nom'];
                header("Location: Page.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Mot de passe incorrect";
                header("Location: loginPage.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Aucun utilisateur trouvé avec cet email";
            header("Location: loginPage.php");
            exit();
        }
    } elseif (isset($_POST['register'])) {
        // Inscription
        $nom = $_POST['nom'];
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $role = $_POST['role'];

        $sql = "INSERT INTO Collaborateur (Nom, Mail, Mdp, Role) VALUES ('$nom', '$mail', '$mdp', '$role')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Inscription réussie, veuillez vous connecter.";
            header("Location: loginPage.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Erreur : " . $sql . "<br>" . $conn->error;
            header("Location: loginPage.php");
            exit();
        }
    }
}

$conn->close();
?>
