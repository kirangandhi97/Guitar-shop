<?php
class User
{
    private $userId;
    private $username;
    private $email;
    private $passwordHash;
    private $createdAt;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->userId      = $data['user_id'] ?? null;
            $this->username    = $data['username'] ?? null;
            $this->email       = $data['email'] ?? null;
            $this->passwordHash = $data['password_hash'] ?? null;
            $this->createdAt   = $data['created_at'] ?? null;
        }
    }

    // Getters and setters
    public function getId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    // Save user record
    public function save(PDO $pdo)
    {
        if ($this->userId) {
            $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, password_hash = :password_hash WHERE user_id = :id");
            $stmt->execute([
                ':username' => $this->username,
                ':email' => $this->email,
                ':password_hash' => $this->passwordHash,
                ':id' => $this->userId
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, created_at) VALUES (:username, :email, :password_hash, NOW())");
            $stmt->execute([
                ':username' => $this->username,
                ':email' => $this->email,
                ':password_hash' => $this->passwordHash
            ]);
            $this->userId = $pdo->lastInsertId();
        }
    }

    // Find user by username
    public static function findByUsername(PDO $pdo, $username)
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $data = $stmt->fetch();
        return $data ? new User($data) : null;
    }
}
