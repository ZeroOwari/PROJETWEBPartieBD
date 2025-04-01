<?php 

function pagination($itemPerPage){
    $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT * FROM entreprise');
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $entreprise[] = $row;
    }
    $TotalElem = count($entreprise);
    $NbPages = ceil($TotalElem / $itemPerPage);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $NbPages ? (int)$_GET['page'] : 1;

    for ($page = 1; $page <= $NbPages; $page++){
        $start = ($page - 1) * $itemPerPage;
        $Page_Slice [ ]= array_slice($entreprise, $start, $itemPerPage);
    }
    
    return json_encode($Page_Slice);

}

echo pagination(2);

?>