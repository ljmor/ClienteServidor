<?php
/**
 * Modelo de Rutina
 *
 * Este modelo representa la capa de acceso a datos para las rutinas de entrenamiento.
 * Se ejecuta en el SERVIDOR y se comunica con la base de datos.
 */

require_once __DIR__ . '/../config/database.php';

class Routine {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Obtiene todas las rutinas desde el SERVIDOR de base de datos
     *
     * @return array Lista de rutinas
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM routines ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una rutina por ID desde el SERVIDOR
     *
     * @param int $id ID de la rutina
     * @return array|false Datos de la rutina o false si no existe
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM routines WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtiene una rutina con todos sus bloques y ejercicios
     *
     * @param int $id ID de la rutina
     * @return array|false Datos completos de la rutina
     */
    public function getFullRoutine($id) {
        $routine = $this->getById($id);
        if (!$routine) {
            return false;
        }

        // Obtener bloques de la rutina
        $sql = "SELECT * FROM routine_blocks WHERE routine_id = :routine_id ORDER BY order_index";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['routine_id' => $id]);
        $blocks = $stmt->fetchAll();

        // Para cada bloque, obtener sus ejercicios
        foreach ($blocks as &$block) {
            $sql = "SELECT re.*, e.name as exercise_name, e.description as exercise_description,
                           e.muscle_group, e.equipment
                    FROM routine_exercises re
                    JOIN exercises e ON re.exercise_id = e.id
                    WHERE re.block_id = :block_id
                    ORDER BY re.order_index";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['block_id' => $block['id']]);
            $block['exercises'] = $stmt->fetchAll();
        }

        $routine['blocks'] = $blocks;
        return $routine;
    }

    /**
     * Crea una nueva rutina en el SERVIDOR de base de datos
     *
     * @param array $data Datos de la rutina
     * @return int ID de la rutina creada
     */
    public function create($data) {
        $sql = "INSERT INTO routines (name, objective, difficulty)
                VALUES (:name, :objective, :difficulty)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'objective' => $data['objective'] ?? null,
            'difficulty' => $data['difficulty']
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualiza una rutina existente en el SERVIDOR
     *
     * @param int $id ID de la rutina
     * @param array $data Datos actualizados
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data) {
        $sql = "UPDATE routines
                SET name = :name, objective = :objective,
                    difficulty = :difficulty, status = :status
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'objective' => $data['objective'] ?? null,
            'difficulty' => $data['difficulty'],
            'status' => $data['status'] ?? 'active'
        ]);
    }

    /**
     * Elimina una rutina del SERVIDOR de base de datos
     *
     * @param int $id ID de la rutina
     * @return bool True si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM routines WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
