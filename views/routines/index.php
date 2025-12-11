<?php
/**
 * Vista: Lista de Rutinas
 *
 * CLIENTE: Esta vista se renderiza en el navegador del usuario.
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Gestion de Rutinas</h2>

<div class="actions">
    <a href="/index.php?controller=routine&action=create" class="btn btn-primary">+ Nueva Rutina</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Objetivo</th>
            <th>Dificultad</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($routines)): ?>
            <tr>
                <td colspan="6" class="text-center">No hay rutinas registradas</td>
            </tr>
        <?php else: ?>
            <?php foreach ($routines as $routine): ?>
                <tr>
                    <td><?php echo htmlspecialchars($routine['id']); ?></td>
                    <td>
                        <a href="/index.php?controller=routine&action=show&id=<?php echo $routine['id']; ?>">
                            <?php echo htmlspecialchars($routine['name']); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($routine['objective'] ?? '-'); ?></td>
                    <td>
                        <?php
                        $difficultyClass = [
                            'facil' => 'success',
                            'intermedio' => 'warning',
                            'avanzado' => 'danger'
                        ];
                        $difficultyLabel = [
                            'facil' => 'Facil',
                            'intermedio' => 'Intermedio',
                            'avanzado' => 'Avanzado'
                        ];
                        ?>
                        <span class="badge badge-<?php echo $difficultyClass[$routine['difficulty']] ?? 'info'; ?>">
                            <?php echo htmlspecialchars($difficultyLabel[$routine['difficulty']] ?? $routine['difficulty']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo $routine['status'] === 'active' ? 'success' : 'warning'; ?>">
                            <?php echo htmlspecialchars($routine['status']); ?>
                        </span>
                    </td>
                    <td class="actions-cell">
                        <a href="/index.php?controller=routine&action=show&id=<?php echo $routine['id']; ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="/index.php?controller=routine&action=edit&id=<?php echo $routine['id']; ?>" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="/index.php?controller=routine&action=delete&id=<?php echo $routine['id']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Esta seguro de eliminar esta rutina? Se eliminaran todos los bloques y ejercicios asociados.')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
