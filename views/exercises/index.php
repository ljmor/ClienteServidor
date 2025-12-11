<?php
/**
 * Vista: Lista de Ejercicios
 *
 * CLIENTE: Esta vista se renderiza en el navegador del usuario.
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Catalogo de Ejercicios</h2>

<div class="actions">
    <a href="/index.php?controller=exercise&action=create" class="btn btn-primary">+ Nuevo Ejercicio</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Grupo Muscular</th>
            <th>Equipamiento</th>
            <th>Descripcion</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($exercises)): ?>
            <tr>
                <td colspan="6" class="text-center">No hay ejercicios registrados</td>
            </tr>
        <?php else: ?>
            <?php foreach ($exercises as $exercise): ?>
                <tr>
                    <td><?php echo htmlspecialchars($exercise['id']); ?></td>
                    <td><?php echo htmlspecialchars($exercise['name']); ?></td>
                    <td>
                        <span class="badge badge-info">
                            <?php echo htmlspecialchars($exercise['muscle_group']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($exercise['equipment'])): ?>
                            <?php foreach (explode(',', $exercise['equipment']) as $equip): ?>
                                <span class="tag"><?php echo htmlspecialchars(trim($equip)); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">Sin equipamiento</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($exercise['description'] ?? '-'); ?></td>
                    <td class="actions-cell">
                        <a href="/index.php?controller=exercise&action=edit&id=<?php echo $exercise['id']; ?>" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="/index.php?controller=exercise&action=delete&id=<?php echo $exercise['id']; ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Esta seguro de eliminar este ejercicio?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
