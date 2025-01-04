<?php

class LoadDatabase {
    private $filePath;
    private $pdo;

    public function __construct($pdo, $filePath = '../database/schemaDatabase.sql') {
        $this->pdo = $pdo;
        $this->filePath = $filePath;
    }

    public function fetchData() {
        // Vérification si le fichier SQL existe
        if (!file_exists($this->filePath)) {
            throw new Exception("SQL file does not exist: " . $this->filePath);
        }

        // Chargement du contenu du fichier SQL
        $sql = file_get_contents($this->filePath);

        // Vérification si le fichier est vide
        if (empty($sql)) {
            throw new Exception("SQL file is empty: " . $this->filePath);
        }

        // Exécution du script SQL
        try {
            // Assurez-vous que la connexion PDO fonctionne
            if ($this->pdo) {
                $this->pdo->exec($sql);
            } else {
                throw new Exception("PDO connection is null.");
            }
        } catch (PDOException $e) {
            throw new Exception("Database setup failed: " . $e->getMessage());
        }
    }
}
