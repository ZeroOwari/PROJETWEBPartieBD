<?php
session_start();
include("Etudiant.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Connexion à la base de données
    $db = new Etudiant('mysql:host=localhost;dbname=web4all', 'root', '');
    
    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['email'] = $user['email'];

        // Redirection vers la page d'accueil
        header("Location: ../index.php?login=success");
        exit();
    } else {
        // Identifiants incorrects
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
}
?>