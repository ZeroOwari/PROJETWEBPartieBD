<?php

include("Search.php");

function printPagination($itemPerPage, $offre) {
    $offre = json_decode($offre, true);

    // Check if decoding was successful
    if (!is_array($offre)) {
        die("Erreur : Les données fournies ne sont pas valides.");
    }

    $TotalElem = count($offre);
    $NbPages = ceil($TotalElem / $itemPerPage);

    // Get the current page from the URL, default to 1 if not set
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $NbPages ? (int)$_GET['page'] : 1;

    // Calculate the start index for the current page
    $start = ($page - 1) * $itemPerPage;

    // Slice the data for the current page
    $Page_Slice = array_slice($offre, $start, $itemPerPage);

    // Display the offers for the current page
    if (!empty($Page_Slice)) {
        echo "<div class='container'>"; // Open container div
        foreach ($Page_Slice as $offre) {
            // Secure variables against XSS
            $nom = htmlspecialchars($offre['Nom-offre']);
            $ville = htmlspecialchars($offre['Localisation-offre']);
            $secteur = htmlspecialchars($offre['Secteur-offre']);
            $description = htmlspecialchars($offre['Description-offre']);
            $competence = htmlspecialchars($offre['Competences-offre']);
            $debut = htmlspecialchars($offre['Debut-offre']);
            $fin = htmlspecialchars($offre['Fin-offre']);
            $nomEntreprise = htmlspecialchars($offre['Nom-entreprise']);
            $logo = htmlspecialchars($offre['CheminImage-entreprise']);
            $descriptionEntreprise = htmlspecialchars($offre['Description-entreprise']);

            $_SESSION['nom'] = $nom;
            $_SESSION['ville'] = $ville;
            $_SESSION['secteur'] = $secteur;
            $_SESSION['description'] = $description;
            $_SESSION['competence'] = $competence;
            $_SESSION['debut'] = $debut;
            $_SESSION['fin'] = $fin;


            // Create a unique ID for the popup
            $entreprise_id = str_replace(' ', '_', $nom);

            echo "<div class='Base-page_de_recherche'>";
            echo "<div class='Nom_de_l_annonce_page_de_recherche'>$nom</div>";

            echo "<div class='icon_avatar'><img width='30' src='image/icon_avatar.png' alt='Icone utilisateur'></div>";
            echo "<div class='icon_localisation'><img width='15' src='image/icon_map_ping.png' alt='Icone Localisation'></div>";
            echo "<div class='icon_malette'><img width='15' src='image/icon_malette.png' alt='Icone Malette'></div>";
            echo "<div class='icon_download'><img width='18' src='image/icon_download.png' alt='Icone Download'></div>";
            echo "<div class='icon_partager'><img width='11' src='image/icon_partager.png' alt='Icone Partager'></div>";

            echo "<div class='carre_description_page_de_recherche'>$description</div>";
            echo "<div class='carre_localisation_page_de_recherche'>$ville</div>";
            echo "<div class='carre_nom_de_lentreprise_page_de_recherche'>$nomEntreprise</div>";
            echo "<button class='btn' onclick='ouvrirPopup(\"popup_$entreprise_id\")'></button>";

            // Popup for the offer with skills
            echo "<div id='popup_$entreprise_id' class='modal'>";
            echo "<div class='modal-content'>";
            echo "<div>";
            echo "<div class='carre_noir'>$nom</div>";

            echo "<div class='logo_de_l_entreprise_popup'><img width='150' src='$logo'></div>";
            echo "<div class='nom_de_l_entreprise_popup'>$nomEntreprise :</div>";
            echo "<div class='titre_a_propos_de_l_entreprise_popup'>Date début : </div>";
            echo "<div class='competence_popup'> Date de publication :   $debut</div>";
            echo "<div class='titre_a_propos_de_l_entreprise_popup'>Date fin : </div>";
            echo "<div class='competence_popup'>Date de fin :  $fin</div>";
            echo "<div class='titre_a_propos_de_l_entreprise_popup'>Secteur d'activité : </div>";
            echo "<div class='competence_popup'>Secteur :  $secteur</div>";
            echo "<div class='titre_a_propos_de_l_entreprise_popup'>A Propos De l'Entreprise</div>";
            echo "<div class='competence_popup'>Localisation :  $ville</div>";
            echo "<div class='Qualification_popup'>Qualification :</div>";
            echo "<div class='competence_popup'>$competence</div>";

            echo "<div class='titre_a_propos_de_l_entreprise_popup'>A Propos De l'Entreprise</div>";
            echo "<div class='competence_popup'>$descriptionEntreprise</div>";
            echo "<a class='btn' href='Postuler.php'>Postuler</a>";
            echo "<a class='btn' href='ModifierOffre.php'>modifier</a>";


            echo "</div>";
            echo "<div class='modal-footer'></div>";
            echo "<span class='close' onclick='fermerPopup(\"popup_$entreprise_id\")'>&times;</span>";
            echo "</div>";
            echo "</div>";

            echo "<div class='panneau'>";
            echo "<div class='like'></div>";
            echo "</div>";

            echo "<div class='notifications'></div>";

            echo "</div>"; // End .Base-page_de_recherche
        }
        echo "</div>"; // End .container
    } else {
        echo "<p>Aucune entreprise à afficher.</p>";
    }

    // Display pagination buttons
    echo "<div class='pagination'>";
    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '">Précédent</a> ';
    }
    if ($page < $NbPages) {
        echo '<a href="?page=' . ($page + 1) . '">Suivant</a>';
    }
    echo "</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nbrOffer']) && $_POST['nbrOffer'] != '') {
    $limit = $_POST['nbrOffer']; 
} 
else {
    $limit = 1; // Valeur par défaut
}

printPagination($limit, $recherche);

?>