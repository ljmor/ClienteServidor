<?php
/**
 * Vista: Detalle de Rutina
 *
 * CLIENTE: Esta vista muestra la rutina completa con bloques y ejercicios.
 */

require_once __DIR__ . '/../layouts/header.php';

$difficultyLabel = [
    'facil' => 'Facil',
    'intermedio' => 'Intermedio',
    'avanzado' => 'Avanzado'
];
$difficultyClass = [
    'facil' => 'success',
    'intermedio' => 'warning',
    'avanzado' => 'danger'
];
?>

<div class="routine-header">
    <div class="routine-info">
        <h2><?php echo htmlspecialchars($routine['name']); ?></h2>
        <div class="routine-meta">
            <span class="badge badge-<?php echo $difficultyClass[$routine['difficulty']] ?? 'info'; ?>">
                <?php echo htmlspecialchars($difficultyLabel[$routine['difficulty']] ?? $routine['difficulty']); ?>
            </span>
            <span class="badge badge-<?php echo $routine['status'] === 'active' ? 'success' : 'warning'; ?>">
                <?php echo htmlspecialchars($routine['status']); ?>
            </span>
        </div>
        <?php if (!empty($routine['objective'])): ?>
            <p class="routine-objective"><strong>Objetivo:</strong> <?php echo htmlspecialchars($routine['objective']); ?></p>
        <?php endif; ?>
    </div>
    <div class="routine-actions">
        <a href="/index.php?controller=routine&action=edit&id=<?php echo $routine['id']; ?>" class="btn btn-secondary">Editar Rutina</a>
        <a href="/index.php?controller=routine&action=index" class="btn btn-secondary">Volver a Lista</a>
    </div>
</div>

<hr>

<!-- Seccion de Bloques -->
<div class="blocks-section">
    <div class="section-header">
        <h3>Bloques / Dias</h3>
    </div>

    <!-- Formulario para agregar bloque -->
    <form method="POST" action="/index.php?controller=routine&action=addBlock" class="inline-form">
        <input type="hidden" name="routine_id" value="<?php echo $routine['id']; ?>">
        <div class="form-inline">
            <input type="text" name="block_name" placeholder="Nombre del bloque (ej: Dia 1, Lunes, Semana 1)" required>
            <button type="submit" class="btn btn-primary btn-sm">+ Agregar Bloque</button>
        </div>
    </form>

    <!-- Lista de Bloques -->
    <?php if (empty($routine['blocks'])): ?>
        <div class="empty-state">
            <p>No hay bloques en esta rutina. Agrega un bloque para comenzar a estructurar tu rutina.</p>
        </div>
    <?php else: ?>
        <?php foreach ($routine['blocks'] as $block): ?>
            <div class="block-card">
                <div class="block-header">
                    <h4><?php echo htmlspecialchars($block['block_name']); ?></h4>
                    <a href="/index.php?controller=routine&action=deleteBlock&block_id=<?php echo $block['id']; ?>&routine_id=<?php echo $routine['id']; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Eliminar este bloque? Se eliminaran todos los ejercicios del bloque.')">
                        Eliminar Bloque
                    </a>
                </div>

                <!-- Formulario para agregar ejercicio al bloque -->
                <form method="POST" action="/index.php?controller=routine&action=addExercise" class="add-exercise-form">
                    <input type="hidden" name="routine_id" value="<?php echo $routine['id']; ?>">
                    <input type="hidden" name="block_id" value="<?php echo $block['id']; ?>">
                    <div class="form-row">
                        <select name="exercise_id" required>
                            <option value="">Seleccionar ejercicio...</option>
                            <?php foreach ($exercises as $exercise): ?>
                                <option value="<?php echo $exercise['id']; ?>">
                                    <?php echo htmlspecialchars($exercise['name']); ?> (<?php echo htmlspecialchars($exercise['muscle_group']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="repetitions" placeholder="Repeticiones (ej: 3x12)">
                        <input type="number" name="estimated_time" placeholder="Tiempo (min)" min="1">
                        <button type="submit" class="btn btn-sm btn-primary">+ Agregar</button>
                    </div>
                </form>

                <!-- Lista de ejercicios del bloque -->
                <?php if (empty($block['exercises'])): ?>
                    <p class="text-muted">No hay ejercicios en este bloque.</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ejercicio</th>
                                <th>Grupo Muscular</th>
                                <th>Repeticiones</th>
                                <th>Tiempo Est.</th>
                                <th>Equipamiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($block['exercises'] as $exercise): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($exercise['exercise_name']); ?></strong>
                                        <?php if (!empty($exercise['exercise_description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($exercise['exercise_description']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($exercise['muscle_group']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($exercise['repetitions'] ?? '-'); ?></td>
                                    <td><?php echo $exercise['estimated_time'] ? htmlspecialchars($exercise['estimated_time']) . ' min' : '-'; ?></td>
                                    <td>
                                        <?php if (!empty($exercise['equipment'])): ?>
                                            <?php foreach (explode(',', $exercise['equipment']) as $equip): ?>
                                                <span class="tag"><?php echo htmlspecialchars(trim($equip)); ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/index.php?controller=routine&action=removeExercise&exercise_id=<?php echo $exercise['id']; ?>&routine_id=<?php echo $routine['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Quitar este ejercicio del bloque?')">
                                            Quitar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.routine-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}
.routine-meta {
    margin: 0.5rem 0;
}
.routine-meta .badge {
    margin-right: 0.5rem;
}
.routine-objective {
    margin-top: 0.5rem;
    color: #666;
}
.routine-actions {
    display: flex;
    gap: 0.5rem;
}
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.inline-form {
    margin-bottom: 1.5rem;
}
.form-inline {
    display: flex;
    gap: 0.5rem;
}
.form-inline input[type="text"] {
    flex: 1;
    padding: 0.5rem;
}
.block-card {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}
.block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #ddd;
}
.block-header h4 {
    margin: 0;
}
.add-exercise-form {
    margin-bottom: 1rem;
}
.form-row {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.form-row select,
.form-row input {
    padding: 0.4rem;
}
.form-row select {
    flex: 2;
    min-width: 200px;
}
.form-row input[type="text"] {
    flex: 1;
    min-width: 100px;
}
.form-row input[type="number"] {
    width: 100px;
}
.table-sm {
    font-size: 0.9rem;
}
.table-sm th,
.table-sm td {
    padding: 0.5rem;
}
.empty-state {
    text-align: center;
    padding: 2rem;
    background: #f5f5f5;
    border-radius: 8px;
    color: #666;
}
.tag {
    display: inline-block;
    background: #e0e0e0;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    margin: 0.1rem;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
