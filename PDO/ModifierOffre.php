<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>P.A.I.J - Informations</title>
  <link rel="stylesheet" href="assets/style-web.css">
  <style>
    /* Styles spécifiques pour cette page */
    .account-container {
      max-width: 900px;
      margin: 40px auto;
      display: flex;
      gap: 30px;
    }
    .account-avatar {
      width: 200px;
      text-align: center;
    }
    .account-avatar img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #7B8EEE;
    }
    .account-details {
      flex: 1;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(97, 91, 155, 0.1);
    }
    .detail-row {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 20px;
      border-bottom: 1px solid #eee;
    }
    .detail-row p {
      flex: 1;
      margin: 0;
      color: #5D5897;
      font-size: 16px;
    }
    .detail-row button {
      background: none;
      border: none;
      cursor: pointer;
      font-size: 20px;
      color: #7B8EEE;
      transition: transform 0.2s;
    }
    .detail-row button:hover {
      transform: scale(1.2);
      color: #5A1763;
    }
    /* Masquer les champs de saisie par défaut */
    .edit-field {
      display: none;
      margin-left: 10px;
    }
    @media (max-width: 768px) {
      .account-container {
        flex-direction: column;
        padding: 20px;
      }
      .account-avatar {
        margin: 0 auto;
      }
    }
  </style>
  <script>
    // Fonction qui bascule l'affichage entre le <span> et le champ <input>
    function toggleEdit(field) {
      var displaySpan = document.getElementById(field + '-display');
      var inputField = document.getElementById(field + '-input');
      if (inputField.style.display === 'none' || inputField.style.display === '') {
        inputField.style.display = 'inline-block';
        displaySpan.style.display = 'none';
      } else {
        inputField.style.display = 'none';
        displaySpan.style.display = 'inline';
      }
    }
  </script>
</head>
<body class="cmpt">
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
          <a href="choix-profil.html">Accueil</a>
          <a href="a-propos.html">À propos de nous</a>
          <a href="avis.html">Avis</a>
          <a href="recherche.html">Recherche</a>
          <a href="espace-tuteur.html">Espace Tuteur</a>
          <a href="mon-compte-etudiant.html">Mon compte Étudiant</a>
          <a href="mon-compte-tuteur.html">Mon compte Tuteur</a>
        </div>
      </div>
      <div class="right-menu">
        <a href="connexion.html" class="nav-button">Connexion</a>
        <a href="espace-recruteur.html" class="nav-button">Espace recruteur</a>
      </div>
    </nav>
  </header>

  <section class="banner">
    <h1>Modifier l'offre</h1>
  </section>

  <div>
    <div class="account-container">
      <div class="account-avatar">
        <img src="assets/Avatar.png" alt="Avatar">
        <h3><?php echo $_SESSION['nom'],$_SESSION['lastname']?></h3>
      </div>
    <form class="infos-compte" action="ModifierOffre.php" method="POST">
        <div class="detail-row">
          <p><strong>Description :</strong> <span id="description-display"><?php echo $_SESSION['description'] ?? ''; ?></span></p>
          <input type="text" name="description" id="description-input" value="<?php echo $_SESSION['description'] ?? ''; ?>" class="edit-field" />
          <button type="button" onclick="toggleEdit('description')">✎</button>
        </div>
        <div class="detail-row">
          <p><strong>Competences :</strong> <span id="competences-display"><?php echo $_SESSION['competences'] ?? ''; ?></span></p>
          <input type="text" name="competences" id="competences-input" value="<?php echo $_SESSION['competences'] ?? ''; ?>" class="edit-field" />
          <button type="button" onclick="toggleEdit('telephone')">✎</button>
        </div>
        <div class="detail-row">
          <p><strong>Date début :</strong> <span id="debut-display"><?php echo $_SESSION['debut'] ?? ''; ?></span></p>
          <input type="date" name="debut" id="debut-input" value="<?php echo $_SESSION['debut'] ?? ''; ?>" class="edit-field" />
          <button type="button" onclick="toggleEdit('debut')">✎</button>
        </div>
        <div class="detail-row">
          <p><strong>Date fin :</strong> <span id="fin-display"><?php echo $_SESSION['fin'] ?? ''; ?></span></p>
          <input type="date" name="fin" id="fin-input" value="<?php echo $_SESSION['fin'] ?? ''; ?>" class="edit-field" />
          <button type="button" onclick="toggleEdit('fin')">✎</button>
        </div>
        <div class="detail-row">
          <p><strong>Ville :</strong> <span id="ville-display"><?php echo $_SESSION['ville'] ?? ''; ?></span></p>
          <input type="text" name="ville" id="ville-input" value="<?php echo $_SESSION['ville'] ?? ''; ?>" class="edit-field" />
          <button type="button" onclick="toggleEdit('ville')">✎</button>
        </div>

        <div class="detail-row">
          <button type="submit">Enregistrer</button>
        </div>
    </form>
    </div>
  </div>

  <footer>
    <div class="footer-content">
      <p>Web4ALL - 2025</p>
      <p><a id="footer" href="mentions.html">Tous droits réservés 2025 © Politique relative aux cookies, politique de confidentialité et conditions d'utilisation</a></p>
    </div>
  </footer>
</body>
</html>
