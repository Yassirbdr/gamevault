<?php
class Database {
    private $host = "localhost";
    private $db_name = "gamevault";     // ← Dit heb je veranderd!
    private $username = "root";
    private $password = "";

    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch(PDOException $exception) {
            throw new Exception("Connectie fout: " . $exception->getMessage());
        }
    }
}
?>