<?php 

#=====================  Class Pilote  =====================
class Pilote 
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

    #retourne le pilote à l'id indiqué
    public function getPiloteById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM pilotepromo WHERE `ID-pilote` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
                
        } catch (PDOException $e) {
            return false;
        }
    }       

    #retourne tous les étudiants 
    public function getAllPilote()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM pilotepromo');
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
                
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPiloteByData($data){
        if ( !$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])
        ) {
            return false;
        }
    
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM pilotepromo WHERE `Prenom-pilote` = :firstname AND `Nom-pilote` = :lastname AND `Email-pilote` = :email AND `MDP-pilote` = :password');
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
    public function sessionLog($email, $password) {
        try {
            $stmt = $this->pdo->prepare('SELECT `Prenom-pilote`, `Nom-pilote`, `Email-pilote`, `MDP-pilote` FROM pilotepromo WHERE `Email-pilote` = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($this->checkLogValidation([
                'email' => $email,
                'password' => $password,
            ])) {
                return [
                    $admin['Prenom-pilote'],
                    $admin['Nom-pilote'],
                    $admin['Email-pilote'],
                    $admin['MDP-pilote']
                ];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function addPilote($data)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM pilotepromo WHERE `Email-pilote` = :email');
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            if ($count > 0) {
                echo "Compte pilote avec l'email " . $data['email'] . " existe déjà.<br>";
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur regarde si un pilote existe déjà: " . $e->getMessage() . "<br>";
            return false;
        }

        if ( !$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])
        ) {
            return false;
        }
        
    
        try {
            $stmt = $this->pdo->prepare('INSERT INTO pilotepromo (`Prenom-pilote`, `Nom-pilote`, `Email-pilote`, `MDP-pilote`) VALUES (:firstname, :lastname, :email, :password)');
            $stmt->bindParam(':firstname', $data['firstname']);
            $stmt->bindParam(':lastname', $data['lastname']);
            $stmt->bindParam(':email', $data['email']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
            return false;
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

    public function addStudent($data){
        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM etudiant WHERE `Email-etudiant` = :email');
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            if ($count > 0) {
                echo "Compte etudiant avec l'email " . $data['email'] . " existe déjà.<br>";
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur etudiant existant déjà." . $e->getMessage() . "<br>";
            return false;
        }

        if ( !$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'] || !$this->checkCharacters($data['Telephone-etudiant']) || !$this->checkCharacters($data['DateNaissance-etudiant']) || !$this->checkCharacters($data['ID-CV']) || !$this->checkCharacters($data['ID-promotion-etudiant']) )
        ) {
            return false;
        }
        
    
        try {
            $stmt = $this->pdo->prepare('INSERT INTO etudiant (`Prenom-etudiant`, `Nom-etudiant`, `Email-etudiant`, `MDP-etudiant`, `Telephone-etudiant`, `DateNaissance-etudiant`, `Chemin-CV`, `ID-promotion-etudiant`) VALUES (:firstname, :lastname, :email, :password, :telephone, :date, :pathcv, :idpromo)');
            $stmt->bindParam(':firstname', $data['firstname'],PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $data['lastname'],PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'],PDO::PARAM_STR);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'],PDO::PARAM_STR);
            $stmt->bindParam(':date', $data['date'],PDO::PARAM_STR);
            $stmt->bindParam(':pathcv', $data['pathcv'],PDO::PARAM_STR);
            $stmt->bindParam('idpromo', $data['idpromo'],PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function deleteStudent($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM etudiant WHERE `ID-etudiant` = :id');
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateStudent($id, $data)
    {
        if (!$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])
        ) {
            return false;
        }
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('UPDATE etudiant SET `Prenom-etudiant` = :firstname, `Nom-etudiant` = :lastname, `Email-etudiant` = :email, `MDP-etudiant` = :password, `Telephone-etudiant` = :telephone, `DateNaissance-etudiant` = :date, `Chamin-CV` = :pathcv, `ID-promotion-etudiant` = :idpromo WHERE `ID-etudiant` = :id');
            $stmt->bindParam(':firstname', $data['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $data['lastname'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':telephone', $data['telephone']);
            $stmt->bindParam(':date', $data['date']);
            $stmt->bindParam(':pathcv', $data['pathcv'],PDO::PARAM_STR);
            $stmt->bindParam('idpromo', $data['idpromo']);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
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
        if (is_numeric($data['identreprise'])) {
            echo 'ID invalide.<br>';
            return false;
        }
        try{
            $stmt = $this->pdo->prepare('SELECT * FROM offrestage WHERE `Nom-offre` = :name AND `Description-offre` = :description AND `Competences-offre` = :competences AND `Debut-offre` = :debut AND `Fin-offre` = :fin AND `Secteur-offre` = :secteur AND `Localisation-offre` = :localisation AND `Type-offre` = :type');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam('sector', $data['sector'], PDO::PARAM_STR);
            $stmt->bindParam(':localisation', $data['localisation'], PDO::PARAM_STR);
            $stmt->bindParam(':identreprise', $data['identreprise'], PDO::PARAM_INT);
            $stmt->bindParam(':type', $data['type'], PDO::PARAM_STR);
            $stmt->execute();
            return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
        catch (PDOException $e) {
            return false;
        }
    }

    public function deleteOffer($data)
    {
        if (!is_numeric($data['id']) || $data['id'] <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM offrestage WHERE `ID-offre` = :id');
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
        try{
            $stmt = $this->pdo->prepare('DELETE FROM Publication WHERE `ID-offre` = :id');
            $stmt->bindParam(':id', $data['id']);
            return $stmt->execute();
        }
        catch (PDOException $e) {
            return false;
        }
    }



    #modifie les valeurs d'une offre selon son id
    public function updateOffer($id, $data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['competences']) || !$this->checkCharacters($data['debut']) || !$this->checkCharacters($data['fin']) ||  !$this->checkCharacters($data['sector']) ||  !$this->checkCharacters($data['localisation']) || !$this->checkCharacters($data['identreprise'])
        ) {
            return false;
        }
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('UPDATE offrestage SET `Nom-offre` = :name, `Description-offre` = :description, `Competences-offre` = :competences, `Debut-offre` = :debut, `Fin-offre` = :fin, `Secteur-offre` = :sector, `Localisation-offre` = :loacalisation WHERE `ID-entreprise` = :identreprise');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam('sector', $data['sector'], PDO::PARAM_STR);
            $stmt->bindParam(':localisation', $data['localisation'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function addOffer($data)
    {
        if (
            !$this->checkCharacters($data['name']) ||  !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['competences']) || !$this->checkCharacters($data['debut']) || !$this->checkCharacters($data['fin']) || !$this->checkCharacters($data['sector']) || !$this->checkCharacters($data['localisation'])
        ) {
            echo "Invalid characters in input.<br>";
            return false;
        }
    
        if (!is_numeric($data['identreprise']) || !is_numeric($data['idauteur'])) {
            echo "Invalid entreprise or auteur ID.<br>";
            return false;
        }
    
        try {
            // Insert into `offrestage`
            $stmt = $this->pdo->prepare('INSERT INTO offrestage (`Nom-offre`, `Description-offre`, `Competences-offre`, `Debut-offre`, `Fin-offre`, `Secteur-offre`, `Localisation-offre`, `ID-entreprise`) VALUES (:name, :description, :competences, :debut, :fin, :sector, :localisation, :identreprise)');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam(':sector', $data['sector'], PDO::PARAM_STR);
            $stmt->bindParam(':localisation', $data['localisation'], PDO::PARAM_STR);
            $stmt->bindParam(':identreprise', $data['identreprise'], PDO::PARAM_INT);
    
            $stmt->execute();
    
            // Get the last inserted ID for the offer
            $offerId = $this->pdo->lastInsertId();
    
            // Insert into `Publication` table
            $stmt = $this->pdo->prepare('INSERT INTO Publication (`ID-offre`, `ID-auteur`) VALUES (:idoffre, :idauteur)');
            $stmt->bindParam(':idoffre', $offerId, PDO::PARAM_INT);
            $stmt->bindParam(':idauteur', $data['idauteur'], PDO::PARAM_INT);
    
            $stmt->execute();
    
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
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

    public function addCompany($data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['telephone']) || !$this->checkCharacters($data['path'])
        )
        {
            return false;
        }
        if (!is_numeric($data['note']))
        {
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('INSERT INTO entreprise (`Nom-entreprise`, `Description-entreprise`, `Email-entreprise`, `Telephone-entreprise`, `Note-entreprise`, `CheminImage-entreprise`) VALUES (:name, :description, :email, :telephone, :note, :path)');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'], PDO::PARAM_STR);
            $stmt->bindParam(':note', $data['note'], PDO::PARAM_INT);
            $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);

            $stmt->execute();

            $entrepriseId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare('INSERT INTO ajout (`ID-entreprise`, `ID-auteur`) VALUES (:identreprise, :idauteur)');
            $stmt->bindParam(':identreprise', $entrepriseId, PDO::PARAM_INT);
            $stmt->bindParam(':idauteur', $data['idauteur'], PDO::PARAM_INT);
    
            $stmt->execute();

            return true;
        } 
        catch (PDOException $e) 
        {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }

    }

    #modifie les valeurs d'une entreprise selon son id
    public function updateCompany($id, $data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['telephone']) || !$this->checkCharacters($data['path'] )
        ) {
            return false;
        }
        if (!is_numeric($data['note'])) {
            return false;
        }
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('UPDATE entreprise SET `Nom-entreprise` = :name, `Description-entreprise` = :description, `Email-entreprise` = :email, `Telephone-entreprise` = :telephone, `Note-entreprise` = :note, `CheminImage-entreprise` = :path WHERE `ID-entreprise` = :id');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'], PDO::PARAM_STR);
            $stmt->bindParam(':note', $data['note'], PDO::PARAM_INT);
            $stmt->bindParam(':path', $data['path'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }


    public function deleteCompany($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM entreprise WHERE `ID-entreprise` = :id');
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
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

    public function matchingContent($keywords = null, $location = null, $type = null) {
        try {
            // Start with a base query
            $sql = 'SELECT * FROM offrestage WHERE 1=1';
    
            // Add conditions dynamically based on non-null parameters
            if (!empty($keywords)) {
                $sql .= ' AND (`Nom-offre` LIKE :keywords OR `Description-offre` LIKE :keywords OR `Competences-offre` LIKE :keywords)';
            }
            if (!empty($location)) {
                $sql .= ' AND `Localisation-offre` LIKE :location';
            }
            if (!empty($type)) {
                $sql .= ' AND `Type-offre` LIKE :type';
            }
    
            $stmt = $this->pdo->prepare($sql);
    
            // Bind parameters only if they are not null
            if (!empty($keywords)) {
                $stmt->bindValue(':keywords', '%' . $keywords . '%', PDO::PARAM_STR);
            }
            if (!empty($location)) {
                $stmt->bindValue(':location', '%' . $location . '%', PDO::PARAM_STR);
            }
            if (!empty($type)) {
                $stmt->bindValue(':type', '%' . $type . '%', PDO::PARAM_STR);
            }
    
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Return results as JSON
            return json_encode($results);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }



    #verifie la correspondance email de log / mdp
    public function checkLogValidation($data)
    {
        if (!$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])) {
            echo 'Caracteres invalides.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM pilotepromo WHERE `Email-pilote` = :email');
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            $pilote = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verify the password
            if ($pilote && password_verify($data['password'], $pilote['MDP-pilote'])) {
                echo 'Validation.<br>';
                return true;
            } else {
                echo 'Email ou mot de passe incorrect.<br>';
                return false;
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage() . "<br>";
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
$test = new Pilote ('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');


$test->addOffer([
    'name'=> 'Chef Pentester',
    'description'=> 'Test de sécurité',
    'competences'=> 'Python, C++',
    'debut'=> '2025-01-01',
    'fin'=> '2025-06-30',
    'sector'=> 'Informatique',
    'localisation'=> 'Lyon',
    'identreprise'=> '1',
    'idauteur'=> '1'
]);

$test->addCompany([
    'name'=> 'Ankama',
    'description'=> 'Société de développement de jeux vidéo',
    'email'=> 'societe@ankama.com',
    'telephone'=> '0123456789',
    'note'=> '5',
    'path'=> 'images/ankama.png',
    'idauteur'=> '1'
]);


echo $test->getAllStudent() ;
echo $test->getStudentById(1) ;

echo $test->getPiloteById(1) ;
echo $test->getAllPilote() ;

echo $test->getAllOffer() ;
echo $test->getOfferById(1) ;

*/

?>