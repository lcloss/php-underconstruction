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

        $sql = str_replace('{% tablename %}', strtolower($this->table), $this->sql_create);
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
}