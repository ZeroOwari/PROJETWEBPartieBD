<?php 
session_start();

#=====================  Class Admin  =====================
class Admin 
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


    #modifie les valeurs d'un admin selon son id
    public function updateAdmin($id, $data)
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
            $stmt = $this->pdo->prepare('UPDATE admin SET `Prenom-admin` = :firstname, `Nom-admin` = :lastname, `Email-admin` = :email, `MDP-admin` = :password WHERE `ID-admin` = :id');
            $stmt->bindParam(':firstname', $data['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $data['lastname'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    #ajoute un compte admin dans la bdd<?php
    public function addAdmin($data)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admin WHERE `Email-admin` = :email');
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
    
            if ($count > 0) {
                echo "Compte admin avec l'email " . $data['email'] . " existe déjà.<br>";
                return false;
            }
        } catch (PDOException $e) {
            echo "Error checking for existing admin: " . $e->getMessage() . "<br>";
            return false;
        }

        if ( !$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])
        ) {
            return false;
        }
        
    
        try {
            $stmt = $this->pdo->prepare('INSERT INTO admin (`Prenom-admin`, `Nom-admin`, `Email-admin`, `MDP-admin`) VALUES (:firstname, :lastname, :email, :password)');
            $stmt->bindParam(':firstname', $data['firstname']);
            $stmt->bindParam(':lastname', $data['lastname']);
            $stmt->bindParam(':email', $data['email']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    #retire le compte admin de la bdd avec l'id correspondant
    public function deleteAdmin($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM admin WHERE `ID-admin` = :id');
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    #retourne tous les admins
    public function getAllAdmins()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM admin');
            $stmt->execute();
            $newjson = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            return $newjson;
             
        } catch (PDOException $e) {
            return false;
        }
    }

    #retourne l'admin en fonction de son id
    public function getAdmin($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM admin WHERE `ID-admin` = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $newjson = json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            return $newjson;
             
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
            $stmt = $this->pdo->prepare('SELECT * FROM admin WHERE `Email-admin` = :email');
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($admin && password_verify(':email', $admin['MDP-admin'])) {
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

    public function deletePilote($data) {
        if (!is_numeric($data['id']) || $data['id'] <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM pilotepromo WHERE `ID-pilote` = :id');
            $stmt->bindParam(':id', $data['id']);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function updatePilote($data) {
        if (!$this->checkCharacters($data['firstname']) || !$this->checkCharacters($data['lastname']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['password'])
        ) {
            return false;
        }
        if (!is_numeric($data['id']) || $data['id'] <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('UPDATE pilotepromo SET `Prenom-pilote` = :firstname, `Nom-pilote` = :lastname, `Email-pilote` = :email, `MDP-pilote` = :password WHERE `ID-pilote` = :id');
            $stmt->bindParam(':firstname', $data['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $data['lastname'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

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
    

}

#=====================  Tests  =====================

$test = new Admin('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

echo $test->getAdmin(1);
echo $test->getAllAdmins();

echo $test->getAllStudent();
echo $test->getStudent(1);

echo $test->getAllPilote();
echo $test->getPilote(1);


/*
$test->addStudent([
    'firstname' => 'Totime',
    'lastname' => 'VC',
    'email' => 'TVC@example.com',
    'password' => 'password123',
    'telephone' => '0631569513',
    'date' => '2005-08-05',
    'idcv' => '1',
    'idpromo' => '1'
]);
$test->updateStudent(1,[
    'firstname' => 'Théotime',
    'lastname' => 'VC',
    'email' => 'TVC@example.com',
    'password' => 'password123',
    'telephone' => '0631569513',
    'date' => '2005-08-05',
    'idcv' => '1',
    'idpromo' => '1'
]);

$test->addPilote([
    'firstname' => 'Clement',
    'lastname' => 'Magnier',
    'email' => 'CM@example.com',
    'password' => 'password123',
]);

#SQL Injection non concluente car characteres non autorisés dans le nom de la table + grace a checkChar
$test->addAdmin([
    'firstname' => 'Select Prenom-admin FROM admin',
    'lastname' => 'Pork',
    'email' => 'john.doe@example.com',
    'password' => 'password123'
]);

$all = $test->getAllAdmins();
foreach ($all as $admin) {
    echo $admin['ID-admin'] . ' ' . $admin['Prenom-admin'] . ' ' . $admin['Nom-admin'] . ' ' . $admin['Email-admin'] . '<br>';
}


$test->addAdmin([
    'firstname' => "$$$*&^%$#@!", // Invalid characters
    'lastname' => 'Pork',
    'email' => 'john.doe@example.com',
    'password' => 'password123'
]);

$test->addAdmin([
    'firstname' => 'PO',
    'lastname' => 'DO',
    'email' => 'PODO@example.com',
    'password' => 'password123'
]);


$demo = $test->getAdmin(6);
echo $demo['ID-admin'] . ' ' . $demo['Prenom-admin'] . ' ' . $demo['Nom-admin'] . ' ' . $demo['Email-admin'] . '<br>';

$test->deleteAdmin(7);

$test->updateAdmin(8, [
    'firstname' => 'Lirila',
    'lastname' => 'Larala',
    'email' => 'lirila.larala@example.com',
    'password' => 'newpassword123'
]);
*/

?>