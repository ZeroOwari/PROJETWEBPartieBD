<?php
session_start();
include("Pilote.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $email = $_POST['email'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password']) 
{
    $password = $_POST['password'];
}
    
    $db = new Pilote('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

    if ($db->checkLogValidation([
        'email' => $email,
        'password' => $password,
    ])) {
        // Authentification réussie
        $_SESSION['email'] = $email;
    
        // Redirection vers la page d'accueil
        header("Location: accueil.html?login=success");
        exit();
    } else {
        // Authentification échouée
        echo "Email ou mot de passe incorrect.";
    }
?>