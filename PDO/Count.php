<?php 


function getStudentCount() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT COUNT(*) AS student_count FROM etudiant');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['student_count'];
    } 
    catch (PDOException $e) {
        return 'Error: ' . $e->getMessage();
    }
}

function getPiloteCount() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare('SELECT COUNT(*) AS pilote_count FROM pilotepromo');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['pilote_count'];
    } 
    catch (PDOException $e) {
        return 'Error: ' . $e->getMessage();
    }
}

function getAminCount() {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare('SELECT COUNT(*) AS admin_count FROM admin');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['admin_count'];
    } catch (PDOException $e) {

        return 'Error: ' . $e->getMessage();
    }
}

function getOfferCount(){
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare('SELECT COUNT(*) AS offer_count FROM offrestage');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['offer_count'];
    } catch (PDOException $e) {

        return 'Error: ' . $e->getMessage();
    }
}

function getCompanyCount(){
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare('SELECT COUNT(*) AS company_count FROM entreprise');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['company_count'];
    } catch (PDOException $e) {

        return 'Error: ' . $e->getMessage();
    }
}

function getUploadCompanyCount(){
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare('SELECT COUNT(*) AS uploadcompany_count FROM ajout');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['uploadcompany_count'];
    } catch (PDOException $e) {

        return 'Error: ' . $e->getMessage();
    }
}

function getUploadOfferCount(){
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=web4all', 'website_user', 'kxHBI-ozJOjvwr_H');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare('SELECT COUNT(*) AS uploadoffer_count FROM publication');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['uploadoffer_count'];
    } catch (PDOException $e) {

        return 'Error: ' . $e->getMessage();
    }
}

#=====================  Test  ====================
echo getUploadCompanyCount(); echo '<br>';
echo getUploadOfferCount(); echo '<br>';
echo getCompanyCount(); echo '<br>';
echo getOfferCount(); echo '<br>';
echo getAminCount(); echo '<br>';
echo getPiloteCount(); echo '<br>';
echo getStudentCount();

?>