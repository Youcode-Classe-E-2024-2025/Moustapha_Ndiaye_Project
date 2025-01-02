<?php

class LoadDatabase {
    private $filePath;
    private $pdo;

    public function __construct($pdo, $filePath = '../database/schemaDatabase.sql') {
        $this->pdo = $pdo;
        $this->filePath = $filePath;
    }

    public function fetchData() {
        if (!file_exists($this->filePath)) {
            throw new Exception("SQL file does not exist: " . $this->filePath);
        }

        $sql = file_get_contents($this->filePath);

        if (empty($sql)) {
            throw new Exception("SQL file is empty: " . $this->filePath);
        }

        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new Exception("Database setup failed: " . $e->getMessage());
        }
    }
}