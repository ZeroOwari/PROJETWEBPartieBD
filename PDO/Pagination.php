<?php 
include("Search.php");
include("afficheEntreprise.php");

function pagination($itemPerPage, $offre) {
    // Decode the JSON string into an array
    $offre = json_decode($offre, true);

    // Check if decoding was successful
    if (!is_array($offre)) {
        die("Erreur : Les données fournies ne sont pas valides.");
    }

    $TotalElem = count($offre);
    $NbPages = ceil($TotalElem / $itemPerPage);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $NbPages ? (int)$_GET['page'] : 1;

    $Page_Slice = [];
    for ($page = 1; $page <= $NbPages; $page++) {
        $start = ($page - 1) * $itemPerPage;
        $Page_Slice[] = array_slice($offre, $start, $itemPerPage);
    }

    return json_encode($Page_Slice);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nbrOffer'])) {
    $limit = $_POST['nbrOffer']; 
} 
else {
    $limit = 1; // Valeur par défaut
}

// Appel de la fonction pagination avec le nombre d'éléments par page
$paginated = pagination($limit, $recherche);
echo $paginated;
printPagination($paginated);

?>