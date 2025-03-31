<?php
class Etudiant extends PDO {
// Méthode statique pour simplifier la connexion
    public static function connect(
        string $dsn,
        ?string $username = null,
        #[\SensitiveParameter] ?string $password = null,
        ?array $options = null
    ): static {
        return new static($dsn, $username, $password, $options);
    }
}

// Utilisation de la méthode connect pour établir la connexion
try {
    $db = Etudiant::connect(
        "   :host=localhost;dbname=web4all;charset=utf8",
        "root",
        "password",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "Connexion réussie !";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>