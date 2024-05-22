<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .register-container {
            display: none; /* Cacher le formulaire d'inscription par défaut */
        }
    </style>
    <script>
        function toggleRegisterForm() {
            var loginForm = document.getElementById('login-container');
            var registerForm = document.getElementById('register-container');
            if (registerForm.style.display === 'none') {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
            } else {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div id="login-container" class="login-container">
        <h2>Connexion</h2>
        <?php
        session_start();
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']); // Clear the error message after displaying it
        }
        ?>
        <form action="loginProcess.php" method="POST">
            <div class="input-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" required>
            </div>
            <div class="input-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <button type="submit" name="login">Se connecter</button>
        </form>
        <button onclick="toggleRegisterForm()">S'inscrire</button>
    </div>

    <div id="register-container" class="register-container">
        <h2>Inscription</h2>
        <form action="loginProcess.php" method="POST">
            <div class="input-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="input-group">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="mail" required>
            </div>
            <div class="input-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <div class="input-group">
                <label for="role">Role</label>
                <input type="text" id="role" name="role" required>
            <button type="submit" name="register">S'inscrire</button>
        </form>
        <button onclick="toggleRegisterForm()">Retour à la connexion</button>
    </div>
</body>
</html>
