<?php 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) 
{
    $_SESSION['email'] = $_POST['email'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'])) 
{
    $_SESSION['nom'] = $_POST['nom'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prenom'])) 
{
    $_SESSION['prenom'] = $_POST['prenom'];
}
if ($_SERVER['telephone'] === 'POST'&& isset($_POST['telephone'])) 
{
    $_SESSION['telephone'] = $_POST['telephone'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && $_POST['password'] === $_POST['confirmPassword']) 
{
    $_SESSION['password'] = $_POST['password'];
}
if ($_SERVER['Date'] === 'POST'&& isset($_POST['Date'])) 
{
    $_SESSION['Date'] = $_POST['Date'];
}

$create = new Etudiant('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');
$create->addStudent([$_SESSION['prenom'], $_SESSION['nom'], $_SESSION['email'], $_SESSION['password'], $_SESSION['telephone'], $_SESSION['Date'], null, null]);

?>