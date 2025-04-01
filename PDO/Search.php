<?php


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keywords'])) {
    $_SESSION['keywords'] = $_POST['keywords'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location'])) {
    $_SESSION['location'] = $_POST['location'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contract']) && $_POST['contract'] != 'Type de contrat') {
    $_SESSION['type'] = $_POST['contract'];
}


//if ($_SESSION['log'] == 'Etudiant') {
    include("Etudiant.php");
    $search = new Etudiant('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');
    echo $search->matchingContent($_SESSION['keywords'], $_SESSION['location'], $_SESSION['type']);
/*
} elseif ($_SESSION['log'] == 'Pilote') {
    include("Pilote.php");
    $search = new Pilote('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');
    $search->matchingContent('$_SESSION[keywords]', '$_SESSION[location]', '$_SESSION[type]');

} elseif ($_SESSION['log'] == 'Admin') {
    include("Admin.php");
    $search = new Admin('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');
    $search->matchingContent('$_SESSION[keywords]', '$_SESSION[location]', '$_SESSION[type]');

} else {
    exit("Invalid user type or not logged in.");
}
*/

?>