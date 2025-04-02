<?php include ("Pilote.php"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web4ALL espace pilote</title>
    <link rel="stylesheet" href="style-web.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="body">
<header>
    <nav class="navbar">
        <div class="logo">
            <img src="assets/logo.png" alt="Web4ALL Logo">
        </div>

        <div class="services-dropdown">
            <button class="hamburger-menu">
                <span class="hamburger-icon"></span>
                Services
            </button>
            <div class="dropdown-content">
                <a href="accueil.html"><i class="fas fa-home"></i> Accueil</a>
                <a href="a-propos.html"><i class="fas fa-info-circle"></i> À propos de nous</a>
                <a href="avis.html"><i class="fas fa-star"></i> Avis</a>
                <a href="recherche.html"><i class="fas fa-search"></i> Recherche</a>
                <a href="espace-tuteur.html"><i class="fas fa-chalkboard-teacher"></i> Espace Tuteur</a>
                <a href="mon-compte-etudiant.html"><i class="fas fa-user-graduate"></i> Mon compte Étudiant</a>
                <a href="mon-compte-tuteur.html"><i class="fas fa-user-tie"></i> Mon compte Tuteur</a>
            </div>
        </div>
        <h1> P.A.I.J </h1>
        <div class="right-menu">
            <a href="connexion.html" class="nav-button active"><i class="fas fa-sign-in-alt"></i> Connexion</a>
            <a href="espace-recruteur.html" class="nav-button"><i class="fas fa-briefcase"></i> Espace recruteur</a>
        </div>
    </nav>
</header>

<div class="containerpilote">
    <div class="sidebar">
    <div class="tuteur">
        <img src="profil.png" alt="Tuteur">
        <h2 class="titre-connexion-purple">Tuteur</h2>
    </div>

    <div class="promo">

<?php
    $db = new Pilote('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');    
    $promos = json_decode($db->getAllPromotions());

    if (is_array($promos)) {
        foreach ($promos as $promo) {
            echo '<div class="promo-item">';
            echo '<button class="toggle-btn">';
            echo htmlspecialchars($promo->{"Nom-promo"});
            echo '<span>➕</span></button>';
            echo '<ul class="eleve-list">';

            $students = json_decode($db->getStudentByPromo($promo->{"ID-promo"}));

            if (is_array($students) && !empty($students)) {
                foreach ($students as $student) {
                    echo '<li>' . htmlspecialchars($student->{"Prenom-etudiant"}) . ' | ' . htmlspecialchars($student->{"Nom-etudiant"}) . '</li>';
                }
            } else {
                echo '<li>Aucun étudiant trouvé pour cette promotion.</li>';
            }

            echo '</ul>';
            echo '</div>';
        }
    } else {
        echo '<p>Erreur : Impossible de récupérer les promotions.</p>';
    }
?>

    


    <script src="espace_pilote.js"></script>
</div>
    <div class="wrapper">
    <h2 class="titre-connexion-purple">Statistiques des Stages</h2>

    <canvas id="stageChart"></canvas>

    <button id="filterBtn">Ajouter un filtre</button>

    <div class="filters" id="filterContainer">
        <button class="filter" data-filter="all">Tous les stagiaires</button>
        <button class="filter" data-filter="btp">BTP</button>
        <button class="filter" data-filter="informatique">Informatique</button>
        <button class="filter" data-filter="generaliste">Généraliste</button>
    </div>
        <script src="espace_pilote_pourc.php"></script>
    </div>
</div>
<footer>
    <div class="footer-content">
        <p>© 2025 P.A.I.J - Tous droits réservés</p>
        <div class="footer-links">
            <a href="mentions.html">Politique relative aux cookies</a>
            <a href="mentions.html">Politique de confidentialité</a>
            <a href="mentions.html">Conditions d'utilisation</a>
        </div>
    </div>
</footer>
</body>
</html>