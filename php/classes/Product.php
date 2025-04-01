<?php
require_once 'Database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllProducts() {
        $stmt = $this->conn->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPriceRange() {
        $query = $this->conn->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return [
            'minPrice' => $result['min_price'] ?? 0,
            'maxPrice' => $result['max_price'] ?? 5000
        ];
    }

    public function getFilteredProducts($filters) {
        $sql = "SELECT id, name, image, video, price, type FROM products WHERE 1=1";
        $params = [];

        if (!empty($filters['types'])) {
            $placeholders = implode(", ", array_fill(0, count($filters['types']), '?'));
            $sql .= " AND type IN ($placeholders)";
            $params = array_merge($params, $filters['types']);
        }

        if (!empty($filters['price_min']) && !empty($filters['price_max'])) {
            $sql .= " AND price BETWEEN ? AND ?";
            $params[] = $filters['price_min'];
            $params[] = $filters['price_max'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

/*
require_once 'Database.php';

class Product {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllProducts() {
        $stmt = $this->conn->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPriceRange() {
        $query = $this->conn->query("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return [
            'minPrice' => $result['min_price'] ?? 0,
            'maxPrice' => $result['max_price'] ?? 5000
        ];
    }

    public function getFilteredProducts($filters) {
        $sql = "SELECT id, name, image, video, price, type FROM products WHERE 1=1";
        $params = [];

        if (!empty($filters['types'])) {
            $placeholders = implode(", ", array_fill(0, count($filters['types']), '?'));
            $sql .= " AND type IN ($placeholders)";
            $params = array_merge($params, $filters['types']);
        }

        if (!empty($filters['price_min']) && !empty($filters['price_max'])) {
            $sql .= " AND price BETWEEN ? AND ?";
            $params[] = $filters['price_min'];
            $params[] = $filters['price_max'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}*/
?>
