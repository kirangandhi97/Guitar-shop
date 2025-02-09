<?php
class Order
{
    private $orderId;
    private $userId;
    private $orderDate;
    private $totalAmount;
    private $status;
    private $items = []; // Array to hold OrderItem objects

    /**
     * Constructor initializes the order data.
     *
     * @param array $data Optional array to initialize order properties.
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->orderId    = $data['order_id'] ?? null;
            $this->userId     = $data['user_id'] ?? null;
            $this->orderDate  = $data['order_date'] ?? null;
            $this->totalAmount = $data['total_amount'] ?? 0;
            $this->status     = $data['status'] ?? 'pending';
        }
    }

    /**
     * Adds an order item to this order and updates the total amount.
     *
     * @param OrderItem $item
     */
    public function addItem(OrderItem $item)
    {
        $this->items[] = $item;
        $this->totalAmount += ($item->getPriceAtPurchase() * $item->getQuantity());
    }

    /**
     * Saves the order and its items to the database using PDO.
     *
     * @param PDO $pdo
     */
    public function save(PDO $pdo)
    {
        // Insert order record
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date, total_amount, status) VALUES (:user_id, NOW(), :total_amount, :status)");
        $stmt->execute([
            ':user_id'     => $this->userId,
            ':total_amount' => $this->totalAmount,
            ':status'      => $this->status
        ]);
        $this->orderId = $pdo->lastInsertId();

        // Insert each order item, linking it to this order
        foreach ($this->items as $item) {
            $item->setOrderId($this->orderId);
            $item->save($pdo);
        }
    }

    // Optionally, you can add getters/setters for properties if needed.
}
