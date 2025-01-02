<?php

// data base connexion 

class database {
    private $host = "localhost";
    private $dbName = "projectManagePOO";
    private $user = "root";
    private $passWord = "";
    private $connexion = null;

    public function connexion() {
        try {
            $this->connexion = new PDO(
                "mysql:host=$this->host;dbname=$this->dbName",
                $this->user,
                $this->passWord
            );
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Échec de la connexion : " . $e->getMessage() . "\n";
        }

        return $this->connexion;
    }

    public function closeConnexion(){
        $this->connexion = null ;
    }
}   

// // user case 
// $db = new database ;
// //open connexion
// $conn = $db->connexion();
// // close connexion
// $conn = $db->closeConnexion() ;