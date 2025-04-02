<?php 

include("afficheEntreprise.php");

function pagination($itemPerPage) {
    $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Dynamically include the LIMIT value in the query
    $sql = 'SELECT * FROM offrestage JOIN entreprise ON offrestage.`ID-entreprise` = entreprise.`ID-entreprise` ORDER BY `ID-offre` DESC LIMIT ' . (int)$itemPerPage;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $offre = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $TotalElem = count($offre);
    $NbPages = ceil($TotalElem / $itemPerPage);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $NbPages ? (int)$_GET['page'] : 1;

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
    $limit = 5; // Valeur par défaut
}

// Appel de la fonction pagination avec le nombre d'éléments par page
printpagination($limit);

?>