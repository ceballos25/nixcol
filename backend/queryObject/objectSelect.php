<?php

class QuerySelect {
    private $select;
    private $from;
    private $joins = [];
    private $where = [];
    private $groupBy;
    private $orderBy;
    private $limit;
    private $offset;

    public function select($columns) {
        $this->select = "SELECT " . $columns;
        return $this;
    }

    public function from($table) {
        $this->from = "FROM " . $table;
        return $this;
    }

    public function where($condition, $logicalOperator = 'AND') {
        if (empty($this->where)) {
            $this->where = "WHERE " . $condition;
        } else {
            $this->where .= " $logicalOperator " . $condition;
        }
        return $this;
    }

    public function innerJoin($table, $onCondition) {
        $this->joins[] = "INNER JOIN $table ON $onCondition";
        return $this;
    }

    public function leftJoin($table, $onCondition) {
        $this->joins[] = "LEFT JOIN $table ON $onCondition";
        return $this;
    }

    public function groupBy($columns) {
        $this->groupBy = "GROUP BY " . $columns;
        return $this;
    }

    public function orderBy($column, $order = 'ASC') {
        if (empty($this->orderBy)) {
            $this->orderBy = "ORDER BY $column $order";
        } else {
            $this->orderBy .= ", $column $order";
        }
        return $this;
    }

    public function limit($limit) {
        $this->limit = "LIMIT " . $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = "OFFSET " . $offset;
        return $this;
    }

    public function whereIn($column, $values, $logicalOperator = 'AND') {
        $inValues = implode(', ', array_map(function($value) {
            return "'" . $value . "'";
        }, $values));

        $condition = "$column IN ($inValues)";

        if (empty($this->where)) {
            $this->where = "WHERE " . $condition;
        } else {
            $this->where .= " $logicalOperator " . $condition;
        }

        return $this;
    }

    public function getQuery() {
        $query = "{$this->select} {$this->from}";

        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $query .= " $this->where";
        }

        if (!empty($this->groupBy)) {
            $query .= " $this->groupBy";
        }

        if (!empty($this->orderBy)) {
            $query .= " $this->orderBy";
        }

        if (!empty($this->limit)) {
            $query .= " $this->limit";
        }

        if (!empty($this->offset)) {
            $query .= " $this->offset";
        }

        return $query;
    }
}
?>