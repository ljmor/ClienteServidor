<?php
/**
 * Modelo de Bloque de Rutina
 *
 * Este modelo representa la capa de acceso a datos para los bloques (días/semanas) de rutinas.
 * Se ejecuta en el SERVIDOR y se comunica con la base de datos.
 */

require_once __DIR__ . '/../config/database.php';

class RoutineBlock {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Obtiene todos los bloques de una rutina desde el SERVIDOR
     *
     * @param int $routineId ID de la rutina
     * @return array Lista de bloques
     */
    public function getByRoutineId($routineId) {
        $sql = "SELECT * FROM routine_blocks WHERE routine_id = :routine_id ORDER BY order_index";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['routine_id' => $routineId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un bloque por ID desde el SERVIDOR
     *
     * @param int $id ID del bloque
     * @return array|false Datos del bloque o false si no existe
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM routine_blocks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crea un nuevo bloque en el SERVIDOR de base de datos
     *
     * @param array $data Datos del bloque
     * @return int ID del bloque creado
     */
    public function create($data) {
        // Obtener el siguiente orden
        $sql = "SELECT COALESCE(MAX(order_index), -1) + 1 as next_order
                FROM routine_blocks WHERE routine_id = :routine_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['routine_id' => $data['routine_id']]);
        $nextOrder = $stmt->fetchColumn();

        $sql = "INSERT INTO routine_blocks (routine_id, block_name, order_index)
                VALUES (:routine_id, :block_name, :order_index)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'routine_id' => $data['routine_id'],
            'block_name' => $data['block_name'],
            'order_index' => $data['order_index'] ?? $nextOrder
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualiza un bloque existente en el SERVIDOR
     *
     * @param int $id ID del bloque
     * @param array $data Datos actualizados
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data) {
        $sql = "UPDATE routine_blocks
                SET block_name = :block_name, order_index = :order_index
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'block_name' => $data['block_name'],
            'order_index' => $data['order_index'] ?? 0
        ]);
    }

    /**
     * Elimina un bloque del SERVIDOR de base de datos
     *
     * @param int $id ID del bloque
     * @return bool True si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM routine_blocks WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
