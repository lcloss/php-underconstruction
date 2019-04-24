<?php

namespace App;

class Sql
{
    private $_table = '';
    private $_statement = '';
    
    public function __construct(string $table = '') {
        $this->_table = $table;
    }
    
    public function setTable(string $table) {
        $this->_table = $table;
    }
    
    private function setStatement(string $statement) {
        $this->_statement = $statement;
    }
    
    private function addStatement(string $statement) {
        $this->_statement .= ' ' . $statement;
    }
    
    public function select(array $columns, string $alias = ''): \Core\Sql
    {
        $sql = "SELECT " . implode(", ", self::quote($columns, $alias));
        
        $this->setStatement($sql);
        
        if ( $this->_table != '' ) {
            $this->from($this->_table, $alias);
        }
        
        return $this;
    }
    
    public function selectRaw(string $select, string $alias = ''): \Core\Sql
    {
        $sql = "SELECT " . $select;
        
        $this->setStatement($sql);
        
        if ( $this->_table != '' ) {
            $this->from($this->_table, $alias);
        }
        
        return $this;
    }
    
    public function count(): \Core\Sql
    {
        $sql = "SELECT COALESCE(COUNT(*), 0) AS numrows";
        
        $this->setStatement($sql);
        
        if ( $this->_table != '' ) {
            $this->from($this->_table);
        }
        
        return $this;
    }
    
    public function insert(array $columns): \Core\Sql
    {
        $sql = "INSERT INTO " . $this->_table . " (" . implode(", ", self::quote($columns)) . ") VALUES (" . implode(", ", self::prepare($columns)) . ")";
        
        $this->setStatement($sql);
        
        return $this;
    }
    
    public function update(array $columns): \Core\Sql
    {
        $sql = "UPDATE " . $this->_table . " SET ";
        
        $update_arr = [];
        foreach ($columns as $column) {
            $update_arr[] = "`" . $column . "` = :" . $column;
        }
        
        $sql .= implode(", ", $update_arr);
        
        $this->setStatement($sql);
        
        return $this;
    }
    
    public function delete(): \Core\Sql
    {
        $sql = "DELETE FROM " . $this->_table;
        
        $this->setStatement($sql);
        
        return $this;
    }
    
    public function join(string $table, array $compare, string $alias1 = '', string $alias2 = ''): \Core\Sql
    {
        $sql = "INNER JOIN " . $table . ( $alias1 != '' ? " " . $alias1 : "" );
        $sql .= " ON " . implode(" AND ", self::compare($compare, $alias1, $alias2));
        
        $this->addStatement($sql);
        
        return $this;
    }
    
    public function get(): string
    {
        return trim($this->_statement);
    }
        
    public static function quote(array $columns, string $alias = ''): array
    {
        $columns_arr = [];
        
        foreach ($columns as $column) {
            if ( '*' === $column ) {
                $columns_arr[] = ( $alias != '' ? $alias . "." : "") . $column;
                
            } elseif ( false === strpos($column, ".") ) {
                $columns_arr[] = ( $alias != '' ? $alias . "." : "") . "`" . $column . "`";
                
            } else {
                $col_name = explode(".", $column);
                $columns_arr[] = $col_name[0] . ".`" . $col_name[1] . "`";
            }
        }
        
        return $columns_arr;
    }
    
    public function values(array $columns): array
    {
        $columns_arr = [];
        
        foreach ($columns as $column => $value) {
            $columns_arr[':' . $column] = $value;
        }
        
        return $columns_arr;
    }
    
    public static function prepare(array $columns): array
    {
        $prepared_columns = array();
        foreach($columns as $column) {
            $prepared_columns[] = ':' . $column;
        }
        
        return $prepared_columns;
    }
    
    public static function compare(array $compares, string $alias1 = '', string $alias2 = ''): array
    {
        $compare_arr = [];
        
        foreach($compares as $compare) {
            $column1_str = ( $alias1 != '' ? $alias1 . "." : "") . "`" . $compare . "`";
            $column2_str = ( $alias2 != '' ? $alias2 . ".`" . $compare . "`" : ":" . $compare );
            
            $compare_arr[] = $column1_str . " = " . $column2_str;
        }
        
        return $compare_arr;
    }
    
    public static function comparison(array $compares, string $alias1 = '', string $alias2 = ''): array
    {
        $compare_arr = [];
        
        foreach($compares as $compare1 => $compare2) {
            $column1_str = ( $alias1 != '' ? $alias1 . "." : "") . "`" . $compare1 . "`";
            $column2_str = ( $alias2 != '' ? $alias2 . ".`" . $compare2 . "`" : ":" . $compare2 );
                
            $compare_arr[] = $column1_str . " = " . $column2_str;  
        }
        
        return $compare_arr;
    }
    
    public function from(string $table, string $alias = ''): \Core\Sql
    {
        $sql = "FROM " . $table . ( $alias != '' ? " " . $alias : "");
        
        $this->addStatement($sql);
        
        return $this;
    }
    
    public function where(array $where, $alias = ''): \Core\Sql
    {
        if ( 0 === count($where) ) {
            return $this;
        }
        
        $where_arr = [];
        foreach ($where as $column) {
            if ( false === strpos($column, ".") ) {
                $where_arr[] = ( $alias != '' ? $alias . "." : "") . "`" . $column . "` = :" . $column;
            } else {
                $col_name = explode(".", $column);
                $where_arr[] = $col_name[0] . ".`" . $col_name[1] . "` = :" . $col_name[1];
            }
        }
        $sql = "WHERE " . implode(" AND ", $where_arr);
        
        $this->addStatement($sql);
        
        return $this;
    }
    
    public function whereCond(string $where): \Core\Sql
    {
        if ( $where == '' ) {
            return $this;
        }
        
        $sql = "WHERE " . $where;
        
        $this->addStatement($sql);
        
        return $this;
    }
    
    public function orderby(array $columns): \Core\Sql
    {
        if ( 0 === count($columns) ) {
            return $this;
        }
        
        $orderby_arr = [];
        
        foreach($columns as $column => $order) {
            $orderby_arr[] = trim($column . ' ' . $order);
        }
        
        $sql = "ORDER BY " . implode(", ", $orderby_arr);
        
        $this->addStatement($sql);
        
        return $this;
    }
    
    public function groupby(array $columns): \Core\Sql
    {
        if ( 0 === count($columns) ) {
            return $this;
        }
        
        $sql = "GROUP BY " . implode(", ", $columns);
        
        $this->addStatement($sql);
        
        return $this;
    }
}
