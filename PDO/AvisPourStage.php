<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitEvaluation'])) {
    include("GestionEtu.php");
    $gestionEtu = new GestionEtu(new PDO('mysql:host=localhost;dbname=web4all', 'root', ''));

    $nom = $_POST['nom'] ?? null;
    $email = $_POST['email'] ?? null;
    $sujet = $_POST['sujet'] ?? null;
    $evaluation = $_POST['evaluation'] ?? null;
    $avis = $_POST['avis'] ?? null;

    if (!is_numeric($companyId) || $companyId <= 0 || !is_numeric($evaluation) || $evaluation < 1 || $evaluation > 5 || empty($nom) || empty($email) || empty($sujet) || empty($avis)) {
        echo "Données invalides. Veuillez remplir tous les champs correctement.";
        return;
    }

    try {
        $stmt = $gestionEtu->$pdo->prepare('INSERT INTO evaluations (`Nom`, `Email`, `Sujet`, `Evaluation`, `Avis`) VALUES (:nom, :email, :sujet, :evaluation, :avis)');
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':sujet', $sujet, PDO::PARAM_STR);
        $stmt->bindParam(':evaluation', $evaluation, PDO::PARAM_INT);
        $stmt->bindParam(':avis', $avis, PDO::PARAM_STR);
        if ($stmt->execute()) {
            echo "Évaluation enregistrée avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement de l'évaluation.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

?>
