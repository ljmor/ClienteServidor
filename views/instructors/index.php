<?php
/**
 * Vista: Lista de Instructores
 * 
 * CLIENTE: Esta vista se renderiza en el navegador del usuario.
 */

require_once __DIR__ . '/../layouts/header.php';
?>

<h2>Gestión de Instructores</h2>

<div class="actions">
    <a href="/index.php?controller=instructor&action=create" class="btn btn-primary">➕ Nuevo Instructor</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Especialización</th>
            <th>Fecha Contratación</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($instructors)): ?>
            <tr>
                <td colspan="8" class="text-center">No hay instructores registrados</td>
            </tr>
        <?php else: ?>
            <?php foreach ($instructors as $instructor): ?>
                <tr>
                    <td><?php echo htmlspecialchars($instructor['id']); ?></td>
                    <td><?php echo htmlspecialchars($instructor['name']); ?></td>
                    <td><?php echo htmlspecialchars($instructor['email']); ?></td>
                    <td><?php echo htmlspecialchars($instructor['phone'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($instructor['specialization'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($instructor['hire_date']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $instructor['status'] === 'active' ? 'success' : 'warning'; ?>">
                            <?php echo htmlspecialchars($instructor['status']); ?>
                        </span>
                    </td>
                    <td class="actions-cell">
                        <a href="/index.php?controller=instructor&action=edit&id=<?php echo $instructor['id']; ?>" class="btn btn-sm btn-secondary">Editar</a>
                        <a href="/index.php?controller=instructor&action=delete&id=<?php echo $instructor['id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Está seguro de eliminar este instructor?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>