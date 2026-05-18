<?php
/**
 * Base Model Class
 * All models extend this
 */

class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Find by ID
     */
    public function findById($id) {
        $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $this->db->bind('i', $id);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Find all
     */
    public function findAll($limit = null, $offset = 0, $orderBy = 'id', $order = 'DESC') {
        $query = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $this->db->prepare($query);
            $this->db->bind('i', $limit);
            $this->db->bind('i', $offset);
        } else {
            $this->db->prepare($query);
        }
        
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Find by column
     */
    public function findBy($column, $value) {
        $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
        
        $type = $this->getParamType($value);
        $this->db->bind($type, $value);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Find all by column
     */
    public function findAllBy($column, $value) {
        $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? ORDER BY id DESC");
        
        $type = $this->getParamType($value);
        $this->db->bind($type, $value);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Count records
     */
    public function count($column = null, $value = null) {
        if ($column && $value) {
            $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} = ?");
            $type = $this->getParamType($value);
            $this->db->bind($type, $value);
        } else {
            $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table}");
        }
        
        $this->db->execute();
        $result = $this->db->single();
        return $result['count'];
    }

    /**
     * Insert - uses bindArray to avoid reference issues
     */
    public function insert($data) {
        if (empty($data)) {
            throw new Exception('No data provided for insert');
        }
        
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($data), '?');
        
        $query = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $this->db->prepare($query);
            
            // Build types string and values array
            $types = '';
            foreach ($values as $value) {
                $types .= $this->getParamType($value);
            }
            
            // Use bindArray to bind all values at once (avoids reference issues)
            $this->db->bindArray($types, $values);
            $this->db->execute();
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            throw new Exception('Insert failed: ' . $e->getMessage());
        }
    }

    /**
     * Update
     */
    public function update($id, $data) {
        $setClause = implode(', ', array_map(fn($key) => "{$key} = ?", array_keys($data)));
        
        $this->db->prepare("UPDATE {$this->table} SET {$setClause} WHERE id = ?");
        
        foreach ($data as $value) {
            $type = $this->getParamType($value);
            $this->db->bind($type, $value);
        }
        
        $this->db->bind('i', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Delete
     */
    public function delete($id) {
        $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $this->db->bind('i', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Get parameter type
     */
    protected function getParamType($value) {
        if (is_int($value)) {
            return 'i';
        } elseif (is_float($value)) {
            return 'd';
        }
        return 's';
    }

    /**
     * Generate slug from string
     */
    protected function generateSlug($string) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
        return $slug;
    }
}
