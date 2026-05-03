<?php
session_start();

class Database {
    private static $instance = null;
    private $db;

    private function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=gamestore;charset=utf8",
                "root",
                ""
            );

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            die("Veritabanı Hatası: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->db;
    }
}

// Giriş kontrolü
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

// Admin kontrolü
function checkAdmin() {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
        header('Location: index.php');
        exit();
    }
}
?>