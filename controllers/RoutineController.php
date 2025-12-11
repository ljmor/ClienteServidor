<?php
/**
 * Controlador de Rutinas
 *
 * Este controlador maneja las peticiones HTTP del CLIENTE y coordina
 * la comunicación entre la VISTA (cliente) y el MODELO (servidor).
 */

require_once __DIR__ . '/../models/Routine.php';
require_once __DIR__ . '/../models/RoutineBlock.php';
require_once __DIR__ . '/../models/RoutineExercise.php';
require_once __DIR__ . '/../models/Exercise.php';

class RoutineController {
    private $routineModel;
    private $blockModel;
    private $routineExerciseModel;
    private $exerciseModel;

    public function __construct() {
        $this->routineModel = new Routine();
        $this->blockModel = new RoutineBlock();
        $this->routineExerciseModel = new RoutineExercise();
        $this->exerciseModel = new Exercise();
    }

    /**
     * Maneja la petición GET del CLIENTE para listar rutinas
     */
    public function index() {
        // SERVIDOR: Obtiene datos de la base de datos
        $routines = $this->routineModel->getAll();

        // SERVIDOR: Genera respuesta HTML para el CLIENTE
        require_once __DIR__ . '/../views/routines/index.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para ver una rutina completa
     */
    public function show() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=routine&action=index&error=not_found');
            exit;
        }

        // SERVIDOR: Obtiene rutina completa con bloques y ejercicios
        $routine = $this->routineModel->getFullRoutine($id);

        if (!$routine) {
            header('Location: /index.php?controller=routine&action=index&error=not_found');
            exit;
        }

        // Obtener lista de ejercicios para el modal de agregar
        $exercises = $this->exerciseModel->getAll();

        require_once __DIR__ . '/../views/routines/show.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para mostrar formulario de creación
     */
    public function create() {
        $errors = [];
        $difficulties = ['facil' => 'Fácil', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'];
        require_once __DIR__ . '/../views/routines/create.php';
    }

    /**
     * Maneja la petición POST del CLIENTE para crear una nueva rutina
     */
    public function store() {
        // SERVIDOR: Valida datos recibidos del CLIENTE
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            // SERVIDOR: Guarda en base de datos
            $id = $this->routineModel->create($_POST);

            // SERVIDOR: Redirige al CLIENTE a ver la rutina
            header('Location: /index.php?controller=routine&action=show&id=' . $id . '&success=created');
            exit;
        }

        // SERVIDOR: Devuelve formulario con errores al CLIENTE
        $difficulties = ['facil' => 'Fácil', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'];
        require_once __DIR__ . '/../views/routines/create.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para mostrar formulario de edición
     */
    public function edit() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=routine&action=index&error=not_found');
            exit;
        }

        // SERVIDOR: Obtiene datos de la rutina
        $routine = $this->routineModel->getById($id);

        if (!$routine) {
            header('Location: /index.php?controller=routine&action=index&error=not_found');
            exit;
        }

        $errors = [];
        $difficulties = ['facil' => 'Fácil', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'];
        require_once __DIR__ . '/../views/routines/edit.php';
    }

    /**
     * Maneja la petición POST del CLIENTE para actualizar una rutina
     */
    public function update() {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /index.php?controller=routine&action=index&error=not_found');
            exit;
        }

        // SERVIDOR: Valida datos del CLIENTE
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            // SERVIDOR: Actualiza en base de datos
            $this->routineModel->update($id, $_POST);

            // SERVIDOR: Redirige al CLIENTE
            header('Location: /index.php?controller=routine&action=show&id=' . $id . '&success=updated');
            exit;
        }

        // SERVIDOR: Devuelve formulario con errores
        $routine = $this->routineModel->getById($id);
        $difficulties = ['facil' => 'Fácil', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'];
        require_once __DIR__ . '/../views/routines/edit.php';
    }

    /**
     * Maneja la petición GET del CLIENTE para eliminar una rutina
     */
    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            // SERVIDOR: Elimina de la base de datos (cascade elimina bloques y ejercicios)
            $this->routineModel->delete($id);
            header('Location: /index.php?controller=routine&action=index&success=deleted');
        } else {
            header('Location: /index.php?controller=routine&action=index&error=delete_failed');
        }
        exit;
    }

    // ==================== BLOQUES ====================

    /**
     * Maneja la petición POST del CLIENTE para agregar un bloque a la rutina
     */
    public function addBlock() {
        $routineId = $_POST['routine_id'] ?? null;
        $blockName = $_POST['block_name'] ?? null;

        if (!$routineId || !$blockName) {
            header('Location: /index.php?controller=routine&action=index&error=invalid_data');
            exit;
        }

        $this->blockModel->create([
            'routine_id' => $routineId,
            'block_name' => $blockName
        ]);

        header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&success=block_added');
        exit;
    }

    /**
     * Maneja la petición GET del CLIENTE para eliminar un bloque
     */
    public function deleteBlock() {
        $blockId = $_GET['block_id'] ?? null;
        $routineId = $_GET['routine_id'] ?? null;

        if ($blockId) {
            $this->blockModel->delete($blockId);
            header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&success=block_deleted');
        } else {
            header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&error=delete_failed');
        }
        exit;
    }

    // ==================== EJERCICIOS DE RUTINA ====================

    /**
     * Maneja la petición POST del CLIENTE para agregar un ejercicio a un bloque
     */
    public function addExercise() {
        $blockId = $_POST['block_id'] ?? null;
        $routineId = $_POST['routine_id'] ?? null;
        $exerciseId = $_POST['exercise_id'] ?? null;

        if (!$blockId || !$exerciseId) {
            header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&error=invalid_data');
            exit;
        }

        $this->routineExerciseModel->create([
            'block_id' => $blockId,
            'exercise_id' => $exerciseId,
            'repetitions' => $_POST['repetitions'] ?? null,
            'estimated_time' => $_POST['estimated_time'] ?? null
        ]);

        header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&success=exercise_added');
        exit;
    }

    /**
     * Maneja la petición GET del CLIENTE para eliminar un ejercicio de un bloque
     */
    public function removeExercise() {
        $exerciseId = $_GET['exercise_id'] ?? null;
        $routineId = $_GET['routine_id'] ?? null;

        if ($exerciseId) {
            $this->routineExerciseModel->delete($exerciseId);
            header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&success=exercise_removed');
        } else {
            header('Location: /index.php?controller=routine&action=show&id=' . $routineId . '&error=remove_failed');
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

        if (empty($data['difficulty'])) {
            $errors['difficulty'] = 'La dificultad es requerida';
        } elseif (!in_array($data['difficulty'], ['facil', 'intermedio', 'avanzado'])) {
            $errors['difficulty'] = 'La dificultad no es válida';
        }

        return $errors;
    }
}
