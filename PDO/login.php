<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo'])) 
{
    $_SESSION['pseudo'] = $_POST['pseudo'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['motDePasse'])) 
{
    $_SESSION['motDePasse'] = $_POST['motDePasse'];
}
if (isset($_SESSION['statutAdmin'])) 
{
    echo "Debug: statutAdmin = " . htmlspecialchars($_SESSION['statutAdmin']);
} else {
    echo "Debug: statutAdmin is not set.";
}


?>
