<?php
include_once 'queryObject/objectSelect.php';

class DashboardQueries {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTotalClientes() {
        $query = (new QuerySelect())->select('COUNT(*) as total')
                                    ->from('cliente')
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $stmt = null; // Cerrar la conexión
    }

    public function getTotalVentas() {
        $query = (new QuerySelect())->select('COUNT(*) as total')
                                    ->from('venta')
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $stmt = null; // Cerrar la conexión
    }

    public function getTotalPagos() {
        $query = (new QuerySelect())->select('SUM(total_pago) as total')
                                    ->from('venta')
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $stmt = null; // Cerrar la conexión
    }

    public function getTotalNumerosVendidos() {
        $query = (new QuerySelect())->select('SUM(total_numeros) as total_numeros_vendidos')
                                    ->from('venta')
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_numeros_vendidos'];
        $stmt = null; // Cerrar la conexión
    }

    public function getFaltanxVender() {
        $query = (new QuerySelect())->select('COUNT(*) as total_disponibles')
                                    ->from('numero')
                                    ->where("estado = 'Disponible'")
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_disponibles'];
        $stmt = null; // Cerrar la conexión
    }

    public function getVentasPorTipoHoyPW($tipo) {
        $query = (new QuerySelect())->select('COUNT(*) as total_ventas, SUM(total_numeros) as total_numeros, SUM(total_pago) as total_dinero')
        ->from('venta')
        ->where("tipo = :tipo AND DATE(fecha) = CURDATE()")
        ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
    }


    public function getVentasPorTipoHoyVM($tipo) {
        $query = (new QuerySelect())->select('COUNT(*) as total_ventas, SUM(total_numeros) as total_numeros, SUM(total_pago) as total_dinero')
        ->from('venta')
        ->where("tipo = :tipo AND DATE(fecha) = CURDATE()")
        ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
    }

    public function getVentasPorTipoHoyGeneral($tipo) {
        $query = (new QuerySelect())->select('COUNT(*) as total_ventas, SUM(total_numeros) as total_numeros, SUM(total_pago) as total_dinero')
        ->from('venta')
        ->where("tipo = :tipo")
        ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
    }
    
    public function getTopClientes() {
        $query = (new QuerySelect())->select('c.celular, SUM(v.total_numeros) as total_numeros')
                                    ->from('venta v')
                                    ->innerJoin('cliente c', 'v.id_cliente = c.id')
                                    ->groupBy('c.celular')
                                    ->orderBy('total_numeros', 'DESC')
                                    ->limit(10)
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
    }

    public function getTopCiudades() {
        $query = (new QuerySelect())->select('c.ciudad, SUM(v.total_pago) as total_dinero')
                                    ->from('venta v')
                                    ->innerJoin('cliente c', 'v.id_cliente = c.id')
                                    ->groupBy('c.ciudad')
                                    ->orderBy('total_dinero', 'DESC')
                                    ->limit(10)
                                    ->getQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
    }

    }

?>