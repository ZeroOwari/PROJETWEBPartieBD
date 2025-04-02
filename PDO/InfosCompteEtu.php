<?php
session_start();
include("Etudiant.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $telephone = $_POST['telephone'] ?? null;
    $password = $_POST['password'] ?? null;
    $dateNaissance = $_POST['DateNaissance'] ?? null;

    // Valider les données (exemple : vérifier que l'email est valide)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email invalide.");
    }

    $db = new Etudiant('mysql:host=localhost;dbname=web4all', 'root', '');
    $result = $db->updateStudent($email, $telephone, $password, $dateNaissance);

    if ($result) {
        $_SESSION['email'] = $email;
        $_SESSION['telephone'] = $telephone;
        $_SESSION['DateNaissance'] = $dateNaissance;

        header("Location: InfosCompte.php?update=success");
        exit();
    } else {
        echo "Erreur lors de la mise à jour des informations.";
    }
}
?>