<?php
session_start();

class Entreprise
{
    public $pdo;
    public $error;

    private $dsn;
    private $user;
    private $password;

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

    #modifie les valeurs d'un admin selon son id
    public function updateCompany($id, $data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['telephone']) || !$this->checkCharacters($data['note'] ) || !$this->checkCharacters($data['path'] )
        ) {
            return false;
        }
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('UPDATE entreprise SET `Nom-entreprise` = :name, `Description-entreprise` = :description, `Email-entreprise` = :email, `Telephone-entreprise` = :telephone, `Note-entreprise` = :note, `CheminImage-entreprise` = :path WHERE `ID-entreprise` = :identreprise');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'], PDO::PARAM_STR);
            $stmt->bindParam('note', $data[':note'], PDO::PARAM_STR);
            $stmt->bindParam('path', $data[':path'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function addCompany($data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['email']) || !$this->checkCharacters($data['telephone']) || !$this->checkCharacters($data['note'] ) || !$this->checkCharacters($data['path'] )
        ) {
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('INSERT INTO entreprise (`Nom-entreprise`, `Description-entreprise`, `Email-entreprise`, `Telephone-entreprise`, `Note-entreprise`, `CheminImage-entreprise`) VALUES (:name, :description, :email, :telephone, :note, :path)');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $data['telephone'], PDO::PARAM_STR);
            $stmt->bindParam('note', $data[':note'], PDO::PARAM_STR);
            $stmt->bindParam('path', $data[':path'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function deleteCompany($data)
    {
        if (!is_numeric($data['id']) || $data['id'] <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('DELETE FROM entreprise WHERE `ID-offre` = :id');
            $stmt->bindParam(':id', $data['id']);
            return $stmt->execute();
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

$test = new Entreprise('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

$test->addCompany('{
        "name":"Thales",
        "description":"Cybersecurite et IA",
        "email":"test@thales.com",
        "telephone":"0223568978",
        "note":"5",
        "path":"c:/wamp64/www/Thales.jpg"}');

echo $test->getAllCompanies() ;

?>