<?php 
session_start();

#=====================  Class Admin  =====================
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
    public function getError()
    {
        return $this->error;
    }


    #retourne le pilote à l'id indiqué
    public function getPilote($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM pilotepromo WHERE `ID-admin` = :id');
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

    #retourne les étudiants à l'id indiqué
    public function getStudent($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM etudiant WHERE `ID-admin` = :id');
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
            $stmt = $this->pdo->prepare('INSERT INTO etudiant (`Prenom-etudiant`, `Nom-etudiant`, `Email-etudiant`, `MDP-etudiant`, `Telephone-etudiant`, `DateNaissance-etudiant`, `ID-CV`, `ID-promotion-etudiant`) VALUES (:firstname, :lastname, :email, :password, :telephone, :date, :idcv, :idpromo)');
            $stmt->bindParam(':firstname', $data['firstname'],PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $data['lastname'],PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'],PDO::PARAM_STR);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'],PDO::PARAM_STR);
            $stmt->bindParam(':date', $data['date'],PDO::PARAM_STR);
            $stmt->bindParam(':idcv', $data['idcv'],PDO::PARAM_STR);
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



    #verifie la correspondance email de log / mdp
    public function checkLogValidation($data)
    {
        if ($this->checkCharacters($data['email']) || $this->checkCharacters($data['password'])) {
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM admin WHERE `Email-pilote` = :email');
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($admin && password_verify(':email', $admin['MDP-pilote'])) {
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
        return preg_match('/^[a-zA-Z0-9_@.]+$/', $string);
    }


    
}

#=====================  Test  =====================
$test = new Pilote ('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');



echo $test->getAllStudent() ;
echo $test->getStudent(1) ;

echo $test->getPilote(1) ;
echo $test->getAllPilote() ;

?>