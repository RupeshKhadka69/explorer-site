<?php
class ConnectDb
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "urban_explorer";
    private $connection;

    public static $instance = null;

    private function __construct()
    {
        try {
            // Making a connection
            $this->connection = new mysqli($this->host, $this->username, $this->password);

            if ($this->connection->connect_error) {
                throw new Exception("Connection error: " . $this->connection->connect_error);
            }

            // Creating a database
            $this->createDatabase();
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            $this->createTables();
        } catch (Exception $e) {
            die("Database Error: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function createDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->database";
        if (!$this->connection->query($sql)) {
            throw new Exception("Error creating database: " . $this->connection->error);
        }
    }

    private function createTables()
    {
        $users_tables = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL, -- Fixed typo
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"; // Removed trailing comma

        $discoveries_tables = "CREATE TABLE IF NOT EXISTS discoveries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            title VARCHAR(50) NOT NULL,
            description TEXT,
            category VARCHAR(50),
            latitude DECIMAL(10,8),
            longitude DECIMAL(11,8),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"; // No syntax issues here

        if (!$this->connection->query($users_tables) || !$this->connection->query($discoveries_tables)) {
            throw new Exception("Error creating tables: " . $this->connection->error);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    private function __clone()
    {
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
