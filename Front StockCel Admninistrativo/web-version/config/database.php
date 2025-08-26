<?php
// Configuración de base de datos
class Database {
    private $host = 'localhost';
    private $db_name = 'softwarepar';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", 
                                 $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Crear instancia de base de datos
$database = new Database();
$pdo = $database->getConnection();

// Verificar conexión
if (!$pdo) {
    die("No se pudo conectar a la base de datos");
}
?>