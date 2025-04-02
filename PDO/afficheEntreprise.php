<?php
function printPagination($limit) {
    $pagination = pagination($limit);

    // Decode the JSON string into an array
    $pagination = json_decode($pagination, true);

    // Check if the JSON decoding was successful
    if (!is_array($pagination)) {
        die("Erreur : La pagination n'a pas retourné un tableau valide.");
    }

    foreach ($pagination as $offres) {
        // Check if there are offers to display
        if (!empty($offres)) {
            echo "<div class='container'>";
            foreach ($offres as $offre) {
                // Secure variables against XSS
                $nom = htmlspecialchars($offre['Nom-offre']);
                $ville = htmlspecialchars($offre['Localisation-offre']);
                $secteur = htmlspecialchars($offre['Secteur-offre']);
                $description = htmlspecialchars($offre['Description-offre']);
                $competence = htmlspecialchars($offre['Competences-offre']);
                $debut = htmlspecialchars($offre['Debut-offre']);
                $fin = htmlspecialchars($offre['Fin-offre']);
                $identreprise = htmlspecialchars($offre['ID-entreprise']);
                $nomEntreprise = htmlspecialchars($offre['Nom-entreprise']);
                $logo = htmlspecialchars($offre['CheminImage-entreprise']);
                $descriptionEntreprise = htmlspecialchars($offre['Description-entreprise']);

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
                echo "<div class='carre_nom_de_lentreprise_page_de_recherche'>$nom</div>";
                echo "<button class='btn' onclick='ouvrirPopup(\"popup_$entreprise_id\")'></button>";

                // Popup for the offer with skills
                echo "<div id='popup_$entreprise_id' class='modal'>";
                echo "<div class='modal-content'>";
                echo "<div>";
                echo "<div class='carre_noir'>$nom</div>";

                echo "<div class='logo_de_l_entreprise_popup'><img width='150' src='$logo'></div>";
                echo "<div class='nom_de_l_entreprise_popup'>$nom :</div>";
                echo "<div class='ville_popup'>$ville</div>";
                echo "<div class='Qualification_popup'>Qualification :</div>";

                echo "<div class='titre_a_propos_de_l_entreprise_popup'>A Propos De l'Entreprise</div>";
                echo "<div class='texte_a_propos_de_l_entreprise_popup'>$descriptionEntreprise</div>";

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
    }
}


?>