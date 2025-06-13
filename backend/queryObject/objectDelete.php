<?php

class QueryDelete {
    private $table;
    private $where;

    public function from($table) {
        $this->table = "FROM " . $table;
        return $this;
    }

    public function where($conditions) {
        $this->where = "WHERE " . $conditions;
        return $this;
    }

    public function getQuery() {
        return "DELETE {$this->table} {$this->where}";
    }
}

// Uso de ejemplo
// $queryDelete = new QueryDelete();
// $queryDelete->from("tabla_usuarios")
//             ->where("id = 1");
