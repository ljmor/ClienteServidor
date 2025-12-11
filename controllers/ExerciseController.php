<?php
/**
 * Controlador de Ejercicios
 *
 * Este controlador maneja las peticiones HTTP del CLIENTE y coordina
 * la comunicación entre la VISTA (cliente) y el MODELO (servidor).
 */

require_once __DIR__ . '/../models/Exercise.php';

class ExerciseController {
    private $exerciseModel;

    public function __construct() {
        $this->exerciseModel = new Exercise();
    }

    /**
     * Maneja la petición GET del CLIENTE para listar ejercicios
     */
    public function index() {
        // SERVIDOR: Obtiene datos de la base de datos
        $exercises = $this->exerciseModel->getAll();

        // SERVIDOR: Genera respuesta HTML para el CLIENTE
        require_once __DIR__ . '/../views/exercises/index.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para mostrar formulario de creación
     */
    public function create() {
        $errors = [];
        $muscleGroups = ['Pecho', 'Espalda', 'Piernas', 'Hombros', 'Brazos', 'Core', 'Cardio', 'Cuerpo Completo'];
        require_once __DIR__ . '/../views/exercises/create.php';
    }

    /**
     * Maneja la petición POST del CLIENTE para crear un nuevo ejercicio
     */
    public function store() {
        // SERVIDOR: Valida datos recibidos del CLIENTE
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            // SERVIDOR: Guarda en base de datos
            $id = $this->exerciseModel->create($_POST);

            // SERVIDOR: Redirige al CLIENTE a la lista
            header('Location: /index.php?controller=exercise&action=index&success=created');
            exit;
        }

        // SERVIDOR: Devuelve formulario con errores al CLIENTE
        $muscleGroups = ['Pecho', 'Espalda', 'Piernas', 'Hombros', 'Brazos', 'Core', 'Cardio', 'Cuerpo Completo'];
        require_once __DIR__ . '/../views/exercises/create.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para mostrar formulario de edición
     */
    public function edit() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=exercise&action=index&error=not_found');
            exit;
        }

        // SERVIDOR: Obtiene datos del ejercicio
        $exercise = $this->exerciseModel->getById($id);

        if (!$exercise) {
            header('Location: /index.php?controller=exercise&action=index&error=not_found');
            exit;
        }

        $errors = [];
        $muscleGroups = ['Pecho', 'Espalda', 'Piernas', 'Hombros', 'Brazos', 'Core', 'Cardio', 'Cuerpo Completo'];
        require_once __DIR__ . '/../views/exercises/edit.php';
    }

    /**
     * Maneja la petición POST del CLIENTE para actualizar un ejercicio
     */
    public function update() {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=exercise&action=index&error=not_found');
            exit;
        }

        // SERVIDOR: Valida datos del CLIENTE
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            // SERVIDOR: Actualiza en base de datos
            $this->exerciseModel->update($id, $_POST);

            // SERVIDOR: Redirige al CLIENTE
            header('Location: /index.php?controller=exercise&action=index&success=updated');
            exit;
        }

        // SERVIDOR: Devuelve formulario con errores
        $exercise = $this->exerciseModel->getById($id);
        $muscleGroups = ['Pecho', 'Espalda', 'Piernas', 'Hombros', 'Brazos', 'Core', 'Cardio', 'Cuerpo Completo'];
        require_once __DIR__ . '/../views/exercises/edit.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para eliminar un ejercicio
     */
    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            // SERVIDOR: Elimina de la base de datos
            $this->exerciseModel->delete($id);
            header('Location: /index.php?controller=exercise&action=index&success=deleted');
        } else {
            header('Location: /index.php?controller=exercise&action=index&error=delete_failed');
        }
        exit;
    }

    /**
     * SERVIDOR: Valida los datos recibidos del CLIENTE
     *
     * @param array $data Datos del formulario del CLIENTE
     * @return array Errores de validación
     */
    private function validate($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'El nombre es requerido';
        }

        if (empty($data['muscle_group'])) {
            $errors['muscle_group'] = 'El grupo muscular es requerido';
        }

        return $errors;
    }
}
