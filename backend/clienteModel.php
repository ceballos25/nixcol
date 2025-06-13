<?php
// filepath: /c:/xampp/htdocs/edts-v3/backend/clienteModel.php
include_once 'queryObject/objectSelect.php';
include_once 'queryObject/objectUpdate.php';
include_once 'queryObject/objectDelete.php';

class ClienteModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarClientes($search, $limit, $offset) {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('*')
                              ->from('cliente')
                              ->where("nombre LIKE :search OR celular LIKE :search OR correo LIKE :search OR departamento LIKE :search OR ciudad LIKE :search")
                              ->orderBy('id', 'DESC')
                              ->limit($limit)
                              ->offset($offset)
                              ->getQuery();

        $stmt = $this->db->prepare($query);
        try {
            $stmt->bindValue(':search', '%' . $search . '%');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result !== false ? $result : [];
        } finally {
            $stmt = null; // Cerrar la conexión
        }
    }

    public function contarClientes($search) {
        $totalQuery = (new QuerySelect())->select('COUNT(*) as total')
                                         ->from('cliente')
                                         ->where("nombre LIKE :search OR celular LIKE :search OR correo LIKE :search OR departamento LIKE :search OR ciudad LIKE :search")
                                         ->getQuery();
        $totalStmt = $this->db->prepare($totalQuery);
        try {
            $totalStmt->bindValue(':search', '%' . $search . '%');
            $totalStmt->execute();
            $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
            return $totalResult !== false ? $totalResult['total'] : 0;
        } finally {
            $totalStmt = null; // Cerrar la conexión
        }
    }

    public function obtenerClientePorId($id) {
        $query = "SELECT * FROM cliente WHERE id = :id";
        $stmt = $this->db->prepare($query);
        try {
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false ? $result : null;
        } finally {
            $stmt = null; // Cerrar la conexión
        }
    }

    public function obtenerClientePorCelular($celular) {
        $query = "SELECT * FROM cliente WHERE celular = :celular";
        $stmt = $this->db->prepare($query);
        try {
            $stmt->bindValue(':celular', $celular);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result !== false ? $result : null;
        } finally {
            $stmt = null; // Cerrar la conexión
        }
    }

    public function actualizarCliente($id, $data) {
        $queryBuilder = new QueryUpdate();
        $query = $queryBuilder->table('cliente')
                              ->set($data)
                              ->where("id = :id")
                              ->getQuery();

        $stmt = $this->db->prepare($query);
        try {
            foreach ($data as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        } finally {
            $stmt = null; // Cerrar la conexión
        }
    }

    public function eliminarCliente($id) {
        $queryBuilder = new QueryDelete();
        $query = $queryBuilder->from('cliente')
                              ->where("id = :id")
                              ->getQuery();

        $stmt = $this->db->prepare($query);
        try {
            $stmt->bindValue(':id', $id);
            $stmt->execute();
        } finally {
            $stmt = null; // Cerrar la conexión
        }
    }
}
?>