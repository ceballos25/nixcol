<?php
class NumeroVendidoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarNumerosVendidos($search, $limit, $offset) {
        try {
            $queryBuilder = new QuerySelect();
            $query = $queryBuilder->select('v.id AS id_venta, n.numero AS numero, c.nombre AS cliente_nombre, c.celular, c.ciudad, v.fecha AS fecha_venta')
                                  ->from('numero_vendido nv')
                                  ->innerJoin('venta v', 'nv.id_venta = v.id')
                                  ->innerJoin('cliente c', 'v.id_cliente = c.id')
                                  ->innerJoin('numero n', 'nv.id_numero = n.id')
                                  ->where("n.numero LIKE :search OR c.nombre LIKE :search OR c.celular LIKE :search OR c.ciudad LIKE :search OR v.fecha LIKE :search")
                                  ->orderBy('v.id', 'DESC')
                                  ->limit($limit)
                                  ->offset($offset)
                                  ->getQuery();

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':search', '%' . $search . '%');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null; // Cerrar la conexión
            return $result;
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return [];
        }
    }

    public function contarNumerosVendidos($search) {
        try {
            $totalQuery = (new QuerySelect())->select('COUNT(*) as total')
                                             ->from('numero_vendido nv')
                                             ->innerJoin('venta v', 'nv.id_venta = v.id')
                                             ->innerJoin('cliente c', 'v.id_cliente = c.id')
                                             ->innerJoin('numero n', 'nv.id_numero = n.id')
                                             ->where("n.numero LIKE :search OR c.nombre LIKE :search OR c.celular LIKE :search OR c.ciudad LIKE :search OR v.fecha LIKE :search")
                                             ->getQuery();
            $totalStmt = $this->db->prepare($totalQuery);
            $totalStmt->bindValue(':search', '%' . $search . '%');
            $totalStmt->execute();
            $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
            $totalStmt = null; // Cerrar la conexión
            return $totalResult['total'];
        } catch (PDOException $e) {
            echo "Error al ejecutar la consulta: " . $e->getMessage();
            return 0;
        }
    }
}
?>