<?php 
session_start();

#=====================  Class Favoris  =====================
class Favoris
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

 public function addFavori($id_etudiant, $id_offre)
 {
     try {
         $stmt = $this->pdo->prepare('INSERT INTO favoris (`ID-etudiant`, `ID-offre`) VALUES (:id_etudiant, :id_offre)');
         $stmt->bindParam(':id_etudiant', $id_etudiant, PDO::PARAM_INT);
         $stmt->bindParam(':id_offre', $id_offre, PDO::PARAM_INT);
         return $stmt->execute();
     } catch (PDOException $e) {
         echo "Erreur : " . $e->getMessage() . "<br>";
         return false;
     }
 }

 public function removeFavori($id_etudiant, $id_offre)
 {
     try {
         $stmt = $this->pdo->prepare('DELETE FROM favoris WHERE `ID-etudiant` = :id_etudiant AND `ID-offre` = :id_offre');
         $stmt->bindParam(':id_etudiant', $id_etudiant, PDO::PARAM_INT);
         $stmt->bindParam(':id_offre', $id_offre, PDO::PARAM_INT);
         return $stmt->execute();
     } catch (PDOException $e) {
         echo "Erreur : " . $e->getMessage() . "<br>";
         return false;
     }
 }

}
 #===== Test =====
 /*
 $test = new Favoris("mysql:host=localhost;dbname=web4all", "root", "");
    $test->addFavori(1, 2);
    $test->removeFavori(1, 2);
    */