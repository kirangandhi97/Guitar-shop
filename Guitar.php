<?php
class Guitar
{
    private $guitarId;
    private $brand;
    private $model;
    private $type;
    private $price;
    private $quantityInStock;
    private $description;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->guitarId = $data['guitar_id'] ?? null;
            $this->brand = $data['brand'] ?? null;
            $this->model = $data['model'] ?? null;
            $this->type = $data['type'] ?? null;
            $this->price = $data['price'] ?? null;
            $this->quantityInStock = $data['quantity_in_stock'] ?? null;
            $this->description = $data['description'] ?? null;
        }
    }

    // Getters
    public function getId()
    {
        return $this->guitarId;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getQuantityInStock()
    {
        return $this->quantityInStock;
    }

    public function getDescription()
    {
        return $this->description;
    }

    // Save (Insert/Update)
    public function save(PDO $pdo)
    {
        if ($this->guitarId) {
            // Update existing guitar
            $stmt = $pdo->prepare("UPDATE guitars SET brand = :brand, model = :model, type = :type, price = :price, quantity_in_stock = :quantity, description = :description WHERE guitar_id = :id");
            $stmt->execute([
                ':brand' => $this->brand,
                ':model' => $this->model,
                ':type' => $this->type,
                ':price' => $this->price,
                ':quantity' => $this->quantityInStock,
                ':description' => $this->description,
                ':id' => $this->guitarId
            ]);
        } else {
            // Insert new guitar
            $stmt = $pdo->prepare("INSERT INTO guitars (brand, model, type, price, quantity_in_stock, description) VALUES (:brand, :model, :type, :price, :quantity, :description)");
            $stmt->execute([
                ':brand' => $this->brand,
                ':model' => $this->model,
                ':type' => $this->type,
                ':price' => $this->price,
                ':quantity' => $this->quantityInStock,
                ':description' => $this->description
            ]);
            $this->guitarId = $pdo->lastInsertId();
        }
    }

    public static function getAll(PDO $pdo)
    {
        $stmt = $pdo->query("SELECT * FROM guitars");
        $guitars = [];
        while ($row = $stmt->fetch()) {
            $guitars[] = new Guitar($row);
        }
        return $guitars;
    }

    public function delete(PDO $pdo)
    {
        if ($this->guitarId) {
            $stmt = $pdo->prepare("DELETE FROM guitars WHERE guitar_id = :id");
            $stmt->execute([':id' => $this->guitarId]);
        }
    }
}
