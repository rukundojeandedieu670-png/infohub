<?php
/**
 * Database Class - Singleton Pattern
 * Handles all database connections and queries
 */

class Database {
    private static $instance = null;
    private $connection;
    private $stmt;
    private $bindParams = [];

    private function __construct() {
        try {
            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                DB_PORT
            );

            // Check connection
            if ($this->connection->connect_error) {
                throw new Exception('Database connection failed: ' . $this->connection->connect_error);
            }

            // Set charset
            $this->connection->set_charset(DB_CHARSET);

        } catch (Exception $e) {
            // Don't use Logger here to avoid infinite loop
            error_log('Database Connection Error: ' . $e->getMessage());
            die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
        }
    }

    /**
     * Get Database Instance (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prepare statement
     */
    public function prepare($query) {
        $this->stmt = $this->connection->prepare($query);
        if (!$this->stmt) {
            error_log('Database Prepare Error: ' . $this->connection->error);
            throw new Exception('Database prepare failed');
        }
        $this->bindParams = [];
        return $this;
    }

    /**
     * Bind parameters - supports multiple calls
     * Usage: $db->bind('i', $id)->bind('s', $name)->execute()
     */
    public function bind($type, &$value) {
        // Validate type is a single character (i, d, s, b)
        if (!in_array($type, ['i', 'd', 's', 'b'])) {
            throw new Exception('Invalid bind type: ' . $type);
        }
        $this->bindParams[] = ['type' => $type, 'value' => &$value];
        return $this;
    }

    /**
     * Bind array of values - alternative method that doesn't use references in the loop
     * Usage: $db->bindArray('iss', [$id, $name, $email])
     */
    public function bindArray($types, $values) {
        if (strlen($types) !== count($values)) {
            throw new Exception('Number of types does not match number of values');
        }
        
        // Convert to references for bind_param which requires them
        $params = [$types];
        foreach ($values as &$value) {
            $params[] = &$value;
        }
        unset($value); // Unset reference after loop
        
        call_user_func_array([$this->stmt, 'bind_param'], $params);
        return $this;
    }

    /**
     * Apply all bound parameters to statement
     */
    private function applyBindParams() {
        if (empty($this->bindParams)) {
            return;
        }

        $types = '';
        $values = [];
        
        foreach ($this->bindParams as $param) {
            $types .= $param['type'];
            $values[] = &$param['value'];
        }

        call_user_func_array([$this->stmt, 'bind_param'], array_merge([$types], $values));
    }

    /**
     * Execute prepared statement
     */
    public function execute() {
        // Apply all bound parameters before executing
        $this->applyBindParams();
        
        if (!$this->stmt->execute()) {
            error_log('Database Execute Error: ' . $this->stmt->error);
            throw new Exception('Query execution failed');
        }
        return $this;
    }

    /**
     * Get single result
     */
    public function single() {
        return $this->stmt->get_result()->fetch_assoc();
    }

    /**
     * Get all results
     */
    public function resultSet() {
        $result = $this->stmt->get_result();
        $results = [];
        
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        
        return $results;
    }

    /**
     * Get row count
     */
    public function rowCount() {
        return $this->stmt->affected_rows;
    }

    /**
     * Get insert ID
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->connection->begin_transaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        return $this->connection->rollback();
    }

    /**
     * Get raw database connection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * Prevent cloning
     */
    public function __clone() {}

    /**
     * Prevent unserializing
     */
    public function __wakeup() {}
}
