<?php
session_start();
include("Etudiant.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $email = $_POST['email'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password']) 
{
    $password = $_POST['password'];
}
    
    $db = new Etudiant('mysql:host=localhost;dbname=web4all', 'TOtime', 'password0508');
    
    if ($db->checkLogValidation([
        'email' => $email,
        'password' => $password,
    ])) {
        // Authentification réussie
        $_SESSION['email'] = $email;
    
        // Redirection vers la page d'accueil
        //header("Location: ../index.php?login=success");
        exit();
    } else {
        // Authentification échouée
        echo "Email ou mot de passe incorrect.";
    }
?>