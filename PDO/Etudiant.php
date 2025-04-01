<?php 

#=====================  Class Etudiant  =====================
class Etudiant 
{
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

    #retourne les étudiants à l'id indiqué
    public function getStudentById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM etudiant WHERE `ID-etudiant` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
                
        } catch (PDOException $e) {
            return false;
        }
    }       

    #retourne tous les étudiants
    public function getAllStudent()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM etudiant');
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
                
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getStudentByData($data){
        if ( !$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])
        ) {
            return false;
        }
    
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM etudiant WHERE `Prenom-etudiant` = :firstname AND `Nom-etudiant` = :lastname AND `Email-etudiant` = :email AND `MDP-etudiant` = :password');
            $stmt->bindParam(':firstname', $data['firstname'],PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $data['lastname'],PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'],PDO::PARAM_STR);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllOffer()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM offrestage');
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
             
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getOfferById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM offrestage WHERE `ID-offre` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
             
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getOfferByData($data){
        if (!is_array($data)){
            return false;
        }
        try{
            $stmt = $this->pdo->prepare('SELECT * FROM offrestage WHERE `Nom-offre` = :name AND `Description-offre` = :description AND `Competences-offre` = :competences AND `Debut-offre` = :debut AND `Fin-offre` = :fin AND `Secteur-offre` = :secteur AND `Localisation-offre` = :localisation `ID-entreprise` = :identreprise');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam('sector', $data['sector'], PDO::PARAM_STR);
            $stmt->bindParam(':localisation', $data['localisation'], PDO::PARAM_STR);
            $stmt->bindParam(':identreprise', $data['identreprise'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
        catch (PDOException $e) {
            return false;
        }
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

    public function getAllCompanies()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM entreprise');
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
             
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCompanyById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM entreprise WHERE `ID-entreprise` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
             
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCompanyByData($data){
        if (!is_array($data)){
            return false;
        }

        try {
            #on test tout en mode brut 
            $stmt = $this->pdo->prepare('SELECT * FROM entreprise WHERE `Nom-entreprise` = :name OR `Description-entreprise` = :description OR `Email-entreprise` = :email OR `Telephone-entreprise` = :telephone OR `Note-entreprise` = :note OR `CheminImage-entreprise` = :path');
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':telephone', $data['telephone']);
            $stmt->bindParam(':note', $data['note']);
            $stmt->bindParam(':path', $data['path']);
            $stmt->execute();
            return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return false;
        }
    }


    #verifie la correspondance email de log / mdp
    public function checkLogValidation($data)
    {
        if ($this->checkCharacters($data['email']) || $this->checkCharacters($data['password'])) {
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM pilotepromo WHERE `Email-pilote` = :email');
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            $pilote = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($pilote && password_verify(':email', $pilote['MDP-pilote'])) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    #verif des caracteres speciaux
    public function checkCharacters($string)
    {
        return preg_match('/^[\p{L}\p{N}_@.\/: \-,+]+$/u', $string);
    }
    
}

#=====================  Test  =====================
/*
$test = new Etudiant ('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

echo $test->getAllStudent() ;
echo $test->getStudent(1) ;
*/

?>