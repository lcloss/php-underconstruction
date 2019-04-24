<?php

namespace App;

class Database 
{
    private static $_instance = null;
    private static $_conn;
    private static $_settings = [];
    private $_stmt;
    
    /**
     * Must be an empty method as this class is a Singleton
     */
    public function __construct() {}
    
    public function __destruct() {}
    
    public function __clone() {}
    
    public function __wakeup() {}
    
    /**
     * Create MySQL Connection through PDO
     *
     * @param array $_settings (database, hostname, username, password)
     * @throws \Exception
     */
    public static function setConnection($settings):void
    {
        /* Only allow a new connection if I am not instantiate yet. */
        if ( self::$_instance == null ) {
            /* Assign database connection settings */
            foreach ($settings as $key => $value) {
                self::$_settings[$key] = $value;
            }
            
            /* Create a new PDO connection */
            try {
                self::$_conn = new \PDO("mysql:dbname=" . self::$_settings['database'] .
                    ";host=" . self::$_settings['hostname'] . ";charset=utf8",
                    self::$_settings['username'] , self::$_settings['password']);
            } catch (\PDOException $e) {
                throw new \Exception($e->getMessage());
            }
            
            self::$_instance = new Database();
        }
        
    }
    
    /**
     * Return PDO connection
     *
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return self::$_conn;
    }
    
    /**
     * Get an Instance of myself
     *
     * @return \Core\Database
     */
    public static function getInstance(): \Core\Database
    {
        if ( self::$_instance == null ) {
            self::$_instance = new Database();
        }
        
        return self::$_instance;
    }
    
    /**
     * Start a new transaction
     */
    public function beginTransaction(): void
    {
        self::$_conn->beginTransaction();
    }
    
    /**
     * Commit SQL changes
     */
    public function commit(): void
    {
        self::$_conn->commit();
    }
    
    /**
     * Rollback SQL changes
     */
    public function rollback(): void
    {
        self::$_conn->rollBack();
    }
    
    /**
     * Execute SQL Statement
     *
     * @param string $sql
     * @param array $parameters
     * @throws \Exception
     * @return boolean
     */
    public function execute(string $sql, array $parameters): bool
    {
        $this->_stmt = self::$_conn->prepare($sql);
        self::_setParams($parameters);
        
        $response = $this->_stmt->execute();
        
        if ( !$response ) {
            throw new \Exception($this->printErrorInfo());
        }
        
        return $response;
    }
    
    /**
     * Execute a SELECT statement and return rows
     *
     * @param string $sql
     * @param array $parameters
     * @throws \Exception
     * @return array
     */
    public function select(string $sql, array $parameters): array
    {
        $response = self::execute($sql, $parameters);
        
        if ( !$response ) {
            throw new \Exception($this->printErrorInfo());
        }
        
        return $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function selectOne(string $sql, array $parameters): array
    {
        $response = self::execute($sql, $parameters);
        
        if ( !$response ) {
            throw new \Exception($this->printErrorInfo());
        }
        
        return $this->_stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get last INSERT ID
     *
     * @return int
     */
    public function getLastInsertId(): int
    {
        return self::$_conn->lastInsertId();
    }
    
    /**
     * Get rows affected by last SQL
     *
     * @return int
     */
    public function getRowsAffected(): int
    {
        return $this->_stmt->rowCount();
    }
    
    public function getErrorCode(): int
    {
        return self::$_conn->errorCode();
    }
    
    public function getErrorInfo(): string
    {
        return self::$_conn->errorInfo();
    }
    
    /**
     * Set parameters to bind a SQL Statement
     *
     * @param array $parameters
     */
    private static function _setParams($parameters = array()): void
    {
        foreach ($parameters as $key => $value) {
            self::$_instance->_bindParams($key, $value);
        }
    }
    
    /**
     * Bind parameters to a SQL Statement
     * @param $key
     * @param $value
     */
    private function _bindParams($key, $value)
    {
        $this->_stmt->bindParam($key, $value);
    }
    
    public function printErrorInfo(): string
    {
        $error = $this->_stmt->errorInfo();
        
        return $error[0] . '; ' . $error[1] . '; ' . $error[2];
    }
}