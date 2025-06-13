<?php
class Database {
    //produccion
    private $host = 'localhost';
    private $db_name = 'eldiama1_nixcol_bd';
    private $username = 'eldiama1_nixcol_user_bd';
    private $password = '(M}h=0&6wf0t';
    private $conn;

    // Obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
            $this->conn->exec("SET time_zone = '-05:00'");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    public function ping() {
        try {
            $this->conn->query("SELECT 1"); // Ejecuta una consulta sencilla
            return true; // Si no hay error, la conexión está activa
        } catch (PDOException $e) {
            // Manejar excepciones de PDO (como conexión perdida)
            escribirLog("Error en ping: " . $e->getMessage(), 'ERROR'); // Loguea el error
            return false; // Si hay error, la conexión se ha perdido
        }
    }
}
?>