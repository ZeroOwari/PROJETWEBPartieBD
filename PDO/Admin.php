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

}

#=====================  Tests  =====================

$test = new Admin('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

echo $test->getAdmin(1);
echo $test->getAllAdmins();




/*

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