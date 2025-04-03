<?php

class GestionEtu
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function rechercherEtudiant($nom = null, $prenom = null, $email = null)
    {
        $query = "SELECT * FROM etudiants WHERE 1=1";
        $params = [];

        if ($nom) {
            $query .= " AND nom LIKE :nom";
            $params[':nom'] = "%$nom%";
        }
        if ($prenom) {
            $query .= " AND prenom LIKE :prenom";
            $params[':prenom'] = "%$prenom%";
        }
        if ($email) {
            $query .= " AND email LIKE :email";
            $params[':email'] = "%$email%";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerEtudiant($nom, $prenom, $email)
    {
        $query = "INSERT INTO etudiants (nom, prenom, email) VALUES (:nom, :prenom, :email)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email
        ]);
    }

    public function modifierEtudiant($id, $nom, $prenom, $email)
    {
        $query = "UPDATE etudiants SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email
        ]);
    }

    public function supprimerEtudiant($id)
    {
        $query = "DELETE FROM etudiants WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public function consulterStatistiques($id)
    {
        $query = "SELECT recherche_stage FROM etudiants WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>