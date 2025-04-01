<?php 
include("Etudiant.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $_SESSION['email'] = $_POST['email'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) 
{
    $_SESSION['name'] = $_POST['name'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname'])) 
{
    $_SESSION['firstname'] = $_POST['firstname'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['telephone'])) 
{
    $_SESSION['telephone'] = $_POST['telephone'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password'] === $_POST['confirmPassword']) 
{
    $_SESSION['password'] = $_POST['password'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['Date'])) 
{
    $_SESSION['Date'] = $_POST['Date'];
}

$create = new Etudiant('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

$create->addStudent([
    'firstname' => $_SESSION['firstname'],
    'lastname' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'password' => $_SESSION['password'],
    'telephone' => null,
    'date' => $_SESSION['Date'],
    'path' => null,
    'idpromo' => null,
]);

// Redirection vers la page d'accueil après l'inscription
header("Location: accueil.html?inscription=success");
exit();
?>