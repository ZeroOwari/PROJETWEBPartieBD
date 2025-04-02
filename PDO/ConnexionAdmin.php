<?php
    session_start();
    include("Admin.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
    {
        $email = $_POST['email'];
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password']) 
    {
        $password = $_POST['password'];
    }
        
    $db = new Admin('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');

    if ($session = $db->sessionLog($email , $password)){
        // Authentification réussie
        $_SESSION['firstname'] = $session[0];
        $_SESSION['name'] = $session[1];
        $_SESSION['email'] = $session[2];
        $_SESSION['password'] = $session[3];

        echo "Bonjour " . $_SESSION['firstname'] . " " . $_SESSION['name'] . ", vous êtes connecté en tant qu'administrateur.";
    
        // Redirection vers la page d'accueil
        header("Location: accueil.html?login=success");
        exit();
    } else {
        // Authentification échouée
        session_destroy(); // Détruire la session en cas d'échec
        header("Location: connexion-adm.html?login=failed");
    }
?>