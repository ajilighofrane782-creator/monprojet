<?php
/**
 * Configuration de la base de données
 * Olympica Natation
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'olympica_natation');
define('DB_USER', 'root');  // Changez selon votre configuration
define('DB_PASS', '');      // Changez selon votre configuration
define('DB_CHARSET', 'utf8');

// Configuration du site
define('SITE_NAME', 'Olympica Natation');
define('SITE_URL', 'http://localhost/olympica_natations');

// Chemins
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/uploads/');

// Connexion à la base de données avec PDO
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Empêcher le clonage de l'instance
    private function __clone() {}
    
    // Empêcher la désérialisation
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Fonction helper pour obtenir la connexion
function getDB() {
    return Database::getInstance()->getConnection();
}
?>
