<?php
class RespaldoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarRespaldo($search, $limit, $offset) {
        $queryBuilder = new QuerySelect();
        $queryBuilder->select('id, cliente, celular, correo, departamento, ciudad, oportunidades, pago, id_transaccion, fecha')
                     ->from('respaldo');

        if ($search !== '') {
            $queryBuilder->where("cliente LIKE :search OR celular LIKE :search OR correo LIKE :search OR departamento LIKE :search OR ciudad LIKE :search OR id_transaccion LIKE :search OR fecha LIKE :search");
        }

        $queryBuilder->orderBy('id', 'DESC')
                     ->limit($limit)
                     ->offset($offset);

        $query = $queryBuilder->getQuery();

        $stmt = $this->db->prepare($query);
        if ($search !== '') {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
        return $result !== false ? $result : [];
    }

    public function contarRespaldo($search) {
        $totalQueryBuilder = new QuerySelect();
        $totalQueryBuilder->select('COUNT(*) as total')
                          ->from('respaldo');

        if ($search !== '') {
            $totalQueryBuilder->where("cliente LIKE :search OR celular LIKE :search OR correo LIKE :search OR departamento LIKE :search OR ciudad LIKE :search OR id_transaccion LIKE :search OR fecha LIKE :search");
        }

        $totalQuery = $totalQueryBuilder->getQuery();
        $totalStmt = $this->db->prepare($totalQuery);
        if ($search !== '') {
            $totalStmt->bindValue(':search', '%' . $search . '%');
        }
        $totalStmt->execute();
        $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $totalStmt = null; // Cerrar la conexión
        return $totalResult !== false ? $totalResult['total'] : 0;
    }
}
?>