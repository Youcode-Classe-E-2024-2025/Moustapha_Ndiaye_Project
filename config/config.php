<?php
require_once(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
// data base connexion 

class database {
    private $host ;
    private $dbName ;
    private $user  ;
    private $passWord;    
    private $connexion = null;

    public function __construct(){
        $this->host = $_ENV['DB_HOST'] ;
        $this->dbName = $_ENV['DB_NAME'] ;
        $this->user = $_ENV['DB_USER'];
        $this->passWord = $_ENV['DB_PASSWORD'];
    }

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
            echo "Connexion failed : " . $e->getMessage() . "\n";
        }

        return $this->connexion;
    }

    public function closeConnexion(){
        $this->connexion = null ;
    }
}   

