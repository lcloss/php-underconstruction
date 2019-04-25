<?php

namespace App;

use App\Database;
use App\Sql;

class Model
{
    protected $db;
    protected $table;
    protected $columns;
    protected $sql;
    protected $flash;

    public function __construct($db, $table, $flash)
    {
        $this->db = $db;
        $this->table = $table;
        $this->columns = array();
        $this->sql = new Sql($this->table);
        $this->flash = $flash;
    }

    public function isColumn($key)
    {
        return ( array_key_exists( $key, $this->columns ) ? true : false );
    }

    /**
     * Magic method for setters and getters
     */
    public function __call(string $name, array $args)
    {
        // Extract method from caller name
        $method = substr($name, 0, 3);
        $column = substr($name, 3, strlen($name) - 3);

        // Validate column name
        // First character must be a letter
        if ( !preg_match('/[a-zA-Z]/', substr($column, 1)) ) {
            throw new \Exception('Invalid column name: ' . $column);
        }

        if ( !preg_match('/[a-zA-Z0-9_]/', $column) ) {
            throw new \Exception('Invalid column name: ' . $column);
        }

        // Validate the method
        switch ($method) {
            case 'get':
                return ( $this->isColumn($column) ? $this->columns[$column] : null);
                break;

            case 'set':
                $this->columns[$column] = $args[0];
                break;

            default:
                throw new \Exception('Invalid request for ' . $name);
                break;
        }
    }

    public function setValues($params) 
    {
        foreach($params as $key => $value) {
            $this->{"set" . $key}($value);
        }
    }

    public function getValues()
    {
        return $this->columns;
    }

    public function execute($sql, $params)
    {
        $resp = $this->db->execute($sql, $params);
        return $resp;
    }

    public function query($sql, $params)
    {
        $resp = $this->db->select($sql, $params);

        return $resp;
    }

    /*
    private $db;
    protected $sql;
    private $values = [];
    protected $table;
    protected $columns = [];
    
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->sql = new Sql($this->table);
    }
    */
    /**
     * Begin a SQL transaction
     */
    /*
    public function beginTransaction(): void
    {
        $this->db->beginTransaction();
    }
    */
    /**
     * Commit changes to DB
     */
    /*
    public function commit(): void
    {
        $this->db->commit();
    }
    */
    
    /**
     * Revert changes from DB
     */
    /*
    public function rollback(): void
    {
        $this->db->rollback();
    }
    */
    /**
     * Get ID of last inserted row
     *
     * @return int
     */
    /*
    public function getLastInsertId(): int
    {
        return $this->db->getLastInsertId();
    }
    */
    /**
     * Get number of rows affetcted by last SQL
     *
     * @return int
     */
    /*
    public function getRowsAffected(): int
    {
        return $this->db->getRowsAffected();
    }
    */
}