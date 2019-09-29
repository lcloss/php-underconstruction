<?php

namespace App;

class MakeTable
{
    protected $sql_create = <<<'EOT'
        CREATE TABLE {% tablename %}
            (
{% columns %}
            );
EOT;

    protected $sql_drop = <<<'EOT'
        DROP TABLE {% tablename %};
EOT;

    protected $sql_insert = <<<'EOT'
        INSERT INTO {% tablename %}
        (
{% columns %}
        ) VALUES (
{% values %}
        );
            
EOT;

    protected $sql_show_columns = "SHOW COLUMNS FROM {% tablename %};";
    protected $sql_alter_add_column = "ALTER TABLE {% tablename %} ADD COLUMN {% column %} {% properties %};";
    protected $sql_alter_modify_column = "ALTER TABLE {% tablename %} MODIFY COLUMN {% column %} {% properties %};";
    protected $sql_alter_drop_column = "ALTER TABLE {% tablename %} DROP COLUMN {% column %};";

    protected $table = '';
    protected $columns;
    protected $db;
    protected $seed = array();

    public function __construct($db, $table) 
    {
        $this->db = $db;
        $this->table = $table;
    }

    public function create() 
    {
        // Table definition and seed
        $make_tpl = file_get_contents('src/commands/templates/make-table.tpl');
        $make_tpl = str_replace('{% tablename %}', $this->table, $make_tpl);
        file_put_contents('app/Db/' . $this->table . 'Table.php', $make_tpl);
        
        // Table model
        $make_tpl = file_get_contents('src/commands/templates/table-model.tpl');
        $make_tpl = str_replace('{% tablename %}', $this->table, $make_tpl);
        file_put_contents('app/Models/' . $this->table . 'Model.php', $make_tpl);
    }

    public function make() 
    {
        $sql_columns = "";
        foreach($this->columns as $column => $prop) {
            if ( !empty($sql_columns) ) {
                $sql_columns .= ",\n\r";
            }
            $sql_columns .= "\t\t\t" . $column . "\t\t" . $prop;
        }

        // $sql = str_replace('{% tablename %}', strtolower($this->table), $this->sql_create);
        $sql = str_replace('{% tablename %}', $this->table, $this->sql_create);
        $sql = str_replace('{% columns %}', $sql_columns, $sql);

        $this->db->execute($sql);
        // echo $sql;
    }

    public function drop() 
    {
        $sql = str_replace('{% tablename %}', strtolower($this->table), $this->sql_drop);
        $this->db->execute($sql);
    }

    public function remove() 
    {
        // Table definition and seed
        unlink('app/Db/' . $this->table . 'Table.php');
        
        // Table model
        unlink('app/Models/' . $this->table . 'Model.php');
    }

    public function seed() 
    {
        foreach ($this->seed as $row) {
            $sql_columns = "";
            $sql_values = "";
            $parms = array();

            foreach ($row as $column => $value) {
                if ( !empty($sql_columns) ) {
                    $sql_columns .= ",\n\r";
                    $sql_values .= ",\n\r";
                }
                $sql_columns .= "\t\t\t`" . $column . "`";
                $sql_values .= "\t\t\t:" . $column;
                $parms[':' . $column] = $value;
            }

            $sql = str_replace('{% tablename %}', strtolower($this->table), $this->sql_insert);
            $sql = str_replace('{% columns %}', $sql_columns, $sql);
            $sql = str_replace('{% values %}', $sql_values, $sql);

            $this->db->execute($sql, $parms);

            return $this->db->getRowsAffected();
        }
    }

    public function update()
    {
        // Get table columns
        $sql = str_replace('{% tablename %}', strtolower($this->table), $this->sql_show_columns);
        $table_columns = $this->db->select($sql, array());

        // Get all columns names
        $table_column_names = array();
        $model_column_names = array();

        foreach($this->columns as $model_column => $model_prop) {
            $model_column_names[] = $model_column;
        }

        // Check for dropped columns
        foreach($table_columns as $column) {
            $table_column_names[] = $column['Field'];

            if ( !in_array($column['Field'], $model_column_names, false) ) {
                // Drop column
                $sql = str_replace('{% tablename %}', $this->table, $this->sql_alter_drop_column);
                $sql = str_replace('{% column %}', $column['Field'], $sql);
                $this->db->execute($sql, array());
            }
        }

        // Check for model
        foreach($this->columns as $model_column => $model_prop) {

            if ( in_array($model_column, $table_column_names, false)) {
                $i = 0;
                while ($i < count($table_columns) && $table_columns[$i]['Field'] != $model_column) {
                    $i++;
                }
                if ($i < count($table_columns)) {
                    $prop_ext = strtoupper($table_columns[$i]['Type']) . " ";
                    if ($table_columns[$i]['Null'] == 'NO') {
                        $prop_ext .= "NOT NULL ";
                    } else {
                        $prop_ext .= "NULL ";
                    }
                    if ($table_columns[$i]['Extra'] == 'auto_increment') {
                        $prop_ext .= "AUTO_INCREMENT ";
                    }
                    if ($table_columns[$i]['Key'] == 'PRI') {
                        $prop_ext .= "PRIMARY KEY ";
                    }
                    $prop_ext = trim($prop_ext);
                    if ($prop_ext != $model_prop) {
                        // Modify column
                        $sql = str_replace('{% tablename %}', $this->table, $this->sql_alter_modify_column);
                        $sql = str_replace('{% column %}', $model_column, $sql);
                        $sql = str_replace('{% properties %}', $model_prop, $sql);
                        $this->db->execute($sql, array());
                    }
                }
            } else {
                // New column
                $sql = str_replace('{% tablename %}', $this->table, $this->sql_alter_add_column);
                $sql = str_replace('{% column %}', $model_column, $sql);
                $sql = str_replace('{% properties %}', $model_prop, $sql);
                $this->db->execute($sql, array());
            }
        }

        return false;
    }
}