<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pagination des entreprises</title>
        <link rel="stylesheet" href="page_css.css">
    </head>
    <body class="body_Page_de_recherche">

    <form method="post" >
        <div class="filter-item">
            <label for="nbrOffer">Nombre d'offres</label>
            <select id="nbrOffer" name="nbrOffer">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
        </div>
        <button type="submit" value="submit">Appliquer</button>
  </form>

  <?php
    include("pagination.php");
  ?>

<script src="page_de_recherche_jvsc.js"></script>
</body>
</html>




 