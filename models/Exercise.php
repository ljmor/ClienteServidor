<?php
/**
 * Modelo de Ejercicio
 *
 * Este modelo representa la capa de acceso a datos para los ejercicios.
 * Se ejecuta en el SERVIDOR y se comunica con la base de datos.
 */

require_once __DIR__ . '/../config/database.php';

class Exercise {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Obtiene todos los ejercicios desde el SERVIDOR de base de datos
     *
     * @return array Lista de ejercicios
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM exercises ORDER BY muscle_group, name");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un ejercicio por ID desde el SERVIDOR
     *
     * @param int $id ID del ejercicio
     * @return array|false Datos del ejercicio o false si no existe
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM exercises WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crea un nuevo ejercicio en el SERVIDOR de base de datos
     *
     * @param array $data Datos del ejercicio
     * @return int ID del ejercicio creado
     */
    public function create($data) {
        $sql = "INSERT INTO exercises (name, description, muscle_group, equipment)
                VALUES (:name, :description, :muscle_group, :equipment)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'muscle_group' => $data['muscle_group'],
            'equipment' => $data['equipment'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Actualiza un ejercicio existente en el SERVIDOR
     *
     * @param int $id ID del ejercicio
     * @param array $data Datos actualizados
     * @return bool True si se actualizó correctamente
     */
    public function update($id, $data) {
        $sql = "UPDATE exercises
                SET name = :name, description = :description,
                    muscle_group = :muscle_group, equipment = :equipment
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'muscle_group' => $data['muscle_group'],
            'equipment' => $data['equipment'] ?? null
        ]);
    }

    /**
     * Elimina un ejercicio del SERVIDOR de base de datos
     *
     * @param int $id ID del ejercicio
     * @return bool True si se eliminó correctamente
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM exercises WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Obtiene los grupos musculares únicos
     *
     * @return array Lista de grupos musculares
     */
    public function getMuscleGroups() {
        $stmt = $this->db->query("SELECT DISTINCT muscle_group FROM exercises ORDER BY muscle_group");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
