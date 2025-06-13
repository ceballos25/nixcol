<?php

class QueryInsert {
    private $table;
    private $columns;
    private $values;

    public function into($table) {
        $this->table = "INTO " . $table;
        return $this;
    }

    public function columns($columns) {
        $this->columns = "(" . implode(', ', $columns) . ")";
        return $this;
    }

    public function values($values) {
        $this->values = "VALUES (" . implode(', ', array_map(function($value) {
            // Assuming values are strings, you may need to adjust this based on your needs
            return "'" . $value . "'";
        }, $values)) . ")";
        return $this;
    }

    public function getQuery() {
        return "INSERT {$this->table} {$this->columns} {$this->values}";
    }
}

// Uso de ejemplo
// $queryInsert = new QueryInsert();
// $queryInsert->into("tabla_usuarios")
//             ->columns(["nombre", "edad", "email"])
//             ->values(["Juan", 25, "juan@example.com"]);

?>
