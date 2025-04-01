<?php


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keywords'])) {
    $keyword = $_POST['keywords'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location'])) {
    $location = $_POST['location'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contract'])) {
    $contract = $_POST['contract'];
}


//if ($_SESSION['log'] == 'Etudiant') {
    include("Etudiant.php");
    $search = new Etudiant('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');
    echo $search->matchingContent($keyword, $location, $contract);
    
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