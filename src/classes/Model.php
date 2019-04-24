<?php

namespace App;

class Model
{
    private $db;    // Core\Database
    protected $sql; // Core\Sql
    private $values = [];
    protected $table;
    protected $columns = [];
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->sql = new Sql($this->table);
    }
    
    /**
     * This magic method implement all getters and setters
     *
     * @param string $name
     * @param array $args
     */
    public function __call(string $name, array $args)
    {
        /* Check if it is only getters and setters */
        $method = substr($name, 0, 3);
        $fieldName = substr($name, 3, strlen($name) - 3);
        
        /* Check also if $fieldName starts with small case */
        if ( !preg_match('/[a-z]/', substr($fieldName, 0, 1)) ) {
            throw new \Exception('Call to undefined method: ' . $name);
        }
        
        switch ($method)
        {
            case 'get':
                return (isset($this->values[$fieldName]) ? $this->values[$fieldName] : NULL);
                break;
                
            case 'set':
                $this->values[$fieldName] = $args[0];
                break;
                
            default:
                throw new \Exception('Call to undefined method: ' . $name);
                
        }
    }
    
    /**
     * Set all values at once
     *
     * @param array $data
     */
    public function setValues(array $data): void
    {
        foreach ($data as $key => $value)
        {
            $this->{"set" . $key}($value);
        }
    }
    
    /**
     * Get all values
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }
    
    /**
     * Begin a SQL transaction
     */
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }
    
    /**
     * Commit changes to DB
     */
    public function commit(): void
    {
        $this->db->commit();
    }
    
    /**
     * Revert changes from DB
     */
    public function rollback(): void
    {
        $this->db->rollback();
    }
    
    /**
     * Get ID of last inserted row
     *
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->db->getLastInsertId();
    }
    
    /**
     * Get number of rows affetcted by last SQL
     *
     * @return int
     */
    public function getRowsAffected(): int
    {
        return $this->db->getRowsAffected();
    }
    
    /**
     * Execute a SQL Statement with prepare
     *
     * @param string $sql
     * @param array $parameters
     * @return bool
     */
    public function execute(string $sql, array $parameters): bool
    {
        return $this->db->execute($sql, $parameters);
    }
    
    /**
     * Execute a SQL Select with prepare
     *
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    public function query(string $sql, array $parameters): array
    {
        return $this->db->select($sql, $parameters);
    }
}