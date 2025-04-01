<?php
session_start();
include("Etudiant.php");
echo 'test';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $_SESSION['email'] = $_POST['email'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password']) 
{
    $_SESSION['password'] = $_POST['password'];
}
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new Etudiant('mysql:host=localhost;dbname=web4all', 'root', '');
    
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