<?php
class Contact {
    // Database connection properties
    private $conn;
    private $table = 'contact';
    
    // Contact properties
    public $id;
    public $name;
    public $number;
    
    // Constructor with database connection
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Get all contacts
    public function getAll() {
        $query = "SELECT id, name, number FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get single contact by ID
    public function getById() {
        $query = "SELECT id, name, number FROM " . $this->table . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->number = $row['number'];
            return true;
        }
        
        return false;
    }
    
    // Create new contact
    public function create() {
        // Check if contact already exists
        if($this->contactExists()) {
            return false;
        }
        
        $query = "INSERT INTO " . $this->table . " (name, number) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->number = htmlspecialchars(strip_tags($this->number));
        
        // Bind parameters
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->number);
        
        // Execute query
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    // Update contact
    public function update() {
        $query = "UPDATE " . $this->table . " SET name = ?, number = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->number = htmlspecialchars(strip_tags($this->number));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind parameters
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->number);
        $stmt->bindParam(3, $this->id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Delete contact
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind parameter
        $stmt->bindParam(1, $this->id);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    
    // Search contacts
    public function search($keyword) {
        $query = "SELECT id, name, number FROM " . $this->table . " 
                  WHERE name LIKE ? OR number LIKE ? 
                  ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize keyword
        $keyword = htmlspecialchars(strip_tags($keyword));
        $keyword = "%{$keyword}%";
        
        // Bind parameters
        $stmt->bindParam(1, $keyword);
        $stmt->bindParam(2, $keyword);
        
        // Execute query
        $stmt->execute();
        
        return $stmt;
    }
    
    // Check if contact already exists
    private function contactExists() {
        $query = "SELECT id FROM " . $this->table . " WHERE name = ? AND number = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->number);
        
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return true;
        }
        
        return false;
    }
}
?>
