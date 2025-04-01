<?php 
session_start();


// #=====================  Class Promotion  =====================

class Promotion {
    #====================  Var   ====================
    public $pdo;
    public $error;

    private $dsn;
    private $user;
    private $password;

    #=====================  Constructeur  =====================
    public function __construct($dsn, $user, $password)
    {
        try {
            $this->dsn = $dsn;
            $this->user = $user;
            $this->password = $password;
            $this->pdo = new PDO($this->dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            isset($this->pdo) ? $this->error = null : $this->error = 'Connection failed';
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
        
    }
    public function getError()
    {
        return $this->error;
    }

    public function getAllPromotions()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM promotion');
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
              
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPromotionById($id){
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM promotion WHERE `ID-promo` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
              
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPromotionByData($data){
        if ( !$this->checkCharacters($data['nom']) || !$this->checkCharacters($data['dateDebut']) || !$this->checkCharacters($data['dateFin']) ){
            return false;
        }
    
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM promotion WHERE `Nom-promo` = :nom AND `DateDebut-promo` = :datedebut AND `DateFin-promo` = :datefin');
            $stmt->bindParam(':nom', $data['nom'],PDO::PARAM_STR);
            $stmt->bindParam(':datedebut', $data['dateDebut'],PDO::PARAM_STR);
            $stmt->bindParam(':datefin', $data['dateFin'],PDO::PARAM_STR);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
              
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addPromotion($data){

        if ( !$this->checkCharacters($data['nom']) || !$this->checkCharacters($data['dateDebut']) || !$this->checkCharacters($data['dateFin']) ){
            return false;
        }
        
    
        try {
            $stmt = $this->pdo->prepare('INSERT INTO promotion (`Nom-promo`, `Debut-promo`, `Fin-promo`) VALUES (:nom, :datedebut, :datefin)');
            $stmt->bindParam(':nom', $data['nom'],PDO::PARAM_STR);
            $stmt->bindParam(':datedebut', $data['dateDebut'],PDO::PARAM_STR);
            $stmt->bindParam(':datefin', $data['dateFin'],PDO::PARAM_STR);
            $stmt->execute();

        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la promotion: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function updatePromotion($data){
        if ( !$this->checkCharacters($data['nom']) || !$this->checkCharacters($data['dateDebut']) || !$this->checkCharacters($data['dateFin']) ){
            return false;
        }
        
    
        try {
            $stmt = $this->pdo->prepare('UPDATE promotion SET `Nom-promo` = :nom, `Debut-promo` = :datedebut, `Fin-promo` = :datefin WHERE `ID-promo` = :id');
            $stmt->bindParam(':nom', $data['nom'],PDO::PARAM_STR);
            $stmt->bindParam(':datedebut', $data['dateDebut'],PDO::PARAM_STR);
            $stmt->bindParam(':datefin', $data['dateFin'],PDO::PARAM_STR);
            $stmt->bindParam(':id', $data['id'],PDO::PARAM_INT);
            $stmt->execute();

        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la promotion: " . $e->getMessage() . "<br>";
            return false;
        }
    }


    public function deletePromotion($id){
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM promotion WHERE `ID-promo` = :id');
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
              
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllStudentsByPromotion($id){
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM etudiant WHERE `ID-promotion-etudiant` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
              
        } catch (PDOException $e) {
            return false;
        }
    }

    public function checkCharacters($string)
    {
        return preg_match('/^[a-zA-Z0-9_@.\/: -]+$/', $string);
    }
    
}

#=====================  Test  =====================
/*
$test = new Promotion('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');


$test->addPromotion([
    'nom' => 'Promo Test',
    'dateDebut' => '2023-10-01',
    'dateFin' => '2023-12-31'
]);


echo $test->getAllPromotions();
echo $test->getPromotionById(1);
echo $test->getAllStudentsByPromotion(1);
*/ 

?>