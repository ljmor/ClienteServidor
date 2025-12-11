<?php
/**
 * Modelo de Ejercicio de Rutina
 *
 * Este modelo representa la capa de acceso a datos para los ejercicios dentro de bloques de rutinas.
 * Se ejecuta en el SERVIDOR y se comunica con la base de datos.
 */

require_once __DIR__ . '/../config/database.php';

class RoutineExercise {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Obtiene todos los ejercicios de un bloque desde el SERVIDOR
     *
     * @param int $blockId ID del bloque
     * @return array Lista de ejercicios con datos del ejercicio base
     */
    public function getByBlockId($blockId) {
        $sql = "SELECT re.*, e.name as exercise_name, e.description as exercise_description,
                       e.muscle_group, e.equipment
                FROM routine_exercises re
                JOIN exercises e ON re.exercise_id = e.id
                WHERE re.block_id = :block_id
                ORDER BY re.order_index";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['block_id' => $blockId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un ejercicio de rutina por ID desde el SERVIDOR
     *
     * @param int $id ID del ejercicio de rutina
     * @return array|false Datos del ejercicio o false si no existe
     */
    public function getById($id) {
        $sql = "SELECT re.*, e.name as exercise_name, e.description as exercise_description,
                       e.muscle_group, e.equipment
                FROM routine_exercises re
                JOIN exercises e ON re.exercise_id = e.id
                WHERE re.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Añade un ejercicio a un bloque en el SERVIDOR de base de datos
     *
     * @param array $data Datos del ejercicio de rutina
     * @return int ID del registro creado
     */
    public function create($data) {
        // Obtener el siguiente orden
        $sql = "SELECT COALESCE(MAX(order_index), -1) + 1 as next_order
                FROM routine_exercises WHERE block_id = :block_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['block_id' => $data['block_id']]);
        $nextOrder = $stmt->fetchColumn();

        $sql = "INSERT INTO routine_exercises (block_id, exercise_id, repetitions, estimated_time, order_index)
                VALUES (:block_id, :exercise_id, :repetitions, :estimated_time, :order_index)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'block_id' => $data['block_id'],
            'exercise_id' => $data['exercise_id'],
            'repetitions' => $data['repetitions'] ?? null,
            'estimated_time' => $data['estimated_time'] ?? null,
            'order_index' => $data['order_index'] ?? $nextOrder
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualiza un ejercicio de rutina existente en el SERVIDOR
     *
     * @param int $id ID del ejercicio de rutina
     * @param array $data Datos actualizados
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data) {
        $sql = "UPDATE routine_exercises
                SET exercise_id = :exercise_id, repetitions = :repetitions,
                    estimated_time = :estimated_time, order_index = :order_index
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'exercise_id' => $data['exercise_id'],
            'repetitions' => $data['repetitions'] ?? null,
            'estimated_time' => $data['estimated_time'] ?? null,
            'order_index' => $data['order_index'] ?? 0
        ]);
    }

    /**
     * Elimina un ejercicio de un bloque del SERVIDOR de base de datos
     *
     * @param int $id ID del ejercicio de rutina
     * @return bool True si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM routine_exercises WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
