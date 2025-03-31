<?php
session_start();

class Offre
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
            $stmt = $this->pdo->prepare('SELECT * FROM offrestage WHERE `Nom-offre` = :name AND `Description-offre` = :description AND `Competences-offre` = :competences AND `Debut-offre` = :debut AND `Fin-offre` = :fin AND `ID-entreprise` = :identreprise');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam(':identreprise', $data['identreprise'], PDO::PARAM_INT);
            $stmt->execute();
            return json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
        catch (PDOException $e) {
            return false;
        }
    }

    #modifie les valeurs d'une offre selon son id
    public function updateOffer($id, $data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['competences']) || !$this->checkCharacters($data['debut']) || !$this->checkCharacters($data['fin']) || !$this->checkCharacters($data['identreprise'])
        ) {
            return false;
        }
        if (!is_numeric($id) || $id <= 0) {
            echo 'ID invalide.<br>';
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('UPDATE offrestage SET `Nom-offre` = :name, `Description-offre` = :description, `Competences-offre` = :competences, `Debut-offre` = :debut, `Fin-offre` = :fin WHERE `ID-entreprise` = :identreprise');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }

    public function addOffer($data)
    {
        if (!$this->checkCharacters($data['name']) || !$this->checkCharacters($data['description']) || !$this->checkCharacters($data['competences']) || !$this->checkCharacters($data['debut']) || !$this->checkCharacters($data['fin']) || !$this->checkCharacters($data['identreprise'])
        ) {
            return false;
        }
        try {
            $stmt = $this->pdo->prepare('INSERT INTO offrestage (`Nom-offre`, `Description-offre`, `Competences-offre`, `Debut-offre`, `Fin-offre`, `ID-entreprise`) VALUES (:name, :description, :competences, :debut, :fin, :identreprise)');
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':competences', $data['competences'], PDO::PARAM_STR);
            $stmt->bindParam(':debut', $data['debut'], PDO::PARAM_STR);
            $stmt->bindParam(':fin', $data['fin'], PDO::PARAM_STR);
            $stmt->bindParam(':identreprise', $data['identreprise'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "<br>";
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

$test = new Offre('mysql:host=localhost;dbname=web4all', 'TOtime', 'Password0508');

echo $test->getAllOffer();
echo $test->getOfferById(1) ;

?>