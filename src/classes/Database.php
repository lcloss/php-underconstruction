<?php

namespace App;

class Database 
{
    protected $conn;
    protected $stmt;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getLastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function getRowsAffected()
    {
        return $this->stmt->rowCount();
    }

    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }

    public function commit()
    {
        $this->conn->commit();
    }

    public function rollback()
    {
        $this->conn->rollBack();
    }

    public function prepare($sql)
    {
        $this->stmt = $this->conn->prepare($sql);
    }

    public function bindParam($key, $value)
    {
        $this->stmt->bindParam($key, $value);
    }

    public function bindParams($params)
    {
        foreach($params as $key => $value) {
            $this->bindParam($key, $value);
        }
    }

    public function getErrorInfo()
    {
        $errors = $this->stmt->errorInfo();
        return $errors[0] . ';' . $errors[1] . ';' . $errors[2];
    }

    
    public function execute($sql, $params = [])
    {
        $this->prepare($sql);
        $this->bindParams($params);

        $result = $this->stmt->execute();

        if ( !$result ) {
            throw new \Exception($this->getErrorInfo());
        }

        return $result;
    }

    public function select($sql, $params)
    {
        $result = $this->execute($sql, $params);

        if ( !$result ) {
            return null;
        }

        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectRow($sql, $params)
    {
        $result = $this->execute($sql, $params);

        if ( !$result ) {
            return null;
        }

        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }
}