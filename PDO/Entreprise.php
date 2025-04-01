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

    public function pagination($page = 1, $limit = 10)
    {
        if (!is_numeric($page) || $page <= 0) {
            echo 'Page invalide.<br>';
            return false;
        }
        if (!is_numeric($limit) || $limit <= 0) {
            echo 'Limite invalide.<br>';
            return false;
        }
        try {
            $offset = ($page - 1) * $limit;
            $stmt = $this->pdo->prepare('SELECT * FROM entreprise ORDER BY `` LIMIT :offset, :limit');
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            return false;
        }
    }


    #verif des caracteres speciaux
    public function checkCharacters($string)
    {
        // autorise @ . / \ et :
        return preg_match('/^[a-zA-Z0-9_@.\/: ]+$/', $string);
    }

}

$test = new Entreprise('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

/*
$test->deleteCompany( 2);

$test->addCompany([
        'name' => 'Thales',
        'description' => 'Cybersecurite et IA',
        'email' => 'test@thales.com',
        'telephone' => '0223568978',
        'note' =>  5,
        'path' => 'c:/wamp64/www/Thales.jpg'
]);

$test->updateCompany(3 ,[
        'name' => 'Thales',
        'description' => 'Cybersecurite et IA',
        'email' => 'test@thales.com',
        'telephone' => '0223568978',
        'note' =>  4,
        'path' => 'c:/wamp64/www/Thales.jpg'
]);

echo $test->getAllCompanies() ; echo '<br>';
echo $test->getCompanyByData([
    'name' => 'Thales',
    'description' => null,
    'email' => null,
    'telephone' => null,
    'note' => null,
    'path' => null
]);echo '<br>';
echo $test->getCompanyByData([
    'name' => null,
    'description' => null,
    'email' => null,
    'telephone' => null,
    'note' => 4,
    'path' => null
]);
*/

?>