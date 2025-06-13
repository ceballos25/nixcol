<?php
include_once 'queryObject/objectInsert.php';
include_once 'queryObject/objectSelect.php';
include_once 'queryObject/objectUpdate.php';
include_once 'queryObject/objectDelete.php';
include_once 'clienteModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class VentaModel
{
    private $db;
    private $clienteModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->clienteModel = new ClienteModel($db);
    }

    public function procesarVenta($data, $numeroPremiado = null) {
        try {
            $this->db->beginTransaction();
    
            // 1. Obtener o crear cliente
            $clienteId = $this->obtenerOcrearClienteId($data['cliente']);
            $data['cliente_id'] = $clienteId;
    
            // 2. Manejar número premiado si existe
            $numerosPremiados = [];
            if ($numeroPremiado !== null) {
                $premiadoInfo = $this->obtenerYReservarNumeroPremiado($numeroPremiado);
                if (!$premiadoInfo) {
                    throw new Exception("El número premiado {$numeroPremiado} no está disponible.");
                }
                $numerosPremiados = [$premiadoInfo];
            }

    
            // 3. Obtener números restantes
            $cantidadRestante = $data['total_numeros'] - count($numerosPremiados);
            $numerosAleatorios = $this->obtenerNumerosAleatorios($cantidadRestante, $numerosPremiados);
    
            // 4. Combinar todos los números
            $numeros = array_merge($numerosPremiados, $numerosAleatorios);
            shuffle($numeros); // Mezcla el orden de los números
    
            // 5. Insertar venta
            $ventaId = $this->insertarVenta($data);
    
            // 6. Actualizar estados de números
            $this->actualizarEstadoNumeros($numeros, 'Vendido');
    
            // 7. Insertar números vendidos
            $this->insertarNumerosVendidos($ventaId, $clienteId, $numeros);
    
            $this->db->commit();
    
            // Generar respuesta
            $html = $this->prepararHtmlRespuesta($data, $numeros);
            $this->enviarCorreoConfirmacion($data['cliente']['email'], $data['id_transaccion'], $html);
    
            return $html;
        } catch (Exception $e) {
            $this->db->rollBack();
            return "<h3>Error:</h3><p>" . $e->getMessage() . "</p>";
        }
    }
    
    // Añade este método
    private function obtenerNumerosAleatorios($cantidad, $excluir = []) {
        $excluirIds = array_column($excluir, 'id');
        $placeholders = !empty($excluirIds) ? "id NOT IN (" . implode(',', $excluirIds) . ")" : "1=1";
    
        $query = (new QuerySelect())
            ->select('id, numero')
            ->from('numero')
            ->where("estado = 'Disponible' AND $placeholders")
            ->orderBy('RAND()')
            ->limit($cantidad)
            ->getQuery();
    
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function insertarVenta($data)
    {
        $queryBuilder = new QueryInsert();
        $query = $queryBuilder->into('venta')
            ->columns(['id_cliente', 'total_numeros', 'total_pago', 'id_transaccion', 'tipo', 'vendedor'])
            ->values([
                $data['cliente_id'],
                $data['total_numeros'],
                $data['total_pago'],
                $data['id_transaccion'],
                $data['tipo'],
                $data['vendedor']
            ])
            ->getQuery();
        $stmt = $this->db->prepare($query);
        try {
            $stmt->execute();
            return $this->db->lastInsertId();
        } finally {
            $stmt = null;
        }
    }

    public function actualizarEstadoNumeros($numeros, $estado)
    {
        foreach ($numeros as $numero) {
            $queryBuilderUpdate = new QueryUpdate();
            $queryUpdate = $queryBuilderUpdate->table('numero')
                ->set(['estado' => $estado])
                ->where("id = :id")
                ->getQuery();
            $stmtUpdate = $this->db->prepare($queryUpdate);
            try {
                $stmtUpdate->bindValue(':estado', $estado);
                $stmtUpdate->bindValue(':id', $numero['id']);
                $stmtUpdate->execute();
            } finally {
                $stmtUpdate = null;
            }
        }
    }

    private function insertarNumerosVendidos($ventaId, $clienteId, $numeros)
    {
        foreach ($numeros as $numero) {
            $queryBuilderInsert = new QueryInsert();
            $queryInsert = $queryBuilderInsert->into('numero_vendido')
                ->columns(['id_venta', 'id_cliente', 'id_numero'])
                ->values([$ventaId, $clienteId, $numero['id']])
                ->getQuery();
            $stmtInsert = $this->db->prepare($queryInsert);
            try {
                $stmtInsert->execute();
            } finally {
                $stmtInsert = null;
            }
        }
    }

    //nueva funcion
    private function obtenerYReservarNumeroPremiado($numero) {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('id, numero')
            ->from('numero')
            ->where('numero = :numero AND estado = "Disponible"')
            ->limit(1)
            ->getQuery();
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':numero', $numero);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
    
        return $result; // Retorna el número si está disponible
    }
    
    //fianliza nuevo codigo


    private function prepararHtmlRespuesta($data, $numeros)
    {
        // Asegúrate de que $numeros sea un array
        if (!is_array($numeros)) {
            $numeros = [];
        }
    
        // Cargar la plantilla HTML
        $templatePath = DOCUMENT_ROOT . '/backend/templeate/templeate.html'; // Asegúrate de que la ruta sea correcta
        $html = file_get_contents($templatePath);
    
        // Función para determinar el fondo del número basado en los grupos específicos
        function obtenerFondo($numero)
        {
            // Definir los números en cada grupo
            $grupo1 = ['9999', '0101', '3333', '3333', '0000', '5656'];   // 600
    
            // Comprobar en qué grupo se encuentra el número
            if (in_array($numero, $grupo1)) {
                return ' #25d366';  // Fondo para el Grupo 1
            } else {
                return '#FFF';  // Fondo por defecto
            }
        }
    
        // Generar los números en formato HTML con el fondo correspondiente
        $numerosHtml = implode('', array_map(function ($numero) {
            $fondo = obtenerFondo((string)$numero['numero']); // Llamamos a la función obtenerFondo
            return "<span class='number' style='background-color: $fondo'>" . htmlspecialchars($numero['numero']) . "</span>";
        }, $numeros));
    
        // Reemplazar las variables en la plantilla con los datos dinámicos
        $html = str_replace(
            ['{clienteNombre}', '{cantidadNumeros}', '{numerosGenerados}'],
            [
                htmlspecialchars($data['cliente']['nombre']),
                htmlspecialchars($data['total_numeros']),
                $numerosHtml
            ],
            $html
        );
    
        return $html;
    }
    

    public function obtenerOcrearClienteId($clienteData)
    {
        $cliente = $this->clienteModel->obtenerClientePorCelular($clienteData['celular']);
        if ($cliente) {
            return $cliente['id'];
        } else {
            $queryBuilder = new QueryInsert();
            $query = $queryBuilder->into('cliente')
                ->columns(['nombre', 'celular', 'correo', 'departamento', 'ciudad'])
                ->values([
                    $clienteData['nombre'],
                    $clienteData['celular'],
                    $clienteData['email'],
                    $clienteData['departamento'],
                    $clienteData['ciudad']
                ])
                ->getQuery();
            $stmt = $this->db->prepare($query);
            try {
                $stmt->execute();
                return $this->db->lastInsertId();
            } finally {
                $stmt = null;
            }
        }
    }

    private function obtenerNumeros($cantidad)
    {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('id, numero')
            ->from('numero')
            ->where('estado = "Disponible"')
            //->orderBy('RAND()') // Añadimos un orden aleatorio
            ->limit($cantidad)
            ->getQuery();

        $stmt = $this->db->prepare($query);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devolvemos los números en desorden
        } finally {
            $stmt = null;
        }
    }
    


    public function contarVentas($search)
    {
        $totalQuery = (new QuerySelect())->select('COUNT(*) as total')
            ->from('venta v')
            ->innerJoin('cliente c', 'v.id_cliente = c.id')
            ->where(
                "v.id LIKE :search1 OR c.nombre LIKE :search2 OR v.total_pago LIKE :search3 OR v.id_transaccion LIKE :search4 OR v.tipo LIKE :search5 OR v.vendedor LIKE :search6 OR v.fecha LIKE :search7"
            )
            ->getQuery();
        $totalStmt = $this->db->prepare($totalQuery);
        $totalStmt->bindValue(':search1', '%' . $search . '%');
        $totalStmt->bindValue(':search2', '%' . $search . '%');
        $totalStmt->bindValue(':search3', '%' . $search . '%');
        $totalStmt->bindValue(':search4', '%' . $search . '%');
        $totalStmt->bindValue(':search5', '%' . $search . '%');
        $totalStmt->bindValue(':search6', '%' . $search . '%');
        $totalStmt->bindValue(':search7', '%' . $search . '%');
        $totalStmt->execute();
        $totalResult = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $totalStmt = null; // Cerrar la conexión
        return $totalResult['total'];
    }

    public function buscarVentas($search, $limit, $offset)
    {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('v.id, c.nombre AS cliente_nombre, c.celular AS cliente_celular, c.correo AS cliente_correo, v.total_numeros, v.total_pago, v.id_transaccion, v.tipo, v.vendedor, v.fecha')
            ->from('venta v')
            ->innerJoin('cliente c', 'v.id_cliente = c.id')
            ->where(
                "v.id LIKE :search1 OR c.celular LIKE :search2 OR c.nombre LIKE :search3 OR v.total_pago LIKE :search4 OR v.id_transaccion LIKE :search5 OR v.tipo LIKE :search6 OR v.vendedor LIKE :search7 OR v.fecha LIKE :search8"
            )
            ->orderBy('v.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->getQuery();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':search1', '%' . $search . '%');
        $stmt->bindValue(':search2', '%' . $search . '%');
        $stmt->bindValue(':search3', '%' . $search . '%');
        $stmt->bindValue(':search4', '%' . $search . '%');
        $stmt->bindValue(':search5', '%' . $search . '%');
        $stmt->bindValue(':search6', '%' . $search . '%');
        $stmt->bindValue(':search7', '%' . $search . '%');
        $stmt->bindValue(':search8', '%' . $search . '%');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
        return $result;
    }

    public function enviarCorreoConfirmacion($clienteEmail, $asunto, $html)
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try {
            // Configuración del servidor de correo
            $mail->isSMTP();
            $mail->Host = 'mail.nixcol.com'; // Verifica con Latinoamérica Hosting
            $mail->SMTPAuth = true;
            $mail->Username = 'info@nixcol.com';
            $mail->Password = 'Colombia2025*';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usar SMTPS para el puerto 465
            $mail->Port = 465;
    
            // Receptores del correo
            $mail->setFrom('info@nixcol.com', 'Nixcol');
            $mail->addAddress($clienteEmail);
            $mail->addBCC('info@nixcol.com', 'Copia oculta');
    
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = ' ' . $asunto;
            $mail->Body = $html;
    
            // Enviar correo
            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar el correo: " . $mail->ErrorInfo . " - " . $e->getMessage();
        }
    }
    public function buscarVentaPorId($id)
    {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('v.id, c.nombre AS cliente_nombre, c.celular AS cliente_celular, v.tipo, v.vendedor, v.fecha')
            ->from('venta v')
            ->innerJoin('cliente c', 'v.id_cliente = c.id')
            ->where('v.id = :id')
            ->getQuery();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión


    }

    public function obtenerNumerosVendidosPorVenta($ventaId)
    {
        $queryBuilder = new QuerySelect();
        $query = $queryBuilder->select('n.numero')
            ->from('numero_vendido nv')
            ->innerJoin('numero n', 'nv.id_numero = n.id')
            ->where('nv.id_venta = :ventaId')
            ->getQuery();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':ventaId', $ventaId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null; // Cerrar la conexión
    }

    public function obtenerNumerosVendidosPorCliente($celular)
    {
        // Obtener el cliente por su celular
        $cliente = $this->clienteModel->obtenerClientePorCelular($celular);
    
        // Si el cliente existe, obtener los números vendidos
        if ($cliente) {
            // Obtener los números vendidos a este cliente
            $queryBuilder = new QuerySelect();
            $query = $queryBuilder->select('n.numero')
                ->from('numero_vendido nv')
                ->innerJoin('numero n', 'nv.id_numero = n.id')
                ->where('nv.id_cliente = :clienteId')
                ->getQuery();
    
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':clienteId', $cliente['id']);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna los números vendidos
            $stmt = null; // Cerrar la conexión
        } else {
            return [];  // Si no se encuentra el cliente, retorna un arreglo vacío
        }

        
    }

    public function anularVenta($ventaId)
    {
        try {
            // Iniciar la transacción
            $this->db->beginTransaction();

            // 1. Obtener los números vendidos de la venta
            $numerosVendidos = $this->obtenerNumerosVendidosPorVenta($ventaId);
            if (empty($numerosVendidos)) {
                throw new Exception("No se encontraron números vendidos para esta venta.");
            }

            // 2. Actualizar el estado de los números a "Disponible"
            $this->actualizarEstadoNumeros($numerosVendidos, 'Disponible');

            // 3. Eliminar los registros de la tabla "numero_vendido"
            $this->eliminarNumerosVendidos($ventaId);

            // 4. Eliminar la venta de la tabla "venta"
            $this->eliminarVenta($ventaId);

            // Finalizar la transacción
            $this->db->commit();

            return "Venta anulada con éxito.";
        } catch (Exception $e) {
            // Si ocurre algún error, revertir la transacción
            $this->db->rollBack();
            return "Error al anular la venta: " . $e->getMessage();
        }
    }

    public function eliminarNumerosVendidos($ventaId)
    {
        // Eliminar los registros de la tabla "numero_vendido"
        try {
            $queryBuilderDelete = new QueryDelete();
            $queryDelete = $queryBuilderDelete->from('numero_vendido')
                ->where('id_venta = :ventaId')
                ->getQuery();

            $stmtDelete = $this->db->prepare($queryDelete);
            $stmtDelete->bindValue(':ventaId', $ventaId);
            $stmtDelete->execute();
        } catch (Exception $e) {
            throw new Exception("Error al eliminar los números vendidos: " . $e->getMessage());
        } finally {
            $stmtDelete = null;
        }
    }


    public function actualizarEstadoNumerosAnulacion($numeros, $estado)
    {
        // Construir la consulta de actualización para todos los números
        $queryBuilderUpdate = new QueryUpdate();
        $whereClauses = [];
        foreach ($numeros as $index => $numero) {
            $whereClauses[] = ':numero' . $index; // Nombre dinámico para cada número
        }
        $queryUpdate = $queryBuilderUpdate->table('numero')
            ->set(['estado' => ':estado'])  // Usamos :estado como parámetro nombrado
            ->where('numero IN (' . implode(',', $whereClauses) . ')') // Para usar parámetros nombrados
            ->getQuery();

        // Preparar la consulta
        $stmtUpdate = $this->db->prepare($queryUpdate);
        
        try {
            // Asignar el valor del estado
            $stmtUpdate->bindValue(':estado', $estado);

            // Vincular los valores de los números en la consulta
            foreach ($numeros as $index => $numero) {
                $stmtUpdate->bindValue(':numero' . $index, $numero['numero'], PDO::PARAM_INT);  // Vinculamos cada número
            }

            // Ejecutar la consulta
            $stmtUpdate->execute();
        } catch (Exception $e) {
            // Manejo de excepciones en caso de error
            throw new Exception("Error al actualizar el estado de los números: " . $e->getMessage());
        } finally {
            // Liberar el recurso de la sentencia
            $stmtUpdate = null;
        }
    }


    public function eliminarVenta($ventaId)
    {
        // Eliminar la venta de la tabla "venta"
        $queryBuilderDelete = new QueryDelete();
        $queryDelete = $queryBuilderDelete->from('venta')
            ->where('id = :ventaId')
            ->getQuery();

        $stmtDelete = $this->db->prepare($queryDelete);
        try {
            $stmtDelete->bindValue(':ventaId', $ventaId);
            $stmtDelete->execute();
        } finally {
            $stmtDelete = null;
        }
    }

    public function prepararHtmlRespuestaAnulacion($data, $numeros)
    {
        // Asegúrate de que $numeros sea un array
        if (!is_array($numeros)) {
            $numeros = [];
        }

        // Cargar la plantilla HTML
        $templatePath = '../backend/templeate/anulacion.html'; // Asegúrate de que la ruta sea correcta
        $html = file_get_contents($templatePath);



        // Generar los números en formato HTML con el fondo correspondiente
        $numerosHtml = implode('', array_map(function ($numero) {
            return "<span class='number' style=''>" . htmlspecialchars($numero['numero']) . "</span>";
        }, $numeros));

        // Reemplazar las variables en la plantilla con los datos dinámicos
        $html = str_replace(
            ['{clienteNombre}', '{cantidadNumeros}', '{numerosGenerados}'],
            [
                htmlspecialchars($data['cliente']['nombre']),
                htmlspecialchars($data['total_numeros']),
                $numerosHtml
            ],
            $html
        );

        return $html;
    }


   // Método para guardar los datos en la tabla 'respaldo'
   public function guardarRespaldo($nombre_completo, $celular, $email, $departamento, $ciudad, $oportunidades, $total_pago, $transactionId) {
    // Limpiar total_pago para asegurarse de que sea solo un valor numérico
    $total_pago = preg_replace('/\D/', '', $total_pago); // Elimina todos los caracteres no numéricos

    // Usamos el QueryBuilder para construir la consulta de inserción en la tabla 'respaldo'
    $queryBuilderInsert = new QueryInsert();
    $query = $queryBuilderInsert->into('respaldo') // Asegúrate de que la tabla se llame 'respaldo'
        ->columns(['cliente', 'celular', 'correo', 'departamento', 'ciudad', 'oportunidades', 'pago', 'id_transaccion'])
        ->values([
            $nombre_completo,
            $celular,
            $email,
            $departamento,
            $ciudad,
            $oportunidades,
            $total_pago,
            $transactionId
        ])
        ->getQuery();
    
    // Ejecutamos la consulta
    try {
        $stmt = $this->db->prepare($query); // Preparamos la consulta
        $stmt->execute();  // Ejecutamos la consulta
        return true;  // Retorna true si la inserción fue exitosa
    } catch (Exception $e) {
        // Manejo de errores si ocurre algún problema con la base de datos
        error_log("Error al guardar en respaldo: " . $e->getMessage());
        return false;  // Retorna false si hubo un error
    }
}
    
}
