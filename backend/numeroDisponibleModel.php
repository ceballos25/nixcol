<?php
class NumeroDisponibleModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarNumerosDisponibles($search, $limit, $offset) {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('id, numero, estado')
                              ->from('numero')
                              ->where("numero LIKE :search OR estado LIKE :search")
                              ->orderBy('RAND()')
                              ->limit($limit)
                              ->offset($offset)
                              ->getQuery();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':search', '%' . $search . '%');
        $stmt->execute();
        $numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
        return $numeros !== false ? $numeros : [];
    }

    public function contarNumerosDisponibles($search) {
        $totalQuery = (new QuerySelect())->select('COUNT(*) as total')
                                         ->from('numero')
                                         ->where("numero LIKE :search OR estado LIKE :search")
                                         ->getQuery();
        $totalStmt = $this->db->prepare($totalQuery);
        $totalStmt->bindValue(':search', '%' . $search . '%');
        $totalStmt->execute();
        $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $totalStmt = null; // Cerrar la conexión
        return $totalResult !== false ? $totalResult['total'] : 0;
    }

    public function actualizarEstadoPorNumero($numero, $nuevoEstado) {
        try {
            $queryBuilder = new QueryUpdate();
            $query = $queryBuilder->table('numero')
                                  ->set(['estado' => $nuevoEstado])
                                  ->where("numero = :numero")
                                  ->getQuery();

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':estado', $nuevoEstado);
            $stmt->bindValue(':numero', $numero);
            $stmt->execute();
            $stmt = null; // Cerrar la conexión
        } catch (PDOException $e) {
            echo "Error al actualizar el estado: " . $e->getMessage();
        }
    }
}
?>