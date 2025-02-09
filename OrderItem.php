<?php
class OrderItem
{
    private $orderItemId;
    private $orderId;
    private $guitarId;
    private $quantity;
    private $priceAtPurchase;

    /**
     * Constructor initializes the order item data.
     *
     * @param array $data Optional array to initialize order item properties.
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->orderItemId   = $data['order_item_id'] ?? null;
            $this->orderId       = $data['order_id'] ?? null;
            $this->guitarId      = $data['guitar_id'] ?? null;
            $this->quantity      = $data['quantity'] ?? 0;
            $this->priceAtPurchase = $data['price_at_purchase'] ?? 0;
        }
    }

    /**
     * Sets the order ID for this item (used when saving the order).
     *
     * @param int $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Returns the quantity ordered.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Returns the price at purchase time.
     *
     * @return float
     */
    public function getPriceAtPurchase()
    {
        return $this->priceAtPurchase;
    }

    /**
     * Saves the order item to the database.
     *
     * @param PDO $pdo
     */
    public function save(PDO $pdo)
    {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, guitar_id, quantity, price_at_purchase) VALUES (:order_id, :guitar_id, :quantity, :price_at_purchase)");
        $stmt->execute([
            ':order_id'        => $this->orderId,
            ':guitar_id'       => $this->guitarId,
            ':quantity'        => $this->quantity,
            ':price_at_purchase' => $this->priceAtPurchase
        ]);
        $this->orderItemId = $pdo->lastInsertId();
    }

    // Optionally, you can add getters/setters for other properties if needed.
}
